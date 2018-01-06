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

Auth::guard('api')->user(); // instance of the logged user
Auth::guard('api')->check(); // if a user is authenticated
Auth::guard('api')->id(); // the id of the authenticated user

Route::group(['middleware' => 'auth:api'], function() {
    Route::post('logout', 'Auth\LoginController@logout');
    Route::apiResource('user', 'UserController', ['only' => [
        'show'
    ]]);
    Route::apiResource('feeds', 'FeedsController');
    Route::apiResource('bank_accounts', 'BankAccountController');
    Route::apiResource('family', 'FamilyController');
    Route::apiResource('family_member', 'FamilyMemberController');

    Route::get('user/{user_id}/get_bank_accounts', 'UserController@getBankAccounts');
    Route::post('connect_family', 'UserController@connectUserToFamily');
    Route::get('family/{family_id}/get_family_members', 'FamilyController@getFamilyMembers');
});

Route::post('register', 'Auth\RegisterController@register');
Route::post('login', 'Auth\LoginController@login');