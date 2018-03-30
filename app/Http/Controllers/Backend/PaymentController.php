<?php

namespace App\Http\Controllers\Backend;

use App\Models\Order;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\URL;

require_once ('alipay-sdk/aop/AopClient.php');
require_once ('alipay-sdk/aop/request/AlipayTradeAppPayRequest.php');
require_once ('alipay-sdk/aop/request/AlipayTradePagePayRequest.php');
require_once ('alipay-sdk/aop/request/AlipayTradeWapPayRequest.php');

require_once ('wechat-sdk/WxPay.Data.php');
require_once ('wechat-sdk/WxPay.Api.php');
require_once ('wechat-sdk/WxPay.Config.php');

/**
    https://openhome.alipay.com/platform/appManage.htm 创建应用
    zyoutrip.com
 */
class PaymentController extends Controller
{


    public function testPayment(Request $request){
        $action = $request->input("action");
        $title = $request->input("title");
        $order_id = $request->input("order_id");
        $payment_id = $request->input("payment_id");
        $description = $request->input("description");
        $price = $request->input("price");
//        $payment = [
//            'title' => "test",
//            'price' => 0.01,
//            'order_id' => "oid",
//            'payment_id' => "pid",
//            'description' => 'test'
//        ];
        $payment = [
            'title' => $title,
            'price' => $price,
            'order_id' => $order_id,
            'payment_id' => $payment_id,
            'description' => $description
        ];
        if($action == "微信"){
            $config = self::weChatWebConfig($payment);
            if($config["return_code"] == "SUCCESS"){
//                echo $config["code_url"];
//                echo "<br />";
//                echo QrCode::size(100)->generate($config["code_url"]);
                return View::make('frontend.index.payment_wechat',
                    array(
                        'url' => $config["code_url"],
                        'des' => $description,
                        'title' => $title,
                        'price' => $price
                     )
                );
            }
            else{
                echo $config["return_msg"];
            }
        }
        else if($action == "支付宝"){
            $config = self::aliWebConfig($payment);
            echo $config;
        }
    }
    /**
     * Wechat Pay
     * Web :
     * trade type = NATIVE
     */

    public function createOpenIdUrl(){
        $format = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=%s&state=%s#wechat_redirect';
        $orderId = "order123";
        $paymentId = "payment999";

        $appId = \WxPayConfig::APPID;
        $scope = "snsapi_base";
        $state = $orderId."".$orderId;
        $redirectUrl = "http://zyoutrip.com/wechat/payment/redirect";

        $url = sprintf($format, $appId, urlencode($redirectUrl), $scope, $state);

        return $url;
    }

    //Q1 回调域名必须在微信公众平台-功能下设置过,否则会出现10003不匹配的问题
    public function parseOpenIdRedirect(Request $request){
        $ip = $request->getClientIp();
        $code = $request->input("code");
        $state = $request->input("state");

        $format = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code';
        $appId = \WxPayConfig::APPID;
        $appSecret = \WxPayConfig::APPSECRET;
        $url = sprintf($format, $appId, $appSecret, $code);

        $client = new Client();
        $res = $client->get($url);

        if($res->getStatusCode() == 200){
            $json_str = $res->getBody();
            $json = json_decode($json_str);
            if(isset($json->openid)){
                $open_id = $json->openid;
                $payment = [
                    'title' => "test",
                    'price' => 0.01,
                    'order_id' => "oid",
                    'payment_id' => "pid-js",
                    'description' => 'test',
                    'open_id' => $open_id
                ];


                $config = self::weChatJsConfig($payment);

                return view('mobile.index.payment',['config' => $config]);

            }
            else{
                abort(404);
            }

        }
    }

    public function getWechatWebPay(Request $request){
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




    }
    public function getWechatWapPay(Request $request){
        $debug = true;
        if($debug){
            $payment = [
                'title' => "test",
                'price' => 0.01,
                'order_id' => "oid",
                'payment_id' => "pid-wap",
                'description' => 'test'
            ];

            $config = self::weChatWapConfig($payment);
            if($config["return_code"] == "SUCCESS"){
                echo $config["mweb_url"];

                $f = '<a href="%s">Pay</a>';
                echo "<br />";
                echo sprintf($f,$config["mweb_url"]);
            }
            else{
                echo $config["return_msg"];
            }
            return;
        }
    }
    public function getWechatJsPay(Request $request){
        return redirect(self::createOpenIdUrl());
    }

    public function weChatWebConfig($payment){
        $price = $payment["price"] * 100;//covert to FEN
        $title = $payment["title"];
        $description = $payment["description"];
        $goodTag = "";
        $notifyUrl = URL::to("/wechat/payment/notify");
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
        $openId = $payment["open_id"];
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

    public function weChatWapConfig($payment){
        $price = $payment["price"] * 100;//covert to FEN
        $title = $payment["title"];
        $description = $payment["description"];
        $goodTag = "";
        $notifyUrl = "";
        $orderId = $payment["order_id"];
        $paymentId = $payment["payment_id"];
        $hour = 60*60;
        $expire = $hour;

        $hType = "Wap";
        $hUrl = "http://zyoutrip.com";
        $hName = "zhotel";

        $sceneInfo = [
            'h5_info' => [
                'type' => $hType,
                'wap_url' => $hType,
                'wap_name' => $hName,
            ]
        ];

        $input = new \WxPayUnifiedOrder();
        $input->SetBody($title);
        $input->SetAttach($orderId."_".$paymentId);//附件数据
        $input->SetOut_trade_no(\WxPayConfig::MCHID.date("YmdHis"));
        $input->SetTotal_fee($price);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + $expire));
        $input->SetGoods_tag($goodTag);
        $input->SetNotify_url($notifyUrl);
        $input->SetTrade_type("MWEB");
        $input->SetProduct_id($orderId);
        //$input->SetSpbill_create_ip($ip); //set by sdk inner
        $input->SetScene_info(json_encode($sceneInfo));


        $order = \WxPayApi::unifiedOrder($input);
        /**
         *
            <xml>
            <return_code><![CDATA[SUCCESS]]></return_code>
            <return_msg><![CDATA[OK]]></return_msg>
            <appid><![CDATA[wx2421b1c4370ec43b]]></appid>
            <mch_id><![CDATA[10000100]]></mch_id>
            <nonce_str><![CDATA[IITRi8Iabbblz1Jc]]></nonce_str>
            <sign><![CDATA[7921E432F65EB8ED0CE9755F0E86D72F]]></sign>
            <result_code><![CDATA[SUCCESS]]></result_code>
            <prepay_id><![CDATA[wx201411101639507cbf6ffd8b0779950874]]></prepay_id>
            <trade_type><![CDATA[MWEB]]></trade_type>
            <mweb_url><![CDATA[https://wx.tenpay.com/cgi-bin/mmpayweb-bin/checkmweb?prepay_id=wx2016121516420242444321ca0631331346&package=1405458241]]></mweb_url>
            </xml>
         */

        //if order["return_code"] == "SUCCESS": <a href=order["mweb_url"]>Pay</a>
        //将$order["mweb_url"]可以拉起微信支付,有效期5分钟

        return $order;
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
                $ids = explode("_", $attach);
                if(count($ids) == 2){
                    $orderId = $ids[0];
                    $paymentId = $ids[1];
                    //queue to execute
                }
            }
        }
        return '<xml>
                <return_code><![CDATA[SUCCESS]]></return_code>
                <return_msg><![CDATA[OK]]></return_msg>
                </xml>';
    }

    //=================== END =====================

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
