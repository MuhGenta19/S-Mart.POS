<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth::routes();

Route::get('/', 'HomeController@index');

Route::get('/', function() {
    return redirect('/login');
});

Route::get('/register', function() {
    return redirect('/login');
});
Route::get('/password/reset', function() {
    return redirect('/login');
});

Route::get('/admin', function() {
    return redirect('/login');
});

Route::group(['middleware' => 'auth:web', 'namespace' => 'Admin'], function () {
    // Route::get('admin', 'DashboardController@index')->name('dashboard.index');

    Route::get('admin/user', 'UserController@index')->name('user.index')->middleware('role:admin|pimpinan');
    Route::post('admin/user', 'UserController@store')->name('user.store')->middleware('role:admin');
    Route::post('admin/user/{id}', 'UserController@update')->name('user.update')->middleware('role:admin');
    Route::delete('admin/user/{id}', 'UserController@destroy')->name('user.destroy')->middleware('role:admin');

    Route::get('admin/supplier', 'SupplierController@index')->name('supplier.index')->middleware('role:admin|pimpinan|staff');
    Route::post('admin/supplier', 'SupplierController@store')->name('supplier.store')->middleware('role:admin|staff');
    Route::post('admin/supplier/{id}', 'SupplierController@update')->name('supplier.update')->middleware('role:admin|staff');
    Route::delete('admin/supplier/{id}', 'SupplierController@destroy')->name('supplier.destroy')->middleware('role:admin|staff');

    Route::get('admin/member', 'MemberController@index')->name('member.index')->middleware('role:admin|pimpinan|kasir');
    Route::post('admin/member', 'MemberController@register')->name('member.register')->middleware('role:admin|kasir');
    Route::post('admin/member/{id}', 'MemberController@topup')->name('member.topup')->middleware('role:admin|kasir');
    Route::delete('admin/member/{id}', 'MemberController@destroy')->name('member.destroy')->middleware('role:admin|kasir');

    Route::get('admin/barang', 'BarangController@index')->name('barang.index');
    Route::post('admin/barang', 'BarangController@store')->name('barang.store')->middleware('role:admin|staff');
    Route::post('admin/barang/{id}', 'BarangController@update')->name('barang.update')->middleware('role:admin|staff');
    Route::delete('admin/barang/{id}', 'BarangController@destroy')->name('barang.destroy')->middleware('role:admin|staff');

    Route::get('admin/kategori', 'KategoriController@index')->name('kategori.index');
    Route::post('admin/kategori', 'KategoriController@store')->name('kategori.store')->middleware('role:admin|staff');
    Route::post('admin/kategori/{id}', 'KategoriController@update')->name('kategori.update')->middleware('role:admin|staff');
    Route::delete('admin/kategori/{id}', 'KategoriController@destroy')->name('kategori.destroy')->middleware('role:admin|staff');

    Route::get('admin/pembelian', 'PembelianController@index')->name('pembelian.index')->middleware('role:admin|pimpinan|staff');
    Route::post('admin/pembelian', 'PembelianController@store')->name('pembelian.store')->middleware('role:admin|staff');;
    Route::post('admin/pembelian/{id}', 'PembelianController@update')->name('pembelian.update')->middleware('role:admin|staff');
    Route::delete('admin/pembelian/{id}', 'PembelianController@destroy')->name('pembelian.destroy')->middleware('role:admin|staff');

    Route::get('admin/penjualan/dibayar', 'PenjualanController@dibayar')->middleware('role:admin|pimpinan|kasir');
    Route::get('admin/penjualan/belumbayar', 'PenjualanController@belumbayar')->name('penjualan.belumbayar')->middleware('role:admin|pimpinan|kasir');
    Route::post('admin/penjualan/konfirmasi', 'PenjualanController@konfirmasi')->name('penjualan.konfirmasi')->middleware('role:admin|kasir');
    Route::post('admin/penjualan', 'PenjualanController@store')->name('penjualan.store')->middleware('role:admin|kasir');
    Route::post('admin/penjualan/{id}', 'PenjualanController@update')->name('penjualan.update')->middleware('role:admin|kasir');
    Route::delete('admin/penjualan/{id}', 'PenjualanController@destroy')->name('penjualan.destroy')->middleware('role:admin|kasir');

    Route::get('admin/pengeluaran', 'PengeluaranController@index')->name('pengeluaran.index')->middleware('role:admin|pimpinan');
    Route::post('admin/pengeluaran', 'PengeluaranController@store')->name('pengeluaran.store')->middleware('role:admin|pimpinan');
    Route::post('admin/pengeluaran/{id}', 'PengeluaranController@update')->name('pengeluaran.update')->middleware('role:admin|pimpinan');
    Route::delete('admin/pengeluaran/{id}', 'PengeluaranController@destroy')->name('pengeluaran.destroy')->middleware('role:admin|pimpinan');

    Route::get('admin/laporan', 'LaporanController@index')->name('laporan.index')->middleware('role:admin|pimpinan');

    Route::get('admin/absent', 'AbsentController@index')->name('absent.index')->middleware('role:admin|pimpinan');
});
