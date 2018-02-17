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
    Route::apiResource('family_registration', 'FamilyRegistrationController');
    Route::apiResource('client', 'ClientController');
    Route::apiResource('payment', 'PaymentController');
    Route::apiResource('claim', 'ClaimController');

    Route::get('user/{user_id}/get_bank_accounts', 'UserController@getBankAccounts');
    Route::post('connect_family', 'UserController@connectUserToFamily');
    Route::get('family/{family_id}/family_members', 'FamilyController@getFamilyMembers');

    Route::get('dpgk/{family_id}/unregistered_family_members', 'DPGKController@getUnregisteredFamilyMembers');
    Route::get('dpgk/{family_id}/registered_family_members', 'DPGKController@getRegisteredFamilyMembers');
    Route::get('dpgk/{family_id}/family_members', 'DPGKController@getSplittedFamilyMembers');
    Route::get('dpgk/{family_id}/history', 'DPGKController@getHistory');
    Route::get('dpgk/{family_id}/get_prepayment_info', 'DPGKController@getPaymentInfo');
    Route::get('dpgk/{family_id}/get_claim_info', 'DPGKController@getClaimInfo');

    Route::get('dkk/{family_id}/unregistered_family_members', 'DKKController@getUnregisteredFamilyMembers');
    Route::get('dkk/{family_id}/registered_family_members', 'DKKController@getRegisteredFamilyMembers');
    Route::get('dkk/{family_id}/family_members', 'DKKController@getSplittedFamilyMembers');
    Route::get('dkk/{family_id}/history', 'DKKController@getHistory');
    Route::get('dkk/{family_id}/get_prepayment_info', 'DKKController@getPaymentInfo');

    Route::get('dwk/{family_id}/unregistered_family_members', 'DWKController@getUnregisteredFamilyMembers');
    Route::get('dwk/{family_id}/registered_family_members', 'DWKController@getRegisteredFamilyMembers');
    Route::get('dwk/{family_id}/family_members', 'DWKController@getSplittedFamilyMembers');
    Route::get('dwk/{family_id}/history', 'DWKController@getHistory');
    Route::get('dwk/{family_id}/get_prepayment_info', 'DWKController@getPaymentInfo');
    Route::get('dwk/{family_id}/get_claim_info', 'DWKController@getClaimInfo');

    Route::post('claim/upload_file', 'ClaimController@upload');
});

Route::post('register', 'Auth\RegisterController@register');
Route::post('login', 'Auth\LoginController@login');