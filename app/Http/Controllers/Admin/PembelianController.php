<?php

namespace App\Http\Controllers\Admin;

use App\Product;
use App\Http\Controllers\Controller;
use App\Pembelian;
use App\Supplier;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PembelianController extends Controller
{
    public function index()
    {
        $pembelians = Pembelian::get();
        $suppliers = Supplier::get();
        $products = Product::get();

        $total_pembelian = Pembelian::count();
        $total_barang = Pembelian::sum('total_barang');
        $total_biaya = Pembelian::sum('total_biaya');
        $data = [
            'category_name' => 'pembelian',
            'page_name' => 'index_pembelian',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'alt_menu' => 0,
        ];
        // $pembelian->load('barang','supplier');
        // if ($pembelian == []) {
        //     return back()->withToastError('Pembelian belum ada');
        // } else {
            return view('admin.pembelian.index', compact('pembelians','suppliers','products', 'total_pembelian', 'total_barang', 'total_biaya'))->with($data);
        // }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            return back()->withToastError($validator->messages()->all()[0])->withInput();
        }

        $params = [
            'supplier_id' => $request->supplier_id,
            'product_id' => $request->product_id,
            'total_barang' => $request->total_barang,
            'total_biaya' => $request->total_biaya,
        ];

        $pembelian = Pembelian::create($params);
        $product = Product::find($pembelian->product_id);
        $data['stok'] = $product->stok + $pembelian->total_barang;
        $data['harga_beli'] = $pembelian->total_biaya / $pembelian->total_barang;
        $data['harga_jual'] = $data['harga_beli'] + ($data['harga_beli'] * 20 / 100);
        $product->update($data);

        return back()->withToastSuccess('successfully added pembelian');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
            return back()->withToastError($validator->messages()->all()[0])->withInput();
        }

        $pembelian = Pembelian::find($id);
        $total_barangOld = $pembelian->total_barang;
        $product_idOld = $pembelian->product_id;
        $barangOld = Barang::find($product_idOld);
        $stokOld = $barangOld->stok;

        $params = [
            'supplier_id' => $request->supplier_id ?? $pembelian->supplier_id,
            'product_id' => $request->product_id ?? $pembelian->product_id,
            'total_barang' => $request->total_barang ?? $pembelian->total_barang,
            'total_biaya' => $request->total_biaya ?? $pembelian->total_biaya,
        ];

        if ($request->total_barang > $stokOld) {
            return back()->withToastError('failed to update pembelian because the products is already purchased');
        }

        $pembelian->update($params);
        $barang = Barang::find($pembelian->product_id);

        if ($product_idOld !== $pembelian->product_id) {
            $dataOld['stok'] = $barangOld->stok - $total_barangOld;
            $barangOld->update($dataOld);

            $data['stok'] =  $barang->stok + $pembelian->total_barang;
            $data['harga_beli'] = $pembelian->total_biaya / $pembelian->total_barang;
            $data['harga_jual'] = $data['harga_beli'] + ($data['harga_beli'] * 20 / 100);
            $barang->update($data);
        } else {
            $data['stok'] = $barang->stok - $total_barangOld + $pembelian->total_barang;
            $data['harga_beli'] = $pembelian->total_biaya / $pembelian->total_barang;
            $data['harga_jual'] = $data['harga_beli'] + ($data['harga_beli'] * 20 / 100);
            $barang->update($data);
        }

        return back()->withToastSuccess('successfully updated pembelian');
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
        $barang = Barang::find($pembelian->product_id);
        if ($barang->stok < $pembelian->total_barang) {
            return back()->withToastError('failed to delete pembelian because the products is already purchased');
        } else {
            $data['stok'] = $barang->stok - $pembelian->total_barang;
            $barang->update($data);
            $pembelian->delete();
        }

        return back()->withToastSuccess('successfully deleted pembelian');
    }
}
