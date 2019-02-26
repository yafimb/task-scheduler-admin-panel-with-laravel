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

Route::group(['prefix'=>'prices'], function(){

    Route::group(['middleware'=>'auth:api'], function(){

       Route::get('/','ApiController@getRapnetPriceList');
       Route::get('user/{user}/shape/{shape}/size/{size}/clarity/{clarity}/color/{color}', 'ApiController@getPrise');

    });
});
