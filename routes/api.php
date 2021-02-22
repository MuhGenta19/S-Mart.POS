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

    //Supplier
    Route::post('createSupplier', 'SupplierController@store'); //create supplier
    Route::get('getSupplier', 'SupplierController@index'); //read supplier
    Route::get('getSupplier/{id}', 'SupplierController@show'); //read supplier by id
    Route::post('updateSupplier/{id}', 'SupplierController@update'); //update profile supplier
    Route::delete('deleteSupplier/{id}', 'SupplierController@destroy'); //delete supplier

    //Category
    Route::post('createCategory', 'CategoryController@store'); //create category
    Route::get('getCategory', 'CategoryController@index'); //read category
    Route::get('getCategory/{id}', 'CategoryController@show'); //read category by id
    Route::post('updateCategory/{id}', 'CategoryController@update'); //update category
    Route::delete('deleteCategory/{id}', 'CategoryController@destroy'); //delete category

    //Product
    Route::get('searchProduct/{data}', 'ProductController@search'); //search product
    Route::post('createProduct', 'ProductController@store'); //create product
    Route::get('getProduct', 'ProductController@index'); //read product
    Route::get('getProduct/{id}', 'ProductController@show'); //read product by id
    Route::get('uidProduct/{uid}', 'ProductController@uid'); //read product by uid (kode produk)
    Route::post('updateProduct/{id}', 'ProductController@update'); //update product
    Route::delete('deleteProduct/{id}', 'ProductController@destroy'); //delete product

    //Cashier
    Route::post('createCashier', 'CashierController@store')->middleware('role:admin'); //create cashier
    Route::get('getCashier', 'CashierController@index')->middleware('role:admin'); //read cashier
    Route::get('getCashier/{id}', 'CashierController@show')->middleware('role:admin'); //read cashier by id
    Route::post('updateCashier/{id}', 'CashierController@update')->middleware('role:admin'); //update profile cashier
    Route::delete('deleteCashier/{id}', 'CashierController@destroy')->middleware('role:admin'); //delete cashier


});
