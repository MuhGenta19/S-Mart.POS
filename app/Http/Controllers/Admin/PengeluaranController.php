<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PengeluaranController extends Controller
{
    public function index()
    {
        $pengeluarans = Pengeluaran::get();
        $nominal = Pengeluaran::sum('nominal');
        $jumlah = Pengeluaran::count();
        // dd($nominal);
        $data = [
            'category_name' => 'pengeluaran',
            'page_name' => 'index_pengeluaran',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'alt_menu' => 0,
        ];

        if ($pengeluarans == []) {
            return back()->withToastError('there is no pengeluaran yet');
        }
        return view('admin.pengeluaran.index', compact('pengeluarans','nominal', 'jumlah'))->with($data);
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
            'jenis_pengeluaran' => 'required|string',
            'nominal' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return back()->withToastError($validator->messages()->all()[0])->withInput();
        }

        $params = [
            'jenis_pengeluaran' => $request->jenis_pengeluaran,
            'nominal' => $request->nominal,
        ];

        $pengeluaran = Pengeluaran::create($params);
        return back()->withToastSuccess('successfully added pengeluaran');
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
            'jenis_pengeluaran' => 'string',
            'nominal' => 'integer',
        ]);

        if ($validator->fails()) {
            return back()->withToastError($validator->messages()->all()[0])->withInput();
        }

        $pengeluaran = Pengeluaran::find($id);

        $params = [
            'jenis_pengeluaran' => $request->jenis_pengeluaran ?? $pengeluaran->jenis_pengeluaran,
            'nominal' => $request->nominal ?? $pengeluaran->nominal,
        ];

        $pengeluaran->update($params);
        return back()->withToastSuccess('successfully updated pengeluaran');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pengeluaran = Pengeluaran::find($id);
        $pengeluaran->delete();

        return back()->withToastSuccess('successfully deleted pengeluaran');
    }
}
