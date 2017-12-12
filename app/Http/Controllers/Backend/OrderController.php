<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    /**
     * Order Status
     * 0 : un-pay
     * 1 : pay-part, 2 : pay-all
     * 3 : reserved, 4 : apply-to-cancel
     * 5 : finished, 6 : canceled
     */


    /**
     * @param Request $request
     */
    public function createTicket(Request $request){

    }

    public function callbackForPayment(Request $request){

    }

    public function createPayment(Request $request){

    }


    //order ops
    public function createOrder(Request $request){

    }

    public function readOrder(Request $request){

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
