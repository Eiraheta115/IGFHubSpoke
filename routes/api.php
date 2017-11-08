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
//Rent
Route::post('rents', 'RentController@create');
//Route::get('/rent/{id}', 'RentController@show');
Route::get('/rents/', 'RentController@list');
Route::put('/rents/{id}', 'RentController@update');
Route::delete('/rents/{id}', 'RentController@delete');
Route::put('/rents/{id}/state', 'RentController@updateState');
//Tax
Route::post('taxes', 'TaxController@create');
//Route::get('/taxes/{id}', 'TaxController@show');
Route::get('/taxes/', 'TaxController@list');
Route::put('/taxes/{id}', 'TaxController@update');
Route::delete('/taxes/{id}', 'TaxController@delete');
Route::put('/taxes/{id}/state', 'TaxController@updateState');
//TaxDetail
Route::post('taxes/{id}/details', 'TaxDetailController@create');
Route::get('/taxes/{id}/details', 'TaxDetailController@list');
Route::put('/taxes/{id}/details/{idDetail}', 'TaxDetailController@update');
Route::delete('/taxes/{id}/details/{idDetail}', 'TaxDetailController@delete');
//SalaryType
Route::post('salaries', 'SalaryTypeController@create');
//Route::get('/salaries/{id}', 'SalaryTypeController@show');
Route::get('/salaries/', 'SalaryTypeController@list');
Route::put('/salaries/{id}', 'SalaryTypeController@update');
Route::put('/salaries/{id}/state', 'SalaryTypeController@updateState');
Route::delete('/salaries/{id}', 'SalaryTypeController@delete');
