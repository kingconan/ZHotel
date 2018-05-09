<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class HackController extends Controller
{
    //
    public function redirectToTravelid($id, Request $request){
        return redirect('http://travelid.cn/product/hotel/'.$id);
    }
}
