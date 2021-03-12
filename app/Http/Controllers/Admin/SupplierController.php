<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::get();
        $total_supplier = Supplier::count();
        $data = [
            'category_name' => 'supplier',
            'page_name' => 'index_supplier',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'alt_menu' => 0,

        ];
        return view('admin.supplier.index', compact('suppliers', 'total_supplier'))->with($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'alamat' => 'required',
            'telepon' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withToastError($validator->messages()->all()[0])->withInput();
        }

        $params = [
            'name' => $request->name,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
        ];

        Supplier::create($params);

        return back()->withToastSuccess('successfully added supplier');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'telepon' => 'string',
        ]);

        if ($validator->fails()) {
            return back()->withToastError($validator->messages()->all()[0])->withInput();
        }

        $supplier = Supplier::find($id);
        $params = [
            'name' => $request->name ?? $supplier->name,
            'alamat' => $request->alamat ?? $supplier->alamat,
            'telepon' => $request->telepon ?? $supplier->telepon,
        ];

        $supplier->update($params);
        return back()->withToastSuccess('successfully updated supplier');
    }

    public function destroy($id)
    {
        $supplier = Supplier::find($id);
        $supplier->delete();

        return back()->withToastSuccess('successfully deleted supplier');
    }
}
