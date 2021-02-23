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

Route::group(['middleware' => 'auth:api', 'namespace' => 'Api'], function () {
    Route::post('logout', 'UserController@logout');

    //Profile
    Route::get('getProfile', 'ProfileController@index'); //read profile
    Route::post('updateProfile', 'ProfileController@update'); //update profile
    Route::post('changePassword', 'ProfileController@change'); //change password
    Route::delete('deleteProfile', 'ProfileController@destroy'); //delete profile

    //User
    Route::post('createUser', 'UserController@store'); //create user
    Route::get('getUser', 'UserController@index'); //read user
    Route::post('updateUser/{id}', 'UserController@update'); //update profile user
    Route::delete('deleteUser/{id}', 'UserController@destroy'); //delete user

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

    //Pembelian
    Route::get('getPembelian', 'PembelianController@index'); //read pembelian
    Route::post('createPembelian', 'PembelianController@store'); //create pembelian
    Route::post('updatePembelian/{id}', 'PembelianController@update'); //update pembelian
    Route::delete('deletePembelian/{id}', 'PembelianController@destroy'); //delete pembelian

    //Pengeluaran
    Route::post('createPengeluaran', 'PengeluaranController@store')->middleware('role:admin|pimpinan'); //create pengeluaran
    Route::get('getPengeluaran', 'PengeluaranController@index')->middleware('role:admin|pimpinan'); //read pengeluaran
    Route::post('updatePengeluaran/{id}', 'PengeluaranController@update')->middleware('role:admin|pimpinan'); //update pengeluaran
    Route::delete('deletePengeluaran/{id}', 'PengeluaranController@destroy')->middleware('role:admin|pimpinan'); //delete pengeluaran

    //Cashier
    Route::post('createCashier', 'CashierController@store')->middleware('role:admin'); //create cashier
    Route::get('getCashier', 'CashierController@index')->middleware('role:admin'); //read cashier
    Route::get('getCashier/{id}', 'CashierController@show')->middleware('role:admin'); //read cashier by id
    Route::post('updateCashier/{id}', 'CashierController@update')->middleware('role:admin'); //update profile cashier
    Route::delete('deleteCashier/{id}', 'CashierController@destroy')->middleware('role:admin'); //delete cashier

    //Member
    Route::post('createMember', 'MemberController@store')->middleware('role:admin|kasir'); //create member
    Route::get('getMember', 'MemberController@index')->middleware('role:admin|kasir'); //read member
    Route::get('getMember/{id}', 'MemberController@show')->middleware('role:admin|kasir'); //read member by id
    Route::get('kodeMember/{kode_member}', 'MemberController@kodeMember')->middleware('role:admin|kasir'); //read member by kode_member
    Route::post('updateMember/{id}', 'MemberController@update')->middleware('role:admin|kasir'); //update member
    Route::delete('deleteMember/{id}', 'MemberController@destroy')->middleware('role:admin|kasir'); //delete member

    Route::get('saldoMember/{id}', 'MemberController@saldo')->middleware('role:admin|kasir|member'); //read saldo
    Route::post('topupMember/{id}', 'MemberController@topup')->middleware('role:admin|kasir'); //topup saldo

    Route::get('penjualan/dibayar', 'PenjualanController@dibayar');
    Route::get('penjualan/belumbayar', 'PenjualanController@belumbayar');
    Route::get('penjualan/{id}', 'PenjualanController@show');
    Route::post('penjualan', 'PenjualanController@store');
    Route::post('penjualan/{id}', 'PenjualanController@update');
    Route::delete('penjualan/{id}', 'PenjualanController@destroy');

    
});
