<?php

namespace App\Http\Controllers\Api;

use App\Product;
use App\Http\Controllers\Api\BaseController;
use App\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PembelianController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pembelian = Pembelian::latest()->get();
        $pembelian->load('product:id,name','supplier:id,name');
        if ($pembelian == []) {
            return $this->responseError('there is no pengeluaran yet');
        } else {
            return $this->responseOk($pembelian);
        }
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
            'supplier_id' => 'required|integer',
            'product_id' => 'required|integer',
            'total_barang' => 'required|integer',
            'total_biaya' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->responseError(422, $validator->errors(), 'failed to add pembelian');
        }

        $params = [
            'supplier_id' => $request->supplier_id,
            'product_id' => $request->product_id,
            'total_barang' => $request->total_barang,
            'total_biaya' => $request->total_biaya,
        ];

        $pembelian = Pembelian::create($params);
        $product = product::find($pembelian->product_id);
        $data['stok'] = $product->stok + $pembelian->total_barang;
        $data['harga_beli'] = $pembelian->total_biaya / $pembelian->total_barang;
        $data['harga_jual'] = $data['harga_beli'] + ($data['harga_beli'] * 20 / 100);
        $product->update($data);

        return $this->responseOk($pembelian->load('supplier', 'product'), 201, 'successfully added pembelian');
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
            'supplier_id' => 'integer',
            'product_id' => 'integer',
            'total_barang' => 'integer',
            'total_biaya' => 'integer',
        ]);

        if ($validator->fails()) {
            return $this->responseError(422, $validator->errors(), 'failed to update pembelian');
        }

        $pembelian = Pembelian::find($id);
        $total_barangOld = $pembelian->total_barang;
        $product_idOld = $pembelian->product_id;
        $productOld = product::find($product_idOld);
        $stokOld = $productOld->stok;

        $params = [
            'supplier_id' => $request->supplier_id ?? $pembelian->supplier_id,
            'product_id' => $request->product_id ?? $pembelian->product_id,
            'total_barang' => $request->total_barang ?? $pembelian->total_barang,
            'total_biaya' => $request->total_biaya ?? $pembelian->total_biaya,
        ];

        if ($request->total_barang > $stokOld) {
            return $this->responseError('failed to update pembelian because the product has been sold');
        }

        $pembelian->update($params);
        $product = product::find($pembelian->product_id);

        if ($product_idOld !== $pembelian->product_id) {
            $dataOld['stok'] = $productOld->stok - $total_barangOld;
            $productOld->update($dataOld);

            $data['stok'] =  $product->stok + $pembelian->total_barang;
            $data['harga_beli'] = $pembelian->total_biaya / $pembelian->total_barang;
            $data['harga_jual'] = $data['harga_beli'] + ($data['harga_beli'] * 20 / 100);
            $product->update($data);
        } else {
            $data['stok'] = $product->stok - $total_barangOld + $pembelian->total_barang;
            $data['harga_beli'] = $pembelian->total_biaya / $pembelian->total_barang;
            $data['harga_jual'] = $data['harga_beli'] + ($data['harga_beli'] * 20 / 100);
            $product->update($data);
        }

        return $this->responseOk($pembelian->load('supplier', 'product'), 200, 'successfully updated pembelian');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pembelian = Pembelian::find($id);
        $product = product::find($pembelian->product_id);
        if ($product->stok < $pembelian->total_barang) {
            return $this->responseError('failed to delete pembelian because the product has been sold');
        } else {
            $data['stok'] = $product->stok - $pembelian->total_barang;
            $product->update($data);
            $pembelian->delete();
        }

        return $this->responseOk(null, 200, 'successfully deleted pembelian');
    }
}
