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
    Route::post('createUser', 'UserController@store')->middleware('role:admin'); //create user
    Route::get('getUser', 'UserController@index')->middleware('role:admin'); //read user
    Route::get('getUser/{id}', 'UserController@show')->middleware('role:admin'); //read user by id
    Route::post('updateUser/{id}', 'UserController@update')->middleware('role:admin'); //update profile user
    Route::delete('deleteUser/{id}', 'UserController@destroy')->middleware('role:admin'); //delete user

    //Role
    Route::post('createRole', 'RoleController@store')->middleware('role:admin'); //create role
    Route::get('getRole', 'RoleController@index')->middleware('role:admin'); //read role
    Route::get('getRole/{id}', 'RoleController@show')->middleware('role:admin'); //read role by id
    Route::post('updateRole/{id}', 'RoleController@update')->middleware('role:admin'); //update role
    Route::delete('deleteRole/{id}', 'RoleController@destroy')->middleware('role:admin'); //delete role

    //Supplier
    Route::post('createSupplier', 'SupplierController@store')->middleware('role:admin|staff'); //create supplier
    Route::get('getSupplier', 'SupplierController@index')->middleware('role:admin|pimpinan|staff'); //read supplier
    Route::get('getSupplier/{id}', 'SupplierController@show')->middleware('role:admin|pimpinan|staff'); //read supplier by id
    Route::post('updateSupplier/{id}', 'SupplierController@update')->middleware('role:admin|staff'); //update profile supplier
    Route::delete('deleteSupplier/{id}', 'SupplierController@destroy')->middleware('role:admin|staff'); //delete supplier

    //Category
    Route::post('createCategory', 'CategoryController@store')->middleware('role:admin|staff'); //create category
    Route::get('getCategory', 'CategoryController@index'); //read category
    Route::get('getCategory/{id}', 'CategoryController@show'); //read category by id
    Route::post('updateCategory/{id}', 'CategoryController@update')->middleware('role:admin|staff'); //update category
    Route::delete('deleteCategory/{id}', 'CategoryController@destroy')->middleware('role:admin|staff'); //delete category

    //Product
    Route::get('searchProduct/{data}', 'ProductController@search'); //search product
    Route::post('createProduct', 'ProductController@store')->middleware('role:admin|staff'); //create product
    Route::get('getProduct', 'ProductController@index'); //read product
    Route::get('getProduct/{id}', 'ProductController@show'); //read product by id
    Route::get('uidProduct/{uid}', 'ProductController@uid'); //read product by uid (kode produk)
    Route::post('updateProduct/{id}', 'ProductController@update')->middleware('role:admin|staff'); //update product
    Route::delete('deleteProduct/{id}', 'ProductController@destroy')->middleware('role:admin|staff'); //delete product

    //Pembelian
    Route::post('createPembelian', 'PembelianController@store')->middleware('role:admin|staff'); //create pembelian
    Route::get('getPembelian', 'PembelianController@index')->middleware('role:admin|pimpinan|staff'); //read pembelian
    Route::post('updatePembelian/{id}', 'PembelianController@update')->middleware('role:admin|staff'); //update pembelian
    Route::delete('deletePembelian/{id}', 'PembelianController@destroy')->middleware('role:admin|staff'); //delete pembelian

    //Penjualan
    Route::post('createPenjualan', 'PenjualanController@store')->middleware('role:admin|kasir'); //create penjualan
    Route::get('penjualanDibayar', 'PenjualanController@dibayar')->middleware('role:admin|pimpinan|kasir'); //read penjualan yg sudah dibayar
    Route::get('penjualanBelumbayar', 'PenjualanController@belumbayar')->middleware('role:admin|pimpinan|kasir'); //read penjualan yg belum dibayar
    Route::get('getPenjualan/{id}', 'PenjualanController@show')->middleware('role:admin|pimpinan|kasir'); //read penjualan by id
    Route::post('updatePenjualan/{id}', 'PenjualanController@update')->middleware('role:admin|kasir'); //update penjualan
    Route::delete('deletePenjualan/{id}', 'PenjualanController@destroy')->middleware('role:admin|kasir'); //delete penjualan

    Route::get('detailPenjualan/request', 'DetailPenjualanController@request')->middleware('role:admin|pimpinan|kasir'); //read penjualan yg di request
    Route::post('detailPenjualan/confirm', 'DetailPenjualanController@confirm')->middleware('role:admin|kasir'); //mengonfirmasi penjualan

    //Pengeluaran
    Route::post('createPengeluaran', 'PengeluaranController@store')->middleware('role:admin|staff'); //create pengeluaran
    Route::get('getPengeluaran', 'PengeluaranController@index')->middleware('role:admin|pimpinan|staff'); //read pengeluaran
    Route::post('updatePengeluaran/{id}', 'PengeluaranController@update')->middleware('role:admin|staff'); //update pengeluaran
    Route::delete('deletePengeluaran/{id}', 'PengeluaranController@destroy')->middleware('role:admin|staff'); //delete pengeluaran

    //Cashier
    Route::post('createCashier', 'CashierController@store')->middleware('role:admin|pimpinan'); //create cashier
    Route::get('getCashier', 'CashierController@index')->middleware('role:admin|pimpinan'); //read cashier
    Route::get('getCashier/{id}', 'CashierController@show')->middleware('role:admin|pimpinan'); //read cashier by id
    Route::post('updateCashier/{id}', 'CashierController@update')->middleware('role:admin|pimpinan'); //update profile cashier
    Route::delete('deleteCashier/{id}', 'CashierController@destroy')->middleware('role:admin|pimpinan'); //delete cashier

    //Member
    Route::post('createMember', 'MemberController@store')->middleware('role:admin|kasir'); //create member
    Route::get('getMember', 'MemberController@index')->middleware('role:admin|pimpinan|kasir'); //read member
    Route::get('getMember/{id}', 'MemberController@show')->middleware('role:admin|pimpinan|kasir'); //read member by id
    Route::get('kodeMember/{kode_member}', 'MemberController@kodeMember')->middleware('role:admin|pimpinan|kasir'); //read member by kode_member
    Route::post('updateMember/{id}', 'MemberController@update')->middleware('role:admin|kasir'); //update member
    Route::delete('deleteMember/{id}', 'MemberController@destroy')->middleware('role:admin|kasir'); //delete member

    Route::get('saldoMember/{id}', 'MemberController@saldo')->middleware('role:admin|kasir|pimpinan|member'); //read saldo
    Route::post('topupMember/{id}', 'MemberController@topup')->middleware('role:admin|kasir'); //topup saldo

    //Laporan
    Route::get('getLaporan', 'LaporanController@index')->middleware('role:admin|pimpinan'); //read laporan keuangan

    //Absent
    Route::get('getAbsent', 'AbsentController@absent')->middleware('role:admin|pimpinan'); //read rekap absent harian


});
