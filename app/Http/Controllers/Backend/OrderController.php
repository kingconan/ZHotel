<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use App\Models\ZEvent;
class OrderController extends Controller
{
    /**
     * Order Status
     */


    /**
     * @param Request $request
     */
    const guard = 'customer';
    public function createTicket(Request $request){

    }

    public function callbackForPayment(Request $request){

    }

    public function createPayment(Request $request){

    }


    //order ops
    public function createOrder(Request $request){
        if(Auth::guard(self::guard)->user()){
            $user = Auth::guard(self::guard)->user();
            $json = $request->json()->all();
            $orderStatus = Order::STATUS_INIT;

            $order = new Order();
            $order->status = $orderStatus;
            $order->user_id = $user->_id;
            $order->user_info = [
              "id"=>$user->_id,"name"=>$user->name,
                "email"=>$user->email,"phone"=>$user->phone
            ];
            $order->book_info = $json["book_info"];
            $order->hotel_info = $json["hotel_info"];
            $order->room_info = $json["room_info"];
            $order->plan_info = $json["plan_info"];
            $order->hotel_id = $json["hotel_id"];
            $order->room_id = $json["room_id"];

            //TODO:build search keys here

            $order->hotel_name = $json["hotel_info"]["name"];
            $order->hotel_name_en = $json["hotel_info"]["name_en"];
            $order->user_phone = $user->phone;
            $order->checkin = $json["book_info"]["checkin"];
            $order->checkout = $json["book_info"]["checkout"];

            $order->save();

            $order_id = $order->_id;
//            $order_id = "sss123";

            //TODO:save and return
            return response()->json(
                [
                    "ok"=>0,
                    "msg"=>"ok",
                    "obj"=>$order_id
                ]
            );
//            return view('frontend.index.order_create',["order_id"=>$order_id]);
        }
        return response()->json(
            [
                "ok"=>4,
                "msg"=>"请先登录",
                "obj"=>[]
            ]
        );
    }
    public function orderRedirect(Request $request){
        $sid = $request->input("sid");
        return view('frontend.index.order_create',["order_id"=>$sid]);
    }

    public function getOrder($id,Request $request){
        $order = Order::find($id);
        if($order){
            return response()->json(
                [
                    "ok"=>0,
                    "msg"=>"ok",
                    "obj"=>$order
                ]
            );
        }
        return response()->json(
            [
                "ok"=>4,
                "msg"=>"not found",
                "obj"=>[]
            ]
        );

    }

    public function updateOrder(Request $request){
        $json = $request->json()->all();
        $_id = $json["_id"];
        $order = Order::find($_id);
        if($order){
            $order->user_info = $json["user_info"];
            $order->book_info = $json["book_info"];
            $order->checkin = $json["book_info"]["checkin"];
            $order->checkout = $json["book_info"]["checkout"];

            $order->user_phone = $json["user_info"]["phone"];


            $order->payment_id = $json["payment_id"];
            $order->payment_price = $json["payment_price"];
            $order->payment_memo = $json["payment_memo"];
            if(isset($json["payment_log"]))
                $order->payment_log = $json["payment_log"];

            $order->save();
            return response()->json(
                [
                    "ok"=>0,
                    "msg"=>"ok",
                    "obj"=>$order
                ]
            );
        }
        else{
            return response()->json(
                [
                    "ok"=>4,
                    "msg"=>"木有找到",
                    "obj"=>$json
                ]
            );
        }
    }

    public function updateOrderBookInfo(Request $request){

    }

    public function deleteOrder(Request $request){

    }

    public function updateOrderStatus(Request $request){

    }




}
