<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes  V1 /api
|--------------------------------------------------------------------------
|
*/


// Dealont APIs




/*
|--------------------------------------------------------------------------
| API Routes  MobileApp/Auth
|--------------------------------------------------------------------------
|
*/

Route::group(['prefix' => '/app/v1'], function () {

    Route::get("/home/init", 'API\Dealont\DealontController@homeInit')->name('home.init');
    Route::get("/setting/init", 'API\Dealont\DealontController@settingInit')->name('setting.init');

    Route::get("/location/list", 'API\Dealont\DealontController@locationList')->name('location.list');
    Route::get("/category/list", 'API\Dealont\DealontController@categoryList')->name('category.list');

    Route::get("/place/list", 'API\Dealont\DealontController@placeList')->name('place.list');
    Route::get("/place/view", 'API\Dealont\DealontController@placeView')->name('place.view');

    Route::post('/auth/register', 'API\Dealont\AuthController@register')->name('auth.register');
    Route::post('/auth/login', 'API\Dealont\AuthController@login')->name('auth.login');
    Route::post('/auth/forgot-password', 'API\Dealont\AuthController@forgotPassword')->name('auth.forgot-password');
    Route::post('/auth/reset', 'API\Dealont\AuthController@resetPassword')->name('auth.reset-password');

    Route::group(['prefix' => '/menu'], function () {
        Route::get('getRestaurantCategories', 'API\Dealont\MenuController@get_Restaurant_Categories_items');
        Route::get('getItemDetails', 'API\Dealont\MenuController@get_item_details');
        Route::get('order', 'API\Dealont\MenuController@post_order');
    });

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/auth/update-profile', 'API\Dealont\AuthController@updateProfile')->name('auth.update-profile');
        Route::get('/auth/user-info', 'API\Dealont\AuthController@userInfo')->name('auth.user-info');
        Route::post('/auth/change-password', 'API\Dealont\AuthController@changePassword')->name('auth.change-password');
        //whishlist endpoints
        Route::group(['prefix' => '/wishlist'], function () {
            Route::post('/save', 'API\Dealont\WishController@addToWishlist')->name('auth.add-wishlist');
            Route::get('/list', 'API\Dealont\WishController@getWishlist')->name('auth.get-wishlist');
            Route::post('/remove', 'API\Dealont\WishController@removeFromWishlist')->name('auth.remove-wish-list');
            Route::post('/reset', 'API\Dealont\WishController@resetWishlist')->name('auth.reset-wish-list');
        });
    });
});


//





Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'], function () {
    //Driver - Private
    Route::get('/driverorders', 'DriverController@getOrders')->name('driver.orders');
    Route::get('/updateorderstatus/{order}/{status}', 'DriverController@updateOrderStatus')->name('driver.updateorderstatus');
    Route::get('/updateorderlocation/{order}/{lat}/{lng}', 'DriverController@orderTracking')->name('driver.updateorderlocation');
    Route::get('/rejectorder/{order}', 'DriverController@rejectOrder')->name('driver.rejectorder');
    Route::get('/acceptorder/{order}', 'DriverController@acceptOrder')->name('driver.acceptorder');
    Route::get('/driveronline', 'DriverController@goOnline')->name('driver.goonline');
    Route::get('/drveroffline', 'DriverController@goOffline')->name('driver.gooffline');
});

//Driver - Public
Route::post('/drivergettoken', 'DriverController@getToken')->name('driver.getToken');




/*
|--------------------------------------------------------------------------
| API Routes  V2 /api/v2/
|--------------------------------------------------------------------------
|
*/
Route::prefix('v2/client')->group(function () {

    /**
     * AUTH
     */
    //Auth /api/v2/client/auth
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('gettoken', 'API\Client\AuthController@getToken')->name('getToken');
        Route::post('getTokenVendor', 'API\Client\AuthController@getTokenVendor')->name('getTokenVendor');
        Route::post('register', 'API\Client\AuthController@register')->name('register');
        Route::post('loginfb', 'API\Client\AuthController@loginFacebook');
        Route::post('logingoogle', 'API\Client\AuthController@loginGoogle');
        Route::group(['middleware' => 'auth:api'], function () {
            Route::get('data', 'API\Client\AuthController@getUseData')->name('getUseData');
        });
    });

    /**
     * Settings
     */
    //Settings /api/v2/client/settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', 'API\Client\SettingsController@index')->name('index');
    });



    /**
     * VENDOR
     */

    //Vendor /api/v2/client/vendor
    Route::prefix('vendor')->name('vendor.')->group(function () {
        Route::get('cities', 'API\Client\VendorController@getCities')->name('cities');
        Route::get('list/{city_id}', 'API\Client\VendorController@getVendors')->name('list');
        Route::get('{id}/items', 'API\Client\VendorController@getVendorItems')->name('items');
        Route::get('{id}/hours', 'API\Client\VendorController@getVendorHours')->name('hours');
        Route::get('/deliveryfee/{res}/{adr}', 'API\Client\VendorController@getDeliveryFee')->name('delivery.fee');
    });

    Route::get('/restaurants', 'API\Client\VendorController@getVendors');
    Route::get('/items/{id}', 'API\Client\VendorController@getVendorItems');
    Route::get('/discount/{id}/{discount}', 'API\Client\VendorController@setDiscount');
    Route::get('/selected-discount/{ids}/{id2}/{discount}', 'API\Client\VendorController@setSelectedDiscount');
    Route::get('/set-discount/{id}/{cat_id}/{index}/{discount}', 'API\Client\VendorController@setDiscountbyIndex');
    Route::get('/discount-all/{id}/{discount}/', 'API\Client\VendorController@setAllDiscount');

    // Countries Select
    Route::get('/get-countries', 'API\Client\VendorController@getCountries');
    Route::get('/countries', 'API\Client\VendorController@getCountriesFromDB');


    // Reviews
    Route::get('/get-reviews', 'API\Client\VendorController@getReviews');
    Route::post('/post-review', 'API\Client\VendorController@postReview');


    Route::get('/get-tokens', 'API\Client\VendorController@getTokens');
    Route::get('/send-notifications', 'API\Client\VendorController@sendNotifications');

    Route::get('/add-token', 'API\Client\VendorController@addToken');
    Route::get('/get-cities-domain', 'API\Client\VendorController@getCitiesFromDomain');




    //NEEDS AUTHENTICATION
    Route::group(['middleware' => 'auth:api'], function () {

        /**
         * ORDERS
         */

        //Orders /api/v2/client/orders
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', 'API\Client\OrdersController@index');
            Route::post('/', 'API\Client\OrdersController@store')->name('store');
        });


        /**
         * Addresses
         */

        //Addresses /api/v2/client/addresses
        Route::prefix('addresses')->name('orders.')->group(function () {
            Route::get('/', 'API\Client\AddressController@getMyAddresses');
            Route::get('/fees/{restaurant_id}', 'API\Client\AddressController@getMyAddressesWithFees');
            Route::post('/', 'API\Client\AddressController@makeAddress')->name('make.address');
            Route::post('/delete', 'API\Client\AddressController@deleteAddress')->name('delete.address');
        });

        /**
         * Notifications
         */

        //Notifications /api/v2/client/notifications
        Route::prefix('notifications')->name('orders.')->group(function () {
            Route::get('/', 'API\Client\NotificationsController@index');
        });
    });
});
