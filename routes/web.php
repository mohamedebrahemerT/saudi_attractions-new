<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['translate', 'auth', 'role'])->group(function () {
    Route::get('/home', 'HomeController@index')->name('index');

    Route::resource('locales', 'LocaleController');

    Route::resource('settings', 'SettingController');

    Route::resource('categories', 'CategoryController');

    Route::resource('subCategories', 'SubCategoryController');

    Route::resource('contactuses', 'ContactUsController');

    Route::resource('events', 'EventController');

    Route::resource('socialMedia', 'SocialMediaController');

    Route::get('users', 'UserController@index')->name('users.index');

    Route::get('users/admins', 'UserController@indexAdmin')->name('users.admins');

    Route::get('users/admins/create', 'UserController@create')->name('users.create');

    Route::post('users/admins/store', 'UserController@store')->name('users.store');

    Route::get('users/admins/{admin}/edit', 'UserController@edit')->name('users.edit');

    Route::patch('users/admins/update/{admin}', 'UserController@update')->name('users.update');

    Route::delete('users/admins/delete/{id}', 'UserController@destroy')->name('users.destroy');

    Route::get('users/block/{id}', 'UserController@block')->name('users.block');

    Route::get('users/unblock/{id}', 'UserController@unblock')->name('users.unblock');

    Route::get('orders', 'ListOrdersController@index')->name('orders.index');

    Route::get('orders/approve/{id}', 'ListOrdersController@approve')->name('orders.approve');

    Route::get('orders/reject/{id}', 'ListOrdersController@reject')->name('orders.reject');

    Route::get('orders/export/{type}', 'ListOrdersController@exportOrders')->name('orders.export');

    Route::get('orders/approved', 'ListOrdersController@approvedOrders')->name('orders.approved_orders');

    Route::get('orders/approved/show', 'ListOrdersController@showApprovedOrder')->name('orders.approved_show');

    Route::get('orders/show', 'ListOrdersController@showOrder')->name('orders.show');

    Route::get('orders/verify/{id}', 'ListOrdersController@verifyOrders')->name('orders.verify');

    Route::get('orders/attractions', 'ListAttractionOrderController@index')->name('attraction_orders.index');

    Route::get('orders/attractions/approve/{id}', 'ListAttractionOrderController@approve')->name('attraction_orders.approve');

    Route::get('orders/attractions/reject/{id}', 'ListAttractionOrderController@reject')->name('attraction_orders.reject');

    Route::get('orders/attractions/export/{type}', 'ListAttractionOrderController@exportAttractionOrders')->name('attraction_orders.export');

    Route::get('orders/attractions/approved', 'ListAttractionOrderController@approvedOrders')->name('attraction_orders.approved_orders');

    Route::get('orders/attractions/approved/show', 'ListAttractionOrderController@showApprovedOrder')->name('attraction_orders.approved_show');

    Route::get('orders/attractions/verify/{id}', 'ListAttractionOrderController@verifyOrders')->name('attraction_orders.verify');

    Route::get('orders/attractions/show', 'ListAttractionOrderController@showOrder')->name('attraction_orders.show');

    Route::resource('venues', 'VenueController');

    Route::resource('attractions', 'AttractionController');

    Route::get('attractions/days/{id}', 'AttractionController@getDays')->name('attractions.days');

    Route::post('attractions/days/{id}/store', 'AttractionController@storeDays')->name('attractions.store_days');

    Route::get('attractions/options/{id}/edit', 'AttractionController@editDays')->name('attractions.edit_days');

    Route::patch('attractions/options/{id}', 'AttractionController@updateDays')->name('attractions.update_days');

    Route::resource('notifications', 'NotificationController');

    Route::get('events/pushNotification/{id}', 'EventController@pushNotification')->name('events.notification');

    Route::get('venues/pushNotification/{id}', 'VenueController@pushNotification')->name('venues.notification');

    Route::get('attractions/pushNotification/{id}', 'AttractionController@pushNotification')->name('attractions.notification');

    Route::get('events/publish/{id}', 'EventController@publish')->name('events.publish');

    Route::get('venues/publish/{id}', 'VenueController@publish')->name('venues.publish');

    Route::get('attractions/publish/{id}', 'AttractionController@publish')->name('attractions.publish');

    Route::get('events/editable/{id}', 'EventController@editable')->name('events.editable');

    Route::get('venues/editable/{id}', 'VenueController@editable')->name('venues.editable');

    Route::get('attractions/editable/{id}', 'AttractionController@editable')->name('attractions.editable');

    Route::get('contact/export/{type}', 'ListContactUsController@exportContactUs')->name('contacts.export');

    Route::get('contact', 'ListContactUsController@index')->name('contacts.index');

    Route::resource('about_uses', 'AboutUsController');

    Route::resource('newsletters', 'NewsletterController');

    Route::get('events/search/filter', 'EventController@search')->name('events.search');

    Route::get('venues/search/filter', 'VenueController@search')->name('venues.search');

    Route::get('attractions/search/filter', 'AttractionController@search')->name('attractions.search');

});




Route::get('clear_cache', function () {
    \Artisan::call('config:cache');
    \Artisan::call('view:clear');
    \Artisan::call('route:clear');
    dd("Cache is cleared");
});

