<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supplier = Supplier::get();

        if (empty($supplier)) {
            return $this->responseError('Suppliers does not exist', 403);
        }
        return $this->responseOk($supplier);
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
            'alamat' => 'required',
            'telepon' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->responseError(422, $validator->errors(), 'failed to add supplier');
        }

        $params = [
            'name' => $request->name,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
        ];

        $supplier = Supplier::create($params);
        return $this->responseOk(201, 'successfully added supplier' , $supplier);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $supplier = Supplier::find($id);

        return $this->responseOk($supplier);
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
            'telepon' => 'string',
        ]);

        if ($validator->fails()) {
            return $this->responseError(422, $validator->errors(), 'failed to added supplier');
        }

        $supplier = Supplier::find($id);

        $params = [
            'name' => $request->name ?? $supplier->name,
            'alamat' => $request->alamat ?? $supplier->alamat,
            'telepon' => $request->telepon ?? $supplier->telepon,
        ];

        $supplier->update($params);
        return $this->responseOk(200, 'successfully updated supplier', $supplier);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $supplier = Supplier::find($id);
        $supplier->delete();

        return $this->responseOk(200, 'successfully deleted supplier', null);
    }
}
