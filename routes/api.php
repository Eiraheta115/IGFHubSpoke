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
//Users
Route::post('users', 'UserControl@create');
Route::get('/users/{id}', 'UserControl@show');
Route::get('/users/', 'UserControl@list');
Route::put('/users/{id}', 'UserControl@update');
Route::delete('/users/{id}', 'UserControl@delete');
Route::post('users/{id}/policies/set', 'UserControl@setPolicies');
//Groups
Route::post('groups', 'GroupController@create');
Route::get('/groups/{id}', 'GroupController@show');
Route::get('/groups/', 'GroupController@list');
Route::put('/groups/{id}', 'GroupController@update');
Route::delete('/groups/{id}', 'GroupController@delete');
//Policies
Route::post('policies', 'PolicyController@create');
Route::get('/policies/{id}', 'PolicyController@show');
Route::get('/policies/', 'PolicyController@list');
Route::put('/policies/{id}', 'PolicyController@update');
Route::delete('/policies/{id}', 'PolicyController@delete');
