<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsProvider;
use App\Http\Middleware\IsCompany;

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => '/admin'], function(){

    Route::get('/login', 'Admin\AuthController@login_view');
    Route::post('/login', 'Admin\AuthController@login');
    Route::get('/logout', 'Admin\AuthController@logout');

    Route::group(['middleware' => ['web', IsAdmin::class]], function () {

        Route::get('/dashboard', 'Admin\HomeController@dashboard');
        Route::get('/profile', 'Admin\HomeController@profile');
        Route::post('/profile/update', 'Admin\HomeController@update_profile');
        Route::post('/change_password', 'Admin\HomeController@change_password');



        Route::group(['middleware' => ['permission:admins_operate']], function ()
        {
            Route::get('/admins/{type}/index', 'Admin\AdminController@index');
            Route::get('/admins/search', 'Admin\AdminController@search');
            Route::get('/admin/create', 'Admin\AdminController@create');
            Route::post('/admin/store', 'Admin\AdminController@store');
            Route::get('/admin/{id}/view', 'Admin\AdminController@show');
            Route::get('/admin/{id}/edit', 'Admin\AdminController@edit');
            Route::post('/admin/update', 'Admin\AdminController@update');
            Route::post('/admin/delete', 'Admin\AdminController@destroy');
            Route::post('/admin/change_status', 'Admin\AdminController@change_status');
        });



        Route::group(['middleware' => ['permission:addresses_observe']], function ()
        {
            Route::get('/addresses/search', 'Admin\AddressController@search');
            Route::get('/addresses/{parent}', 'Admin\AddressController@index');
        });

        Route::group(['middleware' => ['permission:addresses_operate']], function ()
        {
            Route::get('/address/country/create', 'Admin\AddressController@country_create');
            Route::get('/address/city/create', 'Admin\AddressController@city_create');
            Route::post('/address/store', 'Admin\AddressController@store');
            Route::get('/address/{id}/edit', 'Admin\AddressController@edit');
            Route::post('/address/update', 'Admin\AddressController@update');
            Route::post('/address/delete', 'Admin\AddressController@destroy');
        });



        Route::group(['middleware' => ['permission:categories_observe']], function ()
        {
            Route::get('/categories/search', 'Admin\CategoryController@search');
            Route::get('/categories/{parent}', 'Admin\CategoryController@index');
        });

        Route::group(['middleware' => ['permission:categories_operate']], function ()
        {
            Route::get('/categories/excel/export', 'Admin\CategoryController@excel_export');
            Route::get('/category/main/create', 'Admin\CategoryController@main_create');
            Route::get('/category/sub/create', 'Admin\CategoryController@sub_create');
            Route::get('/category/secondary/create', 'Admin\CategoryController@sec_create');
            Route::post('/category/main_store', 'Admin\CategoryController@main_store');
            Route::post('/category/sub_store', 'Admin\CategoryController@sub_store');
            Route::post('/category/sec_store', 'Admin\CategoryController@sec_store');
            Route::get('/category/{id}/main_edit', 'Admin\CategoryController@main_edit');
            Route::get('/category/{id}/sub_edit', 'Admin\CategoryController@sub_edit');
            Route::get('/category/{id}/sec_edit', 'Admin\CategoryController@sec_edit');
            Route::post('/category/main_update', 'Admin\CategoryController@main_update');
            Route::post('/category/sub_update', 'Admin\CategoryController@sub_update');
            Route::post('/category/sec_update', 'Admin\CategoryController@sec_update');
            Route::post('/category/delete', 'Admin\CategoryController@destroy');
        });



        Route::group(['middleware' => ['permission:providers_observe']], function ()
        {
            Route::get('/providers/search', 'Admin\ProviderController@search');
            Route::get('/providers/{state}', 'Admin\ProviderController@index');
            Route::get('/provider/{id}/view', 'Admin\ProviderController@show');
        });

        Route::group(['middleware' => ['permission:providers_observe_statistics']], function ()
        {
            Route::get('/provider/{id}/statistics', 'Admin\ProviderController@statistics');
        });

        Route::group(['middleware' => ['permission:providers_subscriptions']], function ()
        {
            Route::post('/provider/subscriptions', 'Admin\ProviderController@set_subscriptions');
        });

        Route::group(['middleware' => ['permission:providers_operate']], function ()
        {
            Route::get('/provider/create', 'Admin\ProviderController@create');
            Route::post('/provider/store', 'Admin\ProviderController@store');
            Route::get('/provider/{id}/edit', 'Admin\ProviderController@edit');
            Route::post('/provider/update', 'Admin\ProviderController@update');
            Route::post('/provider/change_state', 'Admin\ProviderController@change_state');
            Route::post('/provider/delete', 'Admin\ProviderController@destroy');
            Route::post('/provider/change_password', 'Admin\ProviderController@change_password');
            Route::get('/provider/{id}/subscriptions', 'Admin\ProviderController@get_subscriptions');
        });



        Route::group(['middleware' => ['permission:companies_observe']], function ()
        {
            Route::get('/companies/search', 'Admin\CompanyController@search');
            Route::get('/companies/{state}', 'Admin\CompanyController@index');
            Route::get('/company/{id}/view', 'Admin\CompanyController@show');
        });

        Route::group(['middleware' => ['permission:companies_observe_statistics']], function ()
        {
            Route::get('/company/{id}/statistics', 'Admin\CompanyController@statistics');
        });

        Route::group(['middleware' => ['permission:companies_subscriptions']], function ()
        {
            Route::get('/company/{id}/subscriptions', 'Admin\CompanyController@get_subscriptions');
            Route::post('/company/subscriptions', 'Admin\CompanyController@set_subscriptions');
        });

        Route::group(['middleware' => ['permission:companies_operate']], function ()
        {
            Route::get('/company/create', 'Admin\CompanyController@create');
            Route::post('/company/store', 'Admin\CompanyController@store');
            Route::get('/company/{id}/edit', 'Admin\CompanyController@edit');
            Route::post('/company/update', 'Admin\CompanyController@update');
            Route::post('/company/delete', 'Admin\CompanyController@destroy');
            Route::post('/company/change_state', 'Admin\CompanyController@change_state');
        });



        Route::group(['middleware' => ['permission:collaborations_observe']], function ()
        {
            Route::get('/collaborations', 'Admin\CollaborationController@index');
            Route::get('/collaborations/search', 'Admin\CollaborationController@search');
        });

        Route::group(['middleware' => ['permission:collaborations_operate']], function ()
        {
            Route::get('/collaboration/create', 'Admin\CollaborationController@create');
            Route::post('/collaboration/store', 'Admin\CollaborationController@store');
            Route::get('/collaboration/{provider_id}/edit', 'Admin\CollaborationController@edit');
            Route::post('/collaboration/update', 'Admin\CollaborationController@update');
            Route::post('/collaboration/delete', 'Admin\CollaborationController@destroy');
        });


//        Route::get('/individuals/{state}', 'Admin\IndividualController@index');
//        Route::get('/individual/create', 'Admin\IndividualController@create');
//        Route::post('/individual/store', 'Admin\IndividualController@store');
//        Route::get('/individual/{id}/view', 'Admin\IndividualController@show');
//        Route::get('/individual/{id}/edit', 'Admin\IndividualController@edit');
//        Route::post('/individual/update', 'Admin\IndividualController@update');
//        Route::post('/individual/change_status', 'Admin\IndividualController@change_status');
//        Route::post('/individual/change_password', 'Admin\IndividualController@change_password');
//        Route::post('/individual/delete', 'Admin\IndividualController@destroy');

//        Route::get('/users/{state}', 'Admin\UserController@index');
//        Route::get('/user/create', 'Admin\UserController@create');

        Route::group(['prefix' => '/settings'], function ()
        {
            Route::group(['middleware' => ['permission:settings_observe']], function ()
            {
                Route::get('/about', 'Admin\AboutController@index');
            });

            Route::group(['middleware' => ['permission:settings_operate']], function ()
            {
                Route::get('/about/edit', 'Admin\AboutController@edit');
                Route::post('/about/update', 'Admin\AboutController@update');
            });

//            Route::get('/notifications', 'Admin\NotifyController@index');
//            Route::post('/notification/store', 'Admin\NotifyController@store');
//            Route::get('/notification/delete/{id}', 'Admin\NotifyController@destroy');
        });

        //Ajax Routes
        Route::get('/get_cities/{parent}', 'Admin\HomeController@get_cities');
        Route::get('/get_sub_cats/{parent}', 'Admin\HomeController@get_sub_cats');
        //End Ajax Routes

        //Mail Routes
        Route::post('/mail/send', 'Admin\MailController@send');
        //End Mail Routes
    });
});


Route::group(['prefix' => '/provider'], function(){

    Route::get('/login', 'Provider\AuthController@login_view');
    Route::post('/login', 'Provider\AuthController@login');

    Route::group(['middleware' => ['web', IsProvider::class]], function () {

        Route::get('/dashboard', 'Provider\HomeController@dashboard');
        Route::get('/profile', 'Provider\HomeController@profile');
        Route::post('/profile/update', 'Provider\HomeController@update_profile');
        Route::post('/change_password', 'Provider\HomeController@change_password');
        Route::get('/logout', 'Provider\AuthController@logout');

        Route::group(['middleware' => ['permission:providers_observe']], function ()
        {
            Route::get('/my_provider', 'Provider\HomeController@my_provider');
        });

        Route::group(['middleware' => ['permission:providers_operate']], function ()
        {
            Route::get('/info', 'Provider\HomeController@info');
            Route::post('/info/update', 'Provider\HomeController@update_info');
        });

        Route::group(['middleware' => ['permission:admins']], function ()
        {
            Route::get('/admins/{type}/index', 'Provider\AdminController@index');
            Route::get('/admins/search', 'Provider\AdminController@search');
            Route::get('/admin/create', 'Provider\AdminController@create');
            Route::post('/admin/store', 'Provider\AdminController@store');
            Route::get('/admin/{id}/view', 'Provider\AdminController@show');
            Route::get('/admin/{id}/edit', 'Provider\AdminController@edit');
            Route::post('/admin/update', 'Provider\AdminController@update');
            Route::post('/admin/delete', 'Provider\AdminController@destroy');
            Route::post('/admin/change_status', 'Provider\AdminController@change_status');
        });

        Route::group(['middleware' => ['permission:collaborations_observe']], function ()
        {
            Route::get('/collaborations', 'Provider\CollaborationController@index');
            Route::get('/collaboration/{id}/statistics', 'Provider\CollaborationController@statistics');
        });

        Route::group(['middleware' => ['permission:techs_file_upload']], function () {
            Route::get('/technician/excel/view', 'Provider\TechnicianController@excel_view');
            Route::post('/technician/excel/upload', 'Provider\TechnicianController@excel_upload');
            Route::get('/technician/images/view', 'Provider\TechnicianController@images_view');
            Route::post('/technician/images/upload', 'Provider\TechnicianController@images_upload');
        });

        Route::group(['middleware' => ['permission:techs_observe']], function ()
        {
            Route::get('/technicians/statistics', 'Provider\TechnicianController@statistics');
            Route::get('/technicians/search', 'Provider\TechnicianController@search');
            Route::get('/technicians/{state}', 'Provider\TechnicianController@index');
            Route::get('/technician/{id}/view', 'Provider\TechnicianController@show');

        });

        Route::group(['middleware' => ['permission:techs_operate']], function ()
        {
            Route::get('/technician/{id}/orders/request', 'Provider\TechnicianController@orders_request');
            Route::post('/technician/orders/invoice/show', 'Provider\TechnicianController@orders_show');
            Route::post('/technician/orders/invoice/export', 'Provider\TechnicianController@orders_export');
            Route::get('/technician/create', 'Provider\TechnicianController@create');
            Route::post('/technician/store', 'Provider\TechnicianController@store');
            Route::get('/technician/{id}/edit', 'Provider\TechnicianController@edit');
            Route::post('/technician/update', 'Provider\TechnicianController@update');
            Route::post('/technician/change_state', 'Provider\TechnicianController@change_state');
            Route::post('/technician/change_password', 'Provider\TechnicianController@change_password');
//        Route::post('/technician/delete', 'Provider\TechnicianController@destroy');
        });


        Route::group(['middleware' => ['permission:rotations_observe']], function ()
        {
            Route::get('/rotations/index', 'Provider\RotationController@index');
        });

        Route::group(['middleware' => ['permission:rotations_observe']], function ()
        {
            Route::get('/rotation/create', 'Provider\RotationController@create');
            Route::post('/rotation/store', 'Provider\RotationController@store');
            Route::get('/rotation/{id}/edit', 'Provider\RotationController@edit');
            Route::post('/rotation/update', 'Provider\RotationController@update');
            Route::post('/rotation/delete', 'Provider\RotationController@destroy');
        });


        Route::group(['middleware' => ['permission:warehouse_file_upload']], function ()
        {
            Route::get('/warehouse/excel/view', 'Provider\WarehouseController@excel_view');
            Route::post('/warehouse/excel/upload', 'Provider\WarehouseController@excel_upload');
            Route::get('/warehouse/images/view', 'Provider\WarehouseController@images_view');
            Route::post('/warehouse/images/upload', 'Provider\WarehouseController@images_upload');
        });

        Route::group(['middleware' => ['permission:warehouse_observe']], function ()
        {
            Route::get('/warehouse/search', 'Provider\WarehouseController@search');
            Route::get('/warehouse/{parent}', 'Provider\WarehouseController@index');
            Route::get('/warehouse/{parent}/items', 'Provider\WarehouseController@items');
        });

        Route::group(['middleware' => ['permission:warehouse_operate']], function ()
        {
            Route::get('/warehouse/excel/categories/export', 'Provider\WarehouseController@categories_excel_export');
            Route::get('/warehouse/excel/parts/export', 'Provider\WarehouseController@parts_excel_export');
            Route::get('/warehouse/item/create', 'Provider\WarehouseController@create');
            Route::post('/warehouse/item/store', 'Provider\WarehouseController@store');
            Route::get('/warehouse/item/{id}/edit', 'Provider\WarehouseController@edit');
            Route::post('/warehouse/item/update', 'Provider\WarehouseController@update');
            Route::post('/warehouse/item/change_status', 'Provider\WarehouseController@change_status');
//        Route::post('/warehouse/item/delete', 'Provider\WarehouseController@destroy');
        });



        Route::group(['middleware' => ['permission:warehouse_requests_observe']], function ()
        {
            Route::get('/warehouse_requests', 'Provider\WarehouseRequestController@index');
        });

        Route::group(['middleware' => ['permission:warehouse_requests_operate']], function ()
        {
            //        Route::post('/warehouse_request/delete', 'Provider\WarehouseRequestController@destroy');
        });

        Route::group(['middleware' => ['permission:orders_observe']], function ()
        {
            Route::get('/orders/{type}/invoice/request', 'Provider\OrderController@orders_request');
            Route::post('/orders/invoice/show', 'Provider\OrderController@orders_show');
            Route::post('/orders/invoice/export', 'Provider\OrderController@orders_export');
            Route::get('/orders/search', 'Provider\OrderController@search');
            Route::get('/orders/{type}', 'Provider\OrderController@index');
            Route::get('/order/{id}/view', 'Provider\OrderController@show');
        });


        Route::group(['middleware' => ['permission:services_fees']], function ()
        {
            Route::get('/services/fees/view', 'Provider\ServiceFeeController@view');
            Route::post('/services/fees/update', 'Provider\ServiceFeeController@update');
        });


        //Ajax Routes
        Route::get('/get_cities/{parent}', 'Provider\HomeController@get_cities');
        Route::get('/get_sub_cats/{parent}', 'Provider\HomeController@get_sub_cats');
        //End Ajax Routes
    });
});


Route::group(['prefix' => '/company'], function(){
    Route::get('/login', 'Company\AuthController@login_view');
    Route::post('/login', 'Company\AuthController@login');

    Route::group(['middleware' => ['web', IsCompany::class]], function ()
    {
        Route::get('/dashboard', 'Company\HomeController@dashboard');
//        Route::get('/test', 'Company\HomeController@test');
        Route::get('/profile', 'Company\HomeController@profile');
        Route::post('/profile/update', 'Company\HomeController@update_profile');
        Route::post('/change_password', 'Company\HomeController@change_password');

        Route::get('/logout', 'Company\AuthController@logout');

        Route::group(['middleware' => ['permission:companies_observe']], function ()
        {
            Route::get('/my_company', 'Company\HomeController@my_company');
        });

        Route::group(['middleware' => ['permission:companies_operate']], function ()
        {
            Route::get('/info', 'Company\HomeController@info');
            Route::post('/info/update', 'Company\HomeController@update_info');
        });


        Route::group(['middleware' => ['permission:admins']], function ()
        {
            Route::get('/admins/{type}/index', 'Company\AdminController@index');
            Route::get('/admins/search', 'Company\AdminController@search');
            Route::get('/admin/{id}/view', 'Company\AdminController@show');
            Route::get('/admin/create', 'Company\AdminController@create');
            Route::post('/admin/store', 'Company\AdminController@store');
            Route::get('/admin/{id}/edit', 'Company\AdminController@edit');
            Route::post('/admin/update', 'Company\AdminController@update');
            Route::post('/admin/delete', 'Company\AdminController@destroy');
            Route::post('/admin/change_status', 'Company\AdminController@change_status');
        });

        Route::group(['middleware' => ['permission:sub_companies_observe']], function () {
            Route::get('/sub_company/search', 'Company\SubCompanyController@search');
            Route::get('/sub_companies/{state}', 'Company\SubCompanyController@index');
            Route::get('/sub_company/{id}/users', 'Company\SubCompanyController@users');
//        Route::post('/sub_company/delete', 'Company\SubCompanyController@destroy');
        });

        Route::group(['middleware' => ['permission:sub_companies_operate']], function ()
        {
            Route::get('/sub_company/create', 'Company\SubCompanyController@create');
            Route::post('/sub_company/store', 'Company\SubCompanyController@store');
            Route::get('/sub_company/{id}/edit', 'Company\SubCompanyController@edit');
            Route::post('/sub_company/update', 'Company\SubCompanyController@update');
            Route::post('/sub_company/status/change', 'Company\SubCompanyController@change_status');
        });


        Route::group(['middleware' => ['permission:collaborations_observe']], function ()
        {
            Route::get('/collaborations', 'Company\CollaborationController@index');
        });

        Route::group(['middleware' => ['permission:collaborations_operate']], function ()
        {
            Route::get('/collaboration/{collaboration_id}/statistics', 'Company\CollaborationController@statistics');
            Route::get('/collaboration/{provider_id}/orders/request', 'Company\CollaborationController@orders_request');
            Route::post('/collaboration/orders/invoice/show', 'Company\CollaborationController@orders_show');
            Route::post('/collaboration/orders/invoice/export', 'Company\CollaborationController@orders_export');
            Route::get('/collaboration/{provider_id}/fees/show', 'Company\CollaborationController@fees_show');
            Route::get('/collaboration/{provider_id}/fees/export', 'Company\CollaborationController@fees_export');
        });

        Route::group(['middleware' => ['permission:file_upload']], function ()
        {
            Route::get('/user/excel/view', 'Company\UserController@excel_view');
            Route::post('/user/excel/upload', 'Company\UserController@excel_upload');
            Route::get('/user/images/view', 'Company\UserController@images_view');
            Route::post('/user/images/upload', 'Company\UserController@images_upload');
        });

        Route::group(['middleware' => ['permission:users_observe']], function () {

            Route::get('/users/search', 'Company\UserController@search');
            Route::get('/users/{state}', 'Company\UserController@index');
            Route::get('/user/{id}/view', 'Company\UserController@show');

        });




        Route::group(['middleware' => ['permission:users_operate']], function ()
        {
            Route::get('/user/{id}/orders/request', 'Company\UserController@orders_request');
            Route::post('/user/orders/invoice/show', 'Company\UserController@orders_show');
            Route::post('/user/orders/invoice/export', 'Company\UserController@orders_export');
            Route::get('/user/create', 'Company\UserController@create');
            Route::post('/user/store', 'Company\UserController@store');
            Route::post('/user/order/store', 'Company\UserController@order_store');
            Route::get('/user/{id}/edit', 'Company\UserController@edit');
            Route::post('/user/update', 'Company\UserController@update');
            Route::post('/user/change_state', 'Company\UserController@change_state');
            Route::post('/user/change_password', 'Company\UserController@change_password');
            //        Route::post('/user/delete', 'Company\UserController@destroy');

        });


        Route::group(['middleware' => ['permission:orders_observe']], function ()
        {
            Route::get('/orders/search', 'Company\OrderController@search');
            Route::get('/orders/{type}', 'Company\OrderController@index');
            Route::get('/order/{id}/view', 'Company\OrderController@show');
        });

        Route::group(['middleware' => ['permission:orders_operate']], function ()
        {
            Route::get('/user/{id}/order/create', 'Company\UserController@order_create');
            Route::get('/orders/{type}/invoice/request', 'Company\OrderController@orders_request');
            Route::post('/orders/invoice/show', 'Company\OrderController@orders_show');
            Route::post('/orders/invoice/export', 'Company\OrderController@orders_export');
        });



        Route::group(['middleware' => ['permission:items_requests_observe']], function ()
        {
            Route::get('/item_requests/search', 'Company\ItemRequestController@search');
            Route::get('/item_requests/{status}', 'Company\ItemRequestController@index');
        });

        Route::group(['middleware' => ['permission:item_requests_operate']], function ()
        {
            Route::post('/item_request/change_status', 'Company\ItemRequestController@change_status');
        });


        //Ajax Routes
        Route::get('/get_cities/{parent}', 'Company\HomeController@get_cities');
        Route::get('/get_sub_cats_company/{parent}', 'Company\HomeController@get_subs');
        //End Ajax Routes
    });
});


//Cron Jobs
    Route::get('/crone/schedule', 'Admin\CroneController@schedule');
    Route::get('/crone/rotate', 'Admin\CroneController@rotate');
//End Crone Jobs

Route::get('/policy', 'Admin\HomeController@policy');
