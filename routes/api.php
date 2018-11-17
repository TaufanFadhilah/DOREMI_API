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

// Login
Route::post('login', 'UserController@login');
// Register
Route::post('register', 'UserController@register');
// Zomato APIs
// get Zomato Cities
Route::get('getCities', 'ZomatoController@getCities');
// get Restaurant by City
Route::post('getRestaurantByCity', 'ZomatoController@getRestaurantByCity');
// get Restaurant by Name or food
Route::post('getRestaurantByQuery', 'ZomatoController@getRestaurantByQuery');
// get detail restaurant
Route::post('getDetailRestaurant', 'ZomatoController@getDetailRestaurant');
// get review restaurant
Route::post('getReviewRestaurant', 'ZomatoController@getReviewRestaurant');

Route::group(['middleware' => 'auth:api'], function(){
  // detail logged user
  Route::get('profile', 'UserController@detail');
  // balance logged user
  Route::get('getBalance', 'UserController@getBalance');
  // add balance logged user
  Route::post('addBalance', 'UserController@addBalance');
  // buy food
  Route::post('buyFood', 'UserController@buyFood');
  // get history logged user
  Route::get('history', 'UserController@getHistory');
});


// OAUTH
Route::get('auth/{provider}', 'Auth\AuthController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\AuthController@handleProviderCallback');
