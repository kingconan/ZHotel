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

    }

    public function updateOrderBookInfo(Request $request){

    }

    public function deleteOrder(Request $request){

    }

    public function updateOrderStatus(Request $request){

    }




}
