<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function index()
    {
        $categories = Category::get();
        $total_category = Category::count();
        $data = [
            'category_name' => 'kategori',
            'page_name' => 'index_kategori',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'alt_menu' => 0,

        ];
        return view('admin.kategori.index', compact('categories', 'total_category'))->with($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withToastError($validator->messages()->all()[0])->withInput();
        }

        $params = [
            'name' => $request->name,
        ];

        Category::create($params);

       return back()->withToastSuccess('successfully added category');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
        ]);
        if ($validator->fails()) {
            return back()->withToastError($validator->messages()->all()[0])->withInput();
        }

        $category = Category::find($id);
        $params = [
            'name' => $request->name ?? $category->name,
        ];

        $category->update($params);
       return back()->withToastSuccess('successfully updated category');
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        $category->delete();

        return back()->withToastSuccess('successfully deleted category');
    }
}
