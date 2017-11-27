<?php

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

Route::get('/', function () {
    return view('frontend.index.welcome');
});
Route::get('/create_hotel', function () {
    return view('frontend.index.hotel_create');
});
Route::get('/edit_hotel', function () {
    return view('frontend.index.hotel_create');
});
Route::get('/update_hotel', function () {
    return view('frontend.index.hotel_create');
});
Route::get('/hotel_list', function () {
    return view('frontend.index.hotel_list');
});
Route::get('/plan', function () {
    return view('frontend.index.hotel_plan');
});

Route::post('/api/test', 'Backend\IndexController@test');
Route::post('/api/hotel/{id}', 'Backend\IndexController@getHotel');
Route::post('/api/update/hotel', 'Backend\IndexController@updateHotel');
Route::post('/api/delete/hotel', 'Backend\IndexController@deleteHotel');
Route::post('/api/create/hotel', 'Backend\IndexController@createHotel');
Route::post('/api/search/hotel', 'Backend\IndexController@searchHotel');
Route::post('/api/magic/link', 'Backend\IndexController@magicLink');
Route::post('/api/update/contract', 'Backend\IndexController@updateContract');
Route::post('/api/parse/hotel', 'Backend\IndexController@parseHotel');
Route::get('/api/parse/get_rate', 'Backend\IndexController@getRate');


Route::post('/uploader/image', 'Backend\IndexController@uploadImage');
Route::get('/api/place', 'Backend\IndexController@getPlaceDetailOfGooglemap');
Route::get('/hotel/detail1/{id}', 'Backend\IndexController@index');
Route::get('/hotel/detail/{id}', function () {
    return view('frontend.index.hotel');
});