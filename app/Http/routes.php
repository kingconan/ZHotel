<?php
use Illuminate\Support\Facades\Route;
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
        return view('frontend.index.welcome');
    });
    Route::get('/hotel/detail/{id}', function () {
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

//Route::group(['middleware' => 'b_auth'],function() {
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
    Route::get('/zhotel/ss/register', function () {
    return view('backend.index.register');
    });

    Route::get('zashboard/hotels', function () {
        return view('backend.index.hotel_list');
    });
    Route::get('zashboard/orders', function () {
        return view('backend.index.order_list');
    });

    Route::get('zashboard/order', function () {
        return view('backend.index.order');
    });
//});



Route::group(['middleware'=>'api'],function(){
    Route::post('/api/test', 'Backend\IndexController@getHotelList');
    Route::post('/api/hotel/{id}', 'Backend\IndexController@getHotel');
    Route::post('/api/update/hotel', 'Backend\IndexController@updateHotel');
    Route::post('/api/delete/hotel', 'Backend\IndexController@deleteHotel');
    Route::post('/api/online/hotel', 'Backend\IndexController@onlineHotel');
    Route::post('/api/create/hotel', 'Backend\IndexController@createHotel');
    Route::post('/api/search/hotel', 'Backend\IndexController@searchHotel');
    Route::post('/api/magic/link', 'Backend\IndexController@magicLink');
    Route::post('/api/update/contract', 'Backend\IndexController@updateContract');
    Route::post('/api/parse/hotel', 'Backend\IndexController@parseHotel');
    Route::post('/api/price/hotel', 'Backend\IndexController@checkPrice');
    Route::get('/api/parse/get_rate', 'Backend\IndexController@getRate');


    Route::post('/api/order/create', 'Backend\OrderController@createOrder');

    Route::post('/uploader/image', 'Backend\IndexController@uploadImage');
    Route::post('/fetcher/image', 'Backend\IndexController@fetchImage');
    Route::get('/qiniu/token', 'Backend\IndexController@qToken');
    Route::get('/api/place', 'Backend\IndexController@getPlaceDetailOfGooglemap');

    Route::post('/api/order/{id}', 'Backend\OrderController@getOrder');
    Route::post('/api/order_list', 'Backend\IndexController@getOrderList');
    Route::post('/api/update/order', 'Backend\OrderController@updateOrder');
    Route::post('/api/search/order', 'Backend\IndexController@searchOrder');
    Route::post('/api/test/order', 'Backend\OrderController@test');
});


Route::post('/chrome/test', 'Backend\IndexController@chromeTest');
Route::get('/uuu', 'Backend\IndexController@createMaster');
