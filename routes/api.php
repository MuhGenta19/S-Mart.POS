<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

//Auth
Route::post('login', 'Api\UserController@login'); //login
Route::post('register', 'Api\UserController@register'); //register

Route::post('forgotPassword', 'Api\ForgotPasswordController@sendResetLinkEmail'); //forgot password
Route::post('resetPassword', 'Api\ResetPasswordController@reset'); //reset password

Route::get('email/resend', 'Api\VerificationController@resend')->name('verification.resend'); //email verification resend
Route::get('email/verify/{id}/{hash}', 'Api\VerificationController@verify')->name('verification.verify'); //email verification verify

//User
Route::group(['middleware' => 'auth:api', 'namespace' => 'Api'], function () {
    Route::post('logout', 'UserController@logout');


});
