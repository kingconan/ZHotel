<?php

namespace App\Http\Controllers\Backend;

use App\Models\Order;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

require_once ('alipay-sdk/aop/AopClient.php');
require_once ('alipay-sdk/aop/request/AlipayTradeAppPayRequest.php');
require_once ('alipay-sdk/aop/request/AlipayTradePagePayRequest.php');
require_once ('alipay-sdk/aop/request/AlipayTradeWapPayRequest.php');

/**
    https://openhome.alipay.com/platform/appManage.htm 创建应用
 */
class PaymentController extends Controller
{

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
