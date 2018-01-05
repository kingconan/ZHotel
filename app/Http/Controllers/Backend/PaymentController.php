<?php

namespace App\Http\Controllers\Backend;

use App\Models\Order;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

require_once ('alipay-sdk/aop/AopClient.php');
require_once ('alipay-sdk/aop/request/AlipayTradeAppPayRequest.php');
require_once ('alipay-sdk/aop/request/AlipayTradePagePayRequest.php');
require_once ('alipay-sdk/aop/request/AlipayTradeWapPayRequest.php');

require_once ('wechat-sdk/WxPay.Data.php');
require_once ('wechat-sdk/WxPay.Api.php');

/**
    https://openhome.alipay.com/platform/appManage.htm 创建应用
    zyoutrip.com
 */
class PaymentController extends Controller
{

    /**
     * Wechat Pay
     * Web :
     * trade type = NATIVE
     */

    public function createOpenIdUrl(){
        $format = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=%s&state=%s#wechat_redirect';
        $orderId = "order123";
        $paymentId = "payment999";

        $appId = "";
        $scope = "snsapi_base";
        $state = $orderId."_".$orderId;
        $redirectUrl = "http://zyoutrip.com/wechat/redirect";

        $url = sprintf($format, $appId, urlencode($redirectUrl), $scope, $state);

        return $url;
    }

    public function parseOpenIdRedirect(Request $request){
        $code = $request->input("code");
        $state = $request->input("state");

        $format = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code';
        $appId = "";
        $appSecret = "";
        $url = sprintf($format, $appId, $appSecret, $code);

        $client = new Client();
        $res = $client->get($url);

        if($res->getStatusCode() == 200){
            $json_str = $res->getBody();
            $json = json_decode($json_str);
            if(isset($json->openid)){
                $open_id = $json->openid;

                //TODO: create WxPayUnifiedOrder
                //TODO: get js config
                //TODO: return View
            }
            else{
                abort(404);
            }

        }
    }

    public function weChatWebConfig($payment){
        $price = $payment["price"] * 100;//covert to FEN
        $title = $payment["title"];
        $description = $payment["description"];
        $goodTag = "";
        $notifyUrl = "";
        $orderId = $payment["order_id"];
        $paymentId = $payment["payment_id"];
        $hour = 60*60;
        $expire = $hour;

        $input = new \WxPayUnifiedOrder();
        $input->SetBody($title);
        $input->SetAttach($orderId."_".$paymentId);//附件数据
        $input->SetOut_trade_no(\WxPayConfig::MCHID.date("YmdHis"));
        $input->SetTotal_fee($price);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + $expire));
        $input->SetGoods_tag($goodTag);
        $input->SetNotify_url($notifyUrl);
        $input->SetTrade_type("NATIVE");
        $input->SetProduct_id($orderId);

        $order = \WxPayApi::unifiedOrder($input);

        //将$order["code_url"]变成二维码让用户扫描

        return $order;
    }

    public function weChatJsConfig($payment){
        $price = $payment["price"] * 100;//covert to FEN
        $openId = $payment["openId"];
        $title = $payment["title"];
        $description = $payment["description"];
        $goodTag = "";
        $notifyUrl = "";
        $orderId = $payment["order_id"];
        $paymentId = $payment["payment_id"];
        $hour = 60*60;
        $expire = $hour;

        $input = new \WxPayUnifiedOrder();
        $input->SetBody($title);//商品描述
        $input->SetAttach($orderId."_".$paymentId);//附件数据
        $input->SetOut_trade_no(\WxPayConfig::MCHID.date("YmdHis"));//商户订单号
        $input->SetTotal_fee($price);
        $input->SetTime_start(date("YmdHis"));//交易开始时间
        $input->SetTime_expire(date("YmdHis", time() + $expire));//交易结束时间
        $input->SetGoods_tag($goodTag);//商品标记
        $input->SetNotify_url($notifyUrl);//回调地址
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);


        $order = \WxPayApi::unifiedOrder($input);
        $config = self::_getJsConfig($order);


        return $config;
    }

    private function _getJsConfig($order){
        if(!array_key_exists("appid", $order)
            || !array_key_exists("prepay_id", $order)
            || $order['prepay_id'] == ""){
            throw new \WxPayException("parameter wrong, wechat");
        }
        $jsapi = new \WxPayJsApiPay();
        $jsapi->SetAppid($order["appid"]);
        $timeStamp = strval(time());//必须转成string
        $jsapi->SetTimeStamp($timeStamp);
        $jsapi->SetNonceStr(\WxPayApi::getNonceStr());
        $jsapi->SetPackage("prepay_id=" . $order['prepay_id']);
        $jsapi->SetSignType("MD5");
        $jsapi->SetPaySign($jsapi->MakeSign());
        $parameters = json_encode($jsapi->GetValues());
        return $parameters;
    }


    public function weChatNotify(Request $request){
        $raw = file_get_contents('php://input');
        $message = simplexml_load_string($raw, 'SimpleXMLElement', LIBXML_NOCDATA);
        if($message->return_code == 'SUCCESS'){
            if($message->result_code == 'SUCCESS'){
                $attach = (string)$message->attach;
                //TODO parse here
            }
        }
        return '<xml>
                <return_code><![CDATA[SUCCESS]]></return_code>
                <return_msg><![CDATA[OK]]></return_msg>
                </xml>';
    }


    /**
     * Ali Payment
     */
    const config_ali_web = [
        "gateway" => "https://openapi.alipay.com/gateway.do",
        "app_id" => "2017032206346257",
        "return_url" => "",
        "notify_url" => "",
    ];
    public function getAliWebPay(Request $request){
        $debug = true;
        if($debug){
            $payment = [
                'title' => "test",
                'price' => 0.01,
                'order_id' => "oid",
                'payment_id' => "pid",
                'description' => 'test'
            ];

            $config = self::aliWebConfig($payment);
            echo $config;
            return;
        }
        $orderId = $request->input("order_id");
        $order = Order::find($orderId);
        if(!$order){
            abort(404);
        }
        $orderId = $order->_id;
        $paymentId = $order->payment_id;

        if($paymentId < 1){
            echo "没有找到支付账单,请联系客服~";
        }

        $title = $order->payment_memo;
        $price = $order->payment_price;

        $payment = [
            'title' => $title,
            'price' => $price,
            'order_id' => $orderId,
            'payment_id' => $paymentId,
            'description' => ''
        ];

        $config = self::aliWebConfig($payment);

        echo $config;


    }

    public function aliWebConfig($payment){
        $title = $payment["title"];
        $price = $payment["price"];
        $description = $payment["description"];
        $orderId = $payment["order_id"];
        $paymentId = $payment["payment_id"];



        $aopClient = self::createAliClient();

        $bizContent = [
            "body" => $description,
            "subject" => urldecode($title),
            "out_trade_no" => $orderId."_".$paymentId,
            "timeout_express" => "30m",
            "total_amount" => $price,
            "product_code" => "FAST_INSTANT_TRADE_PAY",
        ];

        $request = new \AlipayTradePagePayRequest();
        $request->setNotifyUrl(PaymentController::config_ali_web['notify_url']);
        $request->setReturnUrl(PaymentController::config_ali_web['return_url']);
        $request->setBizContent(json_encode($bizContent));

        $response = $aopClient->pageExecute($request);
        return $response;
    }
    public function aliH5Config($payment){
        $title = $payment["title"];
        $price = $payment["price"];
        $description = $payment["description"];
        $orderId = $payment["order_id"];
        $paymentId = $payment["payment_id"];



        $aopClient = self::createAliClient();

        $bizContent = [
            "body" => $description,
            "subject" => urldecode($title),
            "out_trade_no" => $orderId."_".$paymentId,
            "timeout_express" => "30m",
            "total_amount" => $price,
            "product_code" => "QUICK_WAP_WAY",
        ];

        $request = new \AlipayTradeWapPayRequest();
        $request->setNotifyUrl(PaymentController::config_ali_web['notify_url']);
        $request->setReturnUrl(PaymentController::config_ali_web['return_url']);
        $request->setBizContent(json_encode($bizContent));

        $response = $aopClient->pageExecute($request);
        return $response;
    }
    public function aliAppConfig($payment){
        $title = $payment["title"];
        $price = $payment["price"];
        $description = $payment["description"];
        $orderId = $payment["order_id"];
        $paymentId = $payment["payment_id"];



        $aopClient = self::createAliClient();

        $bizContent = [
            "body" => $description,
            "subject" => urldecode($title),
            "out_trade_no" => $orderId."_".$paymentId,
            "timeout_express" => "30m",
            "total_amount" => $price,
            "product_code" => "APP_PAY",
        ];

        $request = new \AlipayTradeAppPayRequest();
        $request->setNotifyUrl(PaymentController::config_ali_web['notify_url']);
        $request->setBizContent(json_encode($bizContent));

        $response = $aopClient->sdkExecute($request);
        return $response;
    }

    public function aliNotifyUrl(Request $request){
        $arr = $_POST;
        $res = self::checkAliResponse($arr);
        if($res){
            $trade_no = $request->input("trade_no");
            $out_trade_no = $request->input("out_trade_no");
            $trade_status = $request->input("trade_status");
            $total_fee = $request->input("total_fee");
            $id_arr = explode("_", $out_trade_no);
            if(count($id_arr) == 2){
                $orderId = $id_arr[0];
                $paymentId = $id_arr[1];
                //TODO update order info
                //TODO notify admin
            }
        }
        else{
            echo "Invalid Parameters from Ali";
        }
    }
    public function aliReturnUrl(Request $request){
        $arr = $_GET;
        $res = self::checkAliResponse($arr);
        //TODO return page here
        if($res){
            $trade_no = $request->input("trade_no");
            echo "验证成功<br />支付宝交易号：".$trade_no;
        }
        else{
            echo "验证失败";
        }
    }


    private function createAliClient(){
        $c = new \AopClient();
        $c->gatewayUrl = PaymentController::config_ali_web['gateway'];
        $c->appId = PaymentController::config_ali_web['app_id'];
        $c->rsaPrivateKeyFilePath = storage_path(env("ALI_RSA_PATH",""));
        $c->format = "json";
        $c->charset= "UTF-8";
        $c->signType= "RSA2";
        $c->alipayrsaPublicKey = env("ALI_PUB","");

        return $c;
    }
    private function checkAliResponse($arr){
        if(empty($arr)) return false;
        $c = new \AopClient();
        $c->alipayrsaPublicKey = env("ALI_PUB","");
        $c->signType= "RSA2";
        return $c->rsaCheckV1($arr,$c->alipayrsaPublicKey,$c->signType);
    }
    //===================== END ======================



}
