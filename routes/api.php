<?php


use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('app-settings','AuthController@appSettings');
Route::post('send-otp','AuthController@sendOtp');
Route::post('otp-verify', 'AuthController@otpVerify');
Route::post('sign-up', 'AuthController@signup');

Route::post('razorpay/webhook', 'OrderController@razorpayWebhook');


Route::get('dashboard','HomeController@dashboard');
Route::get('sub-categories','HomeController@subCategories');
Route::get('products','HomeController@products');
Route::get('products/{id}','HomeController@productDetails');
Route::get('get-doctors/{category_id}', 'ProfileController@getDoctors');
Route::get('show-doctor/{doctor_id}', 'ProfileController@showDoctor');
Route::post('get-avalable-slots', 'ProfileController@getAvalableSlots');

Route::prefix('meets')->group(function () {
    Route::post('/', 'MeetController@store');
    Route::get('/', 'MeetController@list');
    Route::get('{eventId}', 'MeetController@show');
    Route::put('{eventId}', 'MeetController@update');
    Route::delete('{eventId}', 'MeetController@destroy');
});

Route::middleware(['auth:api'])->group(function () {
    Route::post('fcm-version-update','AuthController@fcmVersionUpdate');
    Route::get('notifications','ProfileController@notificationLists');
	Route::get('my-profile','ProfileController@myProfile');
	Route::post('profile-update','ProfileController@profileUpdate');

    Route::get('get-appointments-list', 'ProfileController@getAppointmentsList');
    Route::get('get-appointment-details', 'ProfileController@getAppointmentDetails');

    //doctors & customer section
    Route::get('/my-prescriptions', 'ProfileController@getMyPrescriptions');
    Route::post('appointment/razorpay-order', 'ProfileController@createAppointmentRazorpayOrder');
    Route::post('book-appointment', 'ProfileController@bookAppoinment');
    Route::post('cancel-appointment', 'ProfileController@cancelAppointment');
    Route::post('request-for-price-quote', 'ProfileController@requestForPrescription');

    //payment section
    Route::post('payment-method', 'ProfileController@paymentMethod');
    Route::get('payment-history', 'ProfileController@paymentHistory');
    Route::get('payment-method-dropdown', 'ProfileController@paymentMethodDropdown');

    Route::post('create-booking-slot','ProfileController@createBookingSlot');

    Route::get('booking-slots', 'ProfileController@getBookingSlots');
    Route::post('update-booking-slot/{slot_id}', 'ProfileController@updateBookingSlot');

    Route::post('upload-prescription-by-doctor', 'ProfileController@uploadPrescriptionByDoctor');
    // Route::post('cancel-appointment-by-doctor', 'ProfileController@cancelAppointmentByDoctor');//not used anymore
    Route::post('calculate-earnings-for-doctor', 'ProfileController@calculateEarningsForDoctor');

	// Cart Section
	Route::get('cart','CartController@index');
    Route::post('cart','CartController@store');
    Route::put('cart','CartController@update');
    Route::delete('cart/item/{id}','CartController@cartItemDelete');
	// Address
	Route::get('addresses','AddressController@index');
    Route::post('addresses','AddressController@store');

    Route::post('cart/razorpay-order','OrderController@createRazorpayOrder');

    Route::get('order','OrderController@index');
    Route::get('/order/{id}', 'OrderController@getOrderDetails');
    Route::post('upload-prescription','OrderController@uploadPrescription');
    Route::post('order','OrderController@store');//Cart Order
    //prescription order payment
    Route::post('prescription-order/razorpay-order', 'OrderController@createPrescriptionRazorpayOrder');
    Route::post('prescription-order/verify-payment', 'OrderController@verifyPrescriptionPayment');
});

// Route::get('app-settings','AuthController@appSettings');

// Route::post('send-otp','AuthController@sendOtp');

// Route::post('otp-verify', 'AuthController@otpVerify');

// Route::post('sign-up', 'AuthController@signup');

// Route::get('dashboard','HomeController@dashboard');

// Route::get('sub-categories','HomeController@subCategories');

// Route::get('products','HomeController@products');

// Route::get('products/{id}','HomeController@productDetails');

// Route::middleware(['auth:api'])->group(function () {

// 	Route::get('my-profile','ProfileController@myProfile');

// 	Route::post('profile-update','ProfileController@profileUpdate');

// 	// Cart Section

// 	Route::get('cart','CartController@index');

//     Route::post('cart','CartController@store');

//     Route::put('cart','CartController@update');

//     Route::delete('cart/item/{id}','CartController@cartItemDelete');

// 	// Address

// 	Route::get('addresses','AddressController@index');

//     Route::post('addresses','AddressController@store');

//     Route::get('order','OrderController@index');

//     Route::post('upload-prescription','OrderController@uploadPrescription');

//     Route::post('order','OrderController@store');//Cart Order

// });
