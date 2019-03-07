<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//User Routes
Route::group(['prefix' => '/user'], function(){
    Route::post('/login', 'Api\User\AuthController@login');
    Route::post('/splash', 'Api\User\AuthController@splash');
    Route::post('/code_send', 'Api\User\AuthController@code_send');
    Route::post('/code_check', 'Api\User\AuthController@code_check');
    Route::post('/password_change', 'Api\User\AuthController@password_change');
    Route::post('/location/set', 'Api\User\AuthController@set_location');

    Route::post('/profile', 'Api\User\AuthController@profile');
    Route::post('/profile/update', 'Api\User\AuthController@profile_update');

    Route::post('/home', 'Api\User\HomeController@index');
    Route::post('/home/sub_cats', 'Api\User\HomeController@sub_cats');
    Route::get('/about_us/{lang}', 'Api\User\HomeController@about_us');
    Route::get('/complain_titles/{lang}', 'Api\User\HomeController@complain_view');
    Route::post('/complain', 'Api\User\HomeController@complain');
    Route::post('/notifications', 'Api\User\HomeController@notifications');
    Route::post('/notification/seen', 'Api\User\HomeController@seen');

    Route::post('/get_techs', 'Api\User\OrderController@get_techs');
    Route::post('/search_techs', 'Api\User\OrderController@search_techs');
    Route::post('/view_tech', 'Api\User\OrderController@view_tech');

    Route::post('/order', 'Api\User\OrderController@order');
    Route::post('/orders', 'Api\User\OrderController@orders');
    Route::post('/order/details', 'Api\User\OrderController@details');
    Route::post('/order/item_change_status', 'Api\User\OrderController@item_change_status');
    Route::post('/order/view_tech_to_rate', 'Api\User\OrderController@view_tech_to_rate');
    Route::post('/order/rate', 'Api\User\OrderController@rate');
    Route::post('/order/re_schedule', 'Api\User\OrderController@re_schedule');
    Route::post('/order/cancel', 'Api\User\OrderController@cancel');

    Route::post('/order/items/submit', 'Api\User\OrderController@items_submit');
});
//End User Routes


//Tech Routes
Route::group(['prefix' => '/tech'], function(){
    Route::post('/login', 'Api\Tech\AuthController@login');
    Route::post('/splash', 'Api\Tech\AuthController@splash');
    Route::post('/code_send', 'Api\Tech\AuthController@code_send');
    Route::post('/code_check', 'Api\Tech\AuthController@code_check');
    Route::post('/password_change', 'Api\Tech\AuthController@password_change');
    Route::post('/location/set', 'Api\Tech\AuthController@set_location');
    Route::post('/status/switch', 'Api\Tech\AuthController@status_switch');


    Route::post('/profile', 'Api\Tech\AuthController@profile');
    Route::post('/profile/update', 'Api\Tech\AuthController@profile_update');

    Route::post('/home', 'Api\Tech\HomeController@index');
    Route::post('/orders', 'Api\Tech\OrderController@orders');
    Route::get('/about_us/{lang}', 'Api\Tech\HomeController@about_us');
    Route::post('/notifications', 'Api\Tech\HomeController@notifications');
    Route::post('/notification/seen', 'Api\Tech\HomeController@seen');
    Route::post('/rates', 'Api\Tech\HomeController@rates');

    Route::post('/orders', 'Api\Tech\OrderController@orders');
    Route::post('/order/details', 'Api\Tech\OrderController@details');
    Route::post('/warehouse/cats','Api\Tech\OrderController@warehouse_cats');
    Route::post('/warehouse/items','Api\Tech\OrderController@warehouse_items');
    Route::post('/warehouse/item/show','Api\Tech\OrderController@warehouse_show_item');
    Route::post('/warehouse/item/add','Api\Tech\OrderController@warehouse_add_item');
    Route::post('/warehouse/item/request','Api\Tech\OrderController@warehouse_request_item');
    Route::post('/warehouse/search','Api\Tech\OrderController@warehouse_search');
    Route::post('/order/get_third_levels','Api\Tech\OrderController@get_third_levels');
    Route::post('/order/change_status','Api\Tech\OrderController@change_status');
    Route::post('/order/cancel','Api\Tech\OrderController@cancel');


});
//End tech Routes

Route::post('/add_provider', 'Api\ProviderController@store');
