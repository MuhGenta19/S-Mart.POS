<?php

namespace App\Http\Controllers\Api;

use App\Product;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends BaseController
{
    public function search($data)
    {
        $product = Product::where('name', 'like', "%{$data}%")
            ->orWhere('uid', 'like', "%{$data}%")
            ->orWhere('harga_beli', 'like', "%{$data}%")
            ->orWhere('harga_jual', 'like', "%{$data}%")
            ->orWhere('category_id', 'like', "%{$data}%")
            ->orWhere('merek', 'like', "%{$data}%")
            ->orWhere('stok', 'like', "%{$data}%")
            ->orWhere('diskon', 'like', "%{$data}%")
            ->get();

        return $this->responseOk($product);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::orderBy('DESC')->get();
        $product->load('category');

        if (empty($product)) {
            return $this->responseError(403, 'products empty');
        }
        return $this->responseOk($product, 200, 'successfully loaded products');
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
            return $this->responseError(422, $validator->errors(), 'failed to add product');
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

        $product = product::create($params);
        return $this->responseOk($product, 201, 'successfully added product');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = product::find($id);

        return $this->responseOk($product);
    }

    public function uid($uid)
    {
        $product = product::where('uid', $uid)->get();

        return $this->responseOk($product);
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
            return $this->responseError(422, $validator->errors(), 'failed to update product');
        }

        $product = product::find($id);

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
        return $this->responseOk($product, 200, 'successfully updated product');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = product::find($id);
        $product->delete();

        return $this->responseOk(null, 200, 'successfully deleted product');
    }
}
