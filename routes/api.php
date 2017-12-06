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
Route::middleware('jwt.auth')->group(function(){
//Route::get('/candidates/', 'CandidateController@list');
});
// Route::group(['middleware' => 'jwt.auth'],function(){
//   Route::get('/candidates/', 'CandidateController@list');
//  });

//Users
Route::post('users', 'UserControl@create');
Route::get('/users/{id}', 'UserControl@show');
Route::get('/users/', 'UserControl@list');
Route::put('/users/{id}', 'UserControl@update');
Route::delete('/users/{id}', 'UserControl@delete');
Route::post('users/{id}/policies/set', 'UserControl@setPolicies');
Route::post('/auth/login/', 'UserControl@login');
Route::get('/auth/decode/', 'PermissionController@decodeToken');
//Groups
Route::post('groups', 'GroupController@create');
Route::post('/groups/{id}/addPolicies', 'GroupController@addPolicies');
Route::get('/groups/{id}', 'GroupController@show');
Route::get('/groups/', 'GroupController@list');
Route::put('/groups/{id}', 'GroupController@update');
Route::delete('/groups/{id}', 'GroupController@delete');
Route::delete('/groups/{id}/removePolicies', 'GroupController@removePolicies');
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
//CivilStatus
Route::post('civilstatuses', 'CivilStatusController@create');
//Route::get('/civilstatuses/{id}', 'CivilStatusController@show');
Route::get('/civilstatuses/', 'CivilStatusController@list');
Route::put('/civilstatuses/{id}', 'CivilStatusController@update');
Route::delete('/civilstatuses/{id}', 'CivilStatusController@delete');
//Job
//CivilStatus
Route::post('jobs', 'JobController@create');
//Route::get('/civilstatuses/{id}', 'CivilStatusController@show');
Route::get('/jobs/', 'JobController@list');
Route::put('/jobs/{id}', 'JobController@update');
Route::delete('/jobs/{id}', 'JobController@delete');
//Candidate
Route::post('candidates', 'CandidateController@create');
Route::get('/candidates/', 'CandidateController@list');
Route::get('/evaluations/{id}/candidates/', 'CandidateController@byEvaluations');
Route::get('/candidates/{id}/', 'CandidateController@show');
Route::put('/candidates/{id}', 'CandidateController@update');
Route::put('/candidates/{id}/state', 'CandidateController@updateState');
Route::get('/candidatesClass/', 'CandidateController@listClassified');
Route::post('/candidates/{id}/hire', 'EmployeeController@hire');
Route::get('/employees/{code}/', 'EmployeeController@show');
//Evaluation
Route::post('evaluations', 'EvaluationController@create');
Route::get('/evaluations/', 'EvaluationController@list');
Route::post('/evaluations/{id}/addCandidates', 'EvaluationController@massAddingCandidates');
Route::put('/evaluations/{id}/state', 'EvaluationController@updateState');
Route::post('/evaluations/{id}/candidates/{idCandidate}/qualify', 'EvaluationController@qualify');
//LoanTypes
Route::post('loantypes', 'LoanTypesController@create');
Route::get('/loantypes/', 'LoanTypesController@list');
Route::put('/loantypes/{id}', 'LoanTypesController@update');
Route::delete('/loantypes/{id}', 'LoanTypesController@delete');
//loans
Route::post('/employees/{id}/loans', 'LoanController@create');
Route::get('/loans/', 'LoanController@list');
Route::put('/loans/{id}/discount', 'LoanController@discountLoan');
//FiredRetired
Route::post('/employees/{id}/fired', 'FiredRetiredController@fired');
Route::post('/employees/{id}/retired', 'FiredRetiredController@retired');
//employees
Route::get('/employees/', 'EmployeeController@list');
//Pays
Route::post('/pays/', 'PayController@generate');
Route::get('/pays/', 'PayController@list');
Route::get('/pays/{id}/', 'PayController@show');
Route::put('/pays/{id}/', 'PayController@calculate');
Route::post('/pays/{idPay}/employees/{idEmployee}/otherDiscounts/', 'PayController@otherDiscounts');
Route::post('/pays/{idPay}/employees/{idEmployee}/otherIncomes/', 'PayController@otherIncomes');
//Attendence
Route::get('/attendances/{idAtt}/employees/{code}/', 'AttendenceController@show');
Route::get('/attendances/{idAtt}/employees/{code}/absences/', 'AttendenceController@showAbsences');
Route::get('/attendances/{idAtt}/employees/{code}/hours/', 'AttendenceController@getHours');
Route::get('/attendances/{idAtt}/hours/', 'AttendenceController@byPeriod');
Route::get('/attendances/', 'AttendenceController@list');
Route::put('/attendances/{id}/modify', 'AttendenceController@update');
Route::post('/attendances/', 'AttendenceController@generate');
Route::post('/attendancesGenData/', 'AttendenceController@generateDates');
Route::put('/attendances/{id}/', 'AttendenceController@massUpdate');
