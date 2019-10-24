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


Route::post('/timekeeping','Auth\Api\AuthController@timekeeping');

Route::post('/login','Auth\Api\AuthController@login');

Route::middleware('auth:api')->group(function () {

    Route::post('/timekeeping_app','Auth\Api\AuthController@timekeeping_app');
    
    Route::post('/logout','Auth\Api\AuthController@logout');

    Route::post('/register','Auth\Api\AuthController@register');

    Route::post('/show_dtr_list','Auth\Api\AuthController@show_dtr_list');

    Route::post('/update','Auth\Api\AuthController@update');

    Route::post('/delete','Auth\Api\AuthController@delete');

    Route::post('/search_user','Auth\Api\AuthController@search_user');

    Route::get('/current_time','Auth\Api\AuthController@current_time');

});