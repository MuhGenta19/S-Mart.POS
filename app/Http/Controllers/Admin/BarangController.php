<?php

namespace App\Http\Controllers\Admin;

use App\Product;
use App\Http\Controllers\Controller;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        $categories = Category::get();

        $total_barang = Product::count();
        $total_stok = Product::sum('stok');
        $total_harga_jual = Product::sum('harga_jual');
        $total_harga_beli = Product::sum('harga_beli');
        $data = [
            'category_name' => 'barang',
            'page_name' => 'index_barang',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'alt_menu' => 0,

        ];
        return view('admin.barang.index', compact('products', 'categories', 'total_barang', 'total_stok', 'total_harga_jual', 'total_harga_beli'))->with($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'uid' => 'integer|unique:products',
            'harga_beli' => 'required|integer',
            'harga_jual' => 'required|integer',
            'category_id' => 'required|integer',
            'merek' => 'required|string',
            'stok' => 'required|integer',
            'diskon' => 'integer'
        ]);

        if ($validator->fails()) {
            return back()->withToastError($validator->messages()->all()[0])->withInput();
        }

        $params = [
            'name' => $request->name,
            'uid' => $request->uid ?? rand(1000, 9999999999),
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'category_id' => $request->category_id,
            'merek' => $request->merek,
            'stok' => $request->stok,
            'diskon' => $request->diskon ?? 0,
        ];

        $product = Product::create($params);

         return back()->withToastSuccess('successfully added product');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'uid' => 'integer',
            'harga_beli' => 'integer',
            'harga_jual' => 'integer',
            'category_id' => 'integer',
            'merek' => 'string',
            'stok' => 'integer',
            'diskon' => 'integer'
        ]);

        if ($validator->fails()) {
            return back()->withToastError($validator->messages()->all()[0])->withInput();
        }

        $product = Product::find($id);

        $params = [
            'name' => $request->name ?? $product->name,
            'uid' => $request->uid ?? $product->uid,
            'harga_beli' => $request->harga_beli ?? $product->harga_beli,
            'harga_jual' => $request->harga_jual ?? $product->harga_jual,
            'category_id' => $request->category_id ?? $product->category_id,
            'merek' => $request->merek ?? $product->merek,
            'stok' => $request->stok ?? $product->stok,
            'diskon' => $request->diskon ?? $product->diskon,
        ];

        $product->update($params);

         return back()->withToastSuccess('successfully updated product');

    }

    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();

         return back()->withToastSuccess('successfully deleted product');
    }

}
