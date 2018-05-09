<?php
use Illuminate\Support\Facades\Route;
use Jenssegers\Agent\Agent;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::group(['middleware' => 'web'],function() {
    //frontend
    Route::get('/', function () {
        return view('frontend.index.index');
    });

    Route::get('/list/hotels/{id}', function () {
        return view('frontend.index.hotel_list');
    });
    Route::get('/list/brand/{id}', function () {
        return view('frontend.index.hotel_brand');
    });

    Route::get('/user/profile', function () {
        return view('frontend.index.order_list');
    });
    Route::get('/user/order/{id}', function () {
        return view('frontend.index.order_detail');
    });

    Route::get('/test_payment', function () {
        return view('frontend.index.test_payment');
    });

    Route::get('/test', function(){
        echo urlencode("http://zyoutrip.com/test/test");
    });
    Route::get('/hotel/detail/{id}', function () {
        $agent = new Agent();
        if($agent->isMobile()){
            return view('mobile.index.hotel');
        }
        return view('frontend.index.hotel');
    });
    Route::get('/login', function () {
        return view('frontend.index.clogin');
    });
    Route::get('/register', function () {
        return view('frontend.index.cregister');
    });

    //apis
    Route::post('/customer/login', 'Backend\CustomerController@login');
    Route::post('/customer/register', 'Backend\CustomerController@register');
    Route::post('/customer/alogin', 'Backend\CustomerController@ajaxLogin');
    Route::any('/order/booking/step', 'Backend\OrderController@orderRedirect');
    Route::get('/logout', 'Backend\CustomerController@logout');

    //backend
    Route::get('/zhotel/ss/login', function () {
        return view('backend.index.login');
    });


    //apis
    Route::post('/ss/login', 'Backend\IndexController@login');
    Route::post('/ss/register', 'Backend\IndexController@register');
    Route::post('/ss/logout', 'Backend\IndexController@logout');
});

Route::group(['middleware' => 'b_auth'],function() {
    Route::get('/create_hotel', function () {
        return view('backend.index.hotel_create');
    });
    Route::get('/edit_hotel', function () {
        return view('backend.index.hotel_create');
    });
    Route::get('/update_hotel', function () {
        return view('backend.index.hotel_create');
    });
    Route::get('/hotel_list', function () {
        return view('backend.index.hotel_list');
    });
    Route::get('/plan', function () {
        return view('backend.index.hotel_plan');
    });


    Route::group(['prefix' => 'zashboard'], function(){
        Route::get('hotels', function () {
            return view('backend.index.hotel_list');
        });
        Route::get('orders', function () {
            return view('backend.index.order_list');
        });
        Route::get('order', function () {
            return view('backend.index.order');
        });
    });

});

Route::get('/zhotel/ss/register', function () {
    return view('backend.index.register');
});

Route::group(['middleware'=>'api'],function(){
    Route::post('/api/hotels', 'Backend\IndexController@getHotelList');
    Route::post('/api/hotel/{id}', 'Backend\IndexController@getHotel');
    Route::post('/api/update/hotel', 'Backend\IndexController@updateHotel');
    Route::post('/api/delete/hotel', 'Backend\IndexController@deleteHotel');
    Route::post('/api/online/hotel', 'Backend\IndexController@onlineHotel');
    Route::post('/api/memo/hotel', 'Backend\IndexController@memoHotel');
    Route::post('/api/create/hotel', 'Backend\IndexController@createHotel');
    Route::post('/api/search/hotel', 'Backend\IndexController@searchHotel');
    Route::post('/api/magic/link', 'Backend\IndexController@magicLink');
    Route::post('/api/update/contract', 'Backend\IndexController@updateContract');
    Route::post('/api/parse/hotel', 'Backend\IndexController@parseHotel');
    Route::post('/api/price/hotel', 'Backend\IndexController@checkPrice');
    Route::get('/api/parse/get_rate', 'Backend\IndexController@getRate');


    Route::get('/api/index/op', 'Backend\IndexController@getIndexPage');
    Route::get('/api/list/hotels', 'Backend\IndexController@getHotelListByOp');
    Route::get('/api/list/brand', 'Backend\IndexController@getHotelBrandByOp');
    Route::post('/api/list/search', 'Backend\IndexController@searchHotelByOp');


    Route::post('/api/order/create', 'Backend\OrderController@createOrder');
    Route::post('/api/order/get_user_orders', 'Backend\OrderController@getUserOrderList');

    Route::post('/uploader/image', 'Backend\IndexController@uploadImage');
    Route::post('/fetcher/image', 'Backend\IndexController@fetchImage');
    Route::get('/qiniu/token', 'Backend\IndexController@qToken');
    Route::get('/api/place', 'Backend\IndexController@getPlaceDetailOfGooglemap');

    Route::post('/api/order/detail/{id}', 'Backend\OrderController@getOrder');
    Route::post('/api/order_list', 'Backend\IndexController@getOrderList');
    Route::post('/api/update/order', 'Backend\OrderController@updateOrder');
    Route::post('/api/update/order2', 'Backend\OrderController@updateOrderByUser');
    Route::post('/api/search/order', 'Backend\IndexController@searchOrder');
    Route::post('/api/test/order', 'Backend\OrderController@test');
});


Route::post('/chrome/test', 'Backend\IndexController@chromeTest');
Route::get('/uuu', 'Backend\IndexController@createMaster');
Route::get('/ali/payment/test/{id}', 'Backend\PaymentController@getAliWebPay');
Route::post('/payment/test', 'Backend\PaymentController@testPayment');
Route::get('/payment/user/{oid}', 'Backend\PaymentController@userPayment');
Route::get('/wechat/payment/test/{id}', 'Backend\PaymentController@getWechatWebPay');
Route::get('/wechat/payment/wap', 'Backend\PaymentController@getWechatWapPay');
Route::get('/wechat/payment/js', 'Backend\PaymentController@getWechatJsPay');
Route::any('/wechat/payment/notify', 'Backend\PaymentController@weChatNotify');
Route::any('/wechat/payment/redirect', 'Backend\PaymentController@parseOpenIdRedirect');

Route::get('/test/show/1', function () {
    return view('frontend.index.payment_wechat',array("url"=>"test"));
});

Route::get('/dong_hotel/area/{id}', function () {
    return view('mobile.index.dong_area_hotels');
});
Route::get('/dong_hotel/hotel/{id}', function () {
    return view('mobile.index.dong_area_hotel');
});
Route::get('/dong_hotel/contract', function () {
    return view('mobile.index.dong_contract');
});

Route::get('/travelid/package/{id}', function () {
    return view('mobile.index.mirror_travelid_package');
});

Route::get('/dong/test', 'Backend\DongController@test');
Route::get('/dong/getAreaHotels', 'Backend\DongController@getAreaHotels');
Route::get('/dong/getRatePlanList', 'Backend\DongController@getRatePlanList');
Route::get('/dong/getRatePlanDetail', 'Backend\DongController@getRatePanDetail');
Route::get('/dong/getHotelDetail', 'Backend\DongController@getHotelDetail');
Route::get('/dong/contract/{id}', 'Backend\DongController@getContractInfo');
Route::get('/dong/contract/price/get', 'Backend\DongController@getPricesOfRoom');


Route::get('/travelid/hotel/{id}', 'Backend\HackController@redirectToTravelid');
