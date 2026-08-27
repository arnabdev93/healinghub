<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\Admin\GoogleAuthController;
Route::get('/', function () {
    // return view('welcome');
    return redirect()->route('login');
});
Route::group(['middleware' => 'guest'], function () {
    // Route::get('register', 'Auth\RegisterAdminController@showAdminRegisterForm')->name('register');
    // Route::post('register', 'Auth\RegisterAdminController@createAdmin')->name('register.store');
    Route::get('login', 'Auth\LoginAdminController@showAdminLoginForm')->name('login');
    Route::post('login', 'Auth\LoginAdminController@adminLogin')->name('login.store');
});
// Route::group(['middleware' => 'auth'], function () {
//     Route::get('home', 'HomeController@index')->name('home');
//     Route::get('logout', 'Auth\LoginAdminController@logout')->name('logout');
//     Route::resource('banner','Admin\BannerController');
//     Route::post('banner-status-update', 'Admin\BannerController@statusUpdate')->name('banner-status-update');
//     Route::resource('categories','Admin\CategoryController');
//     Route::post('category-status-update', 'Admin\CategoryController@statusUpdate')->name('category-status-update');
//     Route::get('sub-categories','Admin\CategoryController@subCategories')->name('sub-categories');
//     Route::resource('trending-categories','Admin\TrendingCategoryController');
//     Route::post('trending-category-status-update', 'Admin\TrendingCategoryController@statusUpdate')->name('trending-category-status-update');
//     Route::resource('products','Admin\ProductController');
//     Route::delete('product-price/{id}','Admin\ProductController@productPriceDelete')->name('product-price.destroy');
//     Route::delete('product-image/{id}','Admin\ProductController@productImageDelete')->name('product-image.destroy');
//     Route::post('product-status-update', 'Admin\ProductController@statusUpdate')->name('product-status-update');
//     Route::resource('users','Admin\UserController');
//     Route::post('user-status-update', 'Admin\UserController@statusUpdate')->name('user-status-update');
//     Route::get('cart-orders','Admin\OrderController@cartOrders')->name('cart-orders');
//     Route::get('prescription-orders','Admin\OrderController@prescriptionOrders')->name('prescription-orders');
//     Route::get('prescription-orders/details/{id}','Admin\OrderController@prescriptionOrderDetails')->name('prescription-orders.show');
//     Route::post('prescription-order-status-update','Admin\OrderController@prescriptionOrderStatusUpdate')->name('prescription-order-status-update');
//     Route::post('order-price-update/{id}','Admin\OrderController@priceUpdate')->name('order-price-update');
// });
Route::get('/google/connect', [GoogleController::class, 'connect']);
Route::get('/google/callback', [GoogleController::class, 'callback']);



Route::group(['middleware' => 'auth'], function () {
    Route::get('home', 'HomeController@index')->name('home');
    Route::get('/chart-data/{months}', 'HomeController@getChartData')->name('chart.data');
    Route::get('logout', 'Auth\LoginAdminController@logout')->name('logout');
    Route::resource('banner','Admin\BannerController');
    Route::post('banner-status-update', 'Admin\BannerController@statusUpdate')->name('banner-status-update');
    Route::resource('categories','Admin\CategoryController');
    Route::post('category-status-update', 'Admin\CategoryController@statusUpdate')->name('category-status-update');
    Route::get('sub-categories','Admin\CategoryController@subCategories')->name('sub-categories');
    Route::resource('trending-categories','Admin\TrendingCategoryController');
    Route::post('trending-category-status-update', 'Admin\TrendingCategoryController@statusUpdate')->name('trending-category-status-update');
    Route::resource('products','Admin\ProductController');
    Route::delete('product-price/{id}','Admin\ProductController@productPriceDelete')->name('product-price.destroy');
    Route::delete('product-image/{id}','Admin\ProductController@productImageDelete')->name('product-image.destroy');
    Route::post('product-status-update', 'Admin\ProductController@statusUpdate')->name('product-status-update');

    Route::resource('users','Admin\UserController');

    //----------------------------- Doctor Earnings starts----------------------------------------//
    Route::get('doctor-earnings','Admin\UserController@doctorEarnings')->name('doctor.earnings');
    Route::get('platform-earnings','Admin\UserController@totalPlatformEarnings')->name('platform.earnings');
    Route::get('doctor-earnings/{id}/details','Admin\UserController@doctorEarningsDetails')->name('doctor.earnings.details');

    Route::get('setting-manage','Admin\UserController@earningPercentage')->name('setting-manage');
    Route::post('earning-percentage','Admin\UserController@earningPercentageUpdate')->name('earning-percentage.update');
    Route::post('doctor/{doctor_id}/earnings-settle', 'Admin\UserController@settleDoctorEarnings')->name('doctor.earnings.settle');
    //----------------------------- Doctor Earnings ends----------------------------------------//

    Route::post('user-status-update', 'Admin\UserController@statusUpdate')->name('user-status-update');
    Route::get('prescription-orders','Admin\OrderController@prescriptionOrders')->name('prescription-orders');

    Route::get('cart-orders','Admin\OrderController@cartOrders')->name('cart-orders');
    //new
    Route::post('cart-orders/update-status/{id}', 'Admin\OrderController@updateCartOrderStatus')->name('cart-orders.update-status');

    Route::get('prescription-orders/details/{id}','Admin\OrderController@prescriptionOrderDetails')->name('prescription-orders.show');

    Route::get('cart-orders/{id}/details','Admin\OrderController@cartOrdersdetails')->name('cart-orders-details');

    Route::post('prescription-order-status-update','Admin\OrderController@prescriptionOrderStatusUpdate')->name('prescription-order-status-update');
    Route::post('order-price-update/{id}','Admin\OrderController@priceUpdate')->name('order-price-update');
    Route::post('prescription/delivery-status/{id}', 'Admin\OrderController@prescriptiondeliveryStatus')->name('prescriptionupdate-deliveryStatus');
    //-------------book appoinments---------------------------//
    Route::get('appointments', 'Admin\AppointmentController@index')->name('appointments.index');
    Route::get('appointments/{id}/show', 'Admin\AppointmentController@show')->name('appointments.show');
    Route::post('/appointments/{id}/status', 'Admin\AppointmentController@updateStatus')
    ->name('appointments.updateStatus');
    //-------------appoinments logs---------------------------//
    Route::get('appointments-logs', 'Admin\AppointmentLogsController@index')->name('appointments.logs.index');

    //google meet
    // Route::get('/admin/doctor/{doctorId}/google/connect', [GoogleAuthController::class, 'redirectToGoogle'])->name('admin.doctor.google.connect');
    // Route::get('/admin/doctor/google/callback', [GoogleAuthController::class, 'handleCallback'])->name('admin.doctor.google.callback');

});

