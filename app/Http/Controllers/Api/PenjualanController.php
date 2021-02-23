<?php

namespace App\Http\Controllers\Api;

use App\Product;
use App\DetailPenjualan;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Support\Facades\Auth;
use App\Member;
use App\Penjualan;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenjualanController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dibayar()
    {
        $penjualan = Penjualan::where('dibayar', '>', 0)->latest()->get();
        $penjualan->load('product', 'member', 'user');
        if ($penjualan == []) {
            return $this->responseError(403, 'there is no penjualan data that has been paid');
        }
        return $this->responseOk($penjualan);
    }

    public function belumbayar()
    {
        $penjualan = Penjualan::where('dibayar', '=', 0)->latest()->get();
        $penjualan->load('product');
        if ($penjualan == []) {
            return $this->responseError(403, 'there is no penjualan data that has been paid');
        }
        return $this->responseOk($penjualan);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer',
            'jumlah_barang' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->responseError(422, $validator->errors(), "failed to add product(s) to cart");
        }

        $product = product::find($request->product_id);
        $params = [
            'product_id' => $request->product_id,
            'jumlah_barang' => $request->jumlah_barang,
            'total_harga' => $product->harga_jual * $request->jumlah_barang,
            'dibayar' => 0,
            'kembalian' => 0,
            'member_id' => null,
            'user_id' => 0
        ];

        if ($request->jumlah_barang > $product->stok) {
            return $this->responseError('remaining stock products' . $product->stok);
        }
        $penjualan = Penjualan::create($params);
        $data['penjualan_id'] = $penjualan->id;
        DetailPenjualan::create($data);
        return $this->responseOk($penjualan->load('user'), 201, "successfully added product(s) to cart");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $penjualan = Penjualan::find($id);
        $penjualan->load('product', 'member', 'user');
        return $this->responseOk($penjualan);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'product_id' => 'integer',
            'jumlah_barang' => 'integer',
        ]);

        if ($validator->fails()) {
            return $this->responseError(422, $validator->errors(), "failed to update product(s) in cart");
        }
        $penjualan = Penjualan::find($id);

        if ($penjualan->dibayar > 0) {
            return $this->responseError("cannot update or exchange product(s) that have been purchased");
        }
        $product = Product::find($request->product_id ?? $penjualan->product_id);
        $params = [
            'product_id' => $request->product_id ?? $penjualan->product_id,
            'jumlah_barang' => $request->jumlah_barang ?? $penjualan->jumlah_barang,
            'dibayar' => 0,
            'kembalian' => 0,
            'member_id' => null,
            'user_id' => 0
        ];
        $params['total_harga'] = $product->harga_jual * $params['jumlah_barang'];

        if ($params['jumlah_barang'] > $product->stok) {
            return $this->responseError('Stok product sisa ' . $product->stok);
        }
        $penjualan->update($params);
        return $this->responseOk($penjualan, 200, "successfully updated product(s) in cart");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $penjualan =  Penjualan::find($id);
        $detailpenjualan = DetailPenjualan::where('penjualan_id', $penjualan->id);

        if ($penjualan->dibayar > 0) {
            return $this->responseError("cannot delete or return product(s) that have been purchased");
        }

        $detailpenjualan->delete();
        $penjualan->delete();

        return $this->responseOk($penjualan, 200, "successfully deleted product(s) in cart");
    }
}
