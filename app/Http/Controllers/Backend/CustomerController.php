<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\ZEvent;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;

class CustomerController extends Controller
{

    /**
     * LOGIN - LOGOUT
     */
    const guard = 'customer';
    public function login(Request $request){
        $email = $request->input("email");
        $password = $request->input("password");
        ZEvent::log(self::getCurrentEr(), "cmd",__METHOD__, [$email,$password]);
        if(Auth::guard(self::guard)->attempt(['email'=>$email,'password'=>$password],true)){
            return redirect()->intended('/');
        }
        else{
            return Redirect::to('/login');
        }
    }
    public function logout(Request $request){
        ZEvent::log(self::getCurrentEr(), "cmd",__METHOD__, "");
        Auth::guard(self::guard)->logout();
        return redirect()->intended('/');
    }
    public function register(Request $request){
        $name = $request->input("name");
        $email = $request->input("email");
        $password = $request->input("password");
        $phone = $request->input("phone");

        ZEvent::log(self::getCurrentEr(), "cmd",__METHOD__, [$name,$email,$password]);
        $dup = Customer::where("phone",$phone)->get();
        if(empty($dup)){
            echo "<h1 style='text-align: center;margin-top: 100px'>User Already Exits</h1>";
        }
        else{
            /**
             * Role
             *
             */
            $master = new Customer();
            $master->name = $name;
            $master->phone = $phone;
            $master->email = $email;
            $master->password = bcrypt($password);
            $master->save();
            return Redirect::to('/login');
        }

    }
    private function getCurrentEr(){
        if(Auth::guard(self::guard)->user()){
            return Auth::guard(self::guard)->user()->name;
        }
        return "null";
    }
}
