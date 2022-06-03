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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['APITranslation'])->group(function () {

Route::group(['prefix' => 'v1/{lang}'], function () {

    Route::get('categories', 'CategoryController@listCategories');

    Route::get('countries', 'CountryController@listCountries');

    Route::get('events', 'EventController@listEvents');

    Route::get('events/{id}', 'EventController@listEventsDetails');

    Route::get('events/top/list', 'EventController@listTopEvents');

    Route::get('app_review', function(){
        return ["success"=>True,"in_review"=> False];
        #return ["success"=>True,"in_review"=> True];
    });

    Route::post('events/nearby/list', 'EventController@listNearbyEvents');

    Route::get('events/today/list', 'EventController@listTodayEvents');

    Route::get('events/thisWeek/list', 'EventController@listThisWeekEvents');

    Route::post('login', 'AuthenticateController@login');

    Route::post('login/social', 'AuthenticateController@socialLogin');

    Route::post('password/code', 'AuthenticateController@getResetCode');

    Route::post('password/reset', 'AuthenticateController@passwordReset');

    Route::post('register', 'AuthenticateController@register');

    Route::get('cronJob', 'ServiceController@cronJob');

    Route::get('venues', 'VenueController@listVenues');

    Route::get('venues/{id}', 'VenueController@listVenuesDetails');

    Route::get('venues/top/list', 'VenueController@listTopVenues');

    Route::post('venues/nearby/list', 'VenueController@listNearbyVenues');

    Route::get('attractions', 'AttractionController@listAttractions');

    Route::get('attractions/{id}', 'AttractionController@listAttractionsDetails');

    Route::get('attractions/top/list', 'AttractionController@listTopAttractions');

    Route::post('attractions/nearby/list', 'AttractionController@listNearbyAttractions');

    Route::post('search/filter', 'ServiceController@searchFilter');

    Route::post('filter/category/all', 'ServiceController@filerCategory');

    Route::post('filter/category', 'ServiceController@filerVenuesAndAttractions');

    Route::post('filter/venue', 'ServiceController@filterVenue');

    Route::post('filter/event', 'ServiceController@filterEvent');

    Route::post('filter/attraction', 'ServiceController@filterAttraction');

    Route::post('nearby/all/list', 'ServiceController@listNearbyAll');

    Route::get('notifications', 'NotificationController@listNotification');

    Route::get('about-us', 'AboutUsController@listAboutUs');

    Route::get('contact/data', 'ListContactDataController@listContactData');

    Route::group(['middleware'=>'jwt-auth'] , function () {

        Route::post('password/change', 'AuthenticateController@changePassword');

        Route::get('profile', 'AuthenticateController@viewProfile');

        Route::post('profile/edit', 'AuthenticateController@editProfile');

        Route::get('events/{id}/like', 'ServiceController@likeEvent');

        Route::post('events/ticket/order', 'ServiceController@orderTickets');

        Route::post('payment/event/change', 'ServiceController@changeStatusEvent');

        Route::get('history/orders/upcoming', 'ServiceController@viewUpcomingOrderHistory');

        Route::get('history/orders/past', 'ServiceController@viewPastOrderHistory');

        Route::get('history/liked', 'ServiceController@viewLikedHistory');

        Route::get('events/order/cancel/{id}', 'ServiceController@cancelOrder');

        Route::get('attractions/order/cancel/{id}', 'ServiceController@cancelAttractionOrder');

        Route::get('venues/{id}/like', 'ServiceController@likeVenue');

        Route::get('venues/liked/list', 'ServiceController@viewLikedVenues');

        Route::get('attractions/{id}/like', 'ServiceController@likeAttraction');

        Route::get('attractions/liked/list', 'ServiceController@viewLikedAttractions');

        Route::get('users/subscribe', 'ServiceController@subscribe');

        Route::get('users/unsubscribe', 'ServiceController@unSubscribe');

        Route::post('contact', 'ServiceController@contactUs');

        Route::get('attractions/ticket/view', 'ServiceController@pickTicketsView');

        Route::post('attractions/ticket/order', 'ServiceController@orderAttractions');

        Route::post('payment/attraction/change', 'ServiceController@changeStatusAttraction');

        Route::get('history/attractions/upcoming', 'ServiceController@viewUpcomingOrderAttraction');

        Route::get('history/attractions/past', 'ServiceController@viewPastOrderAttraction');

      });

    });
});
