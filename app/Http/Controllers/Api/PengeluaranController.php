<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PengeluaranController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pengeluaran = Pengeluaran::latest()->get();

        if ($pengeluaran == []) {
            return $this->responseError('there is no pengeluaran yet');
        }else {
            return $this->responseOk($pengeluaran);
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
            'jenis_pengeluaran' => 'required|string',
            'nominal' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->responseError(422, $validator->errors(), 'failed to add pengeluaran');
        }

        $params = [
            'jenis_pengeluaran' => $request->jenis_pengeluaran,
            'nominal' => $request->nominal,
        ];

        $pengeluaran = Pengeluaran::create($params);
        return $this->responseOk($pengeluaran, 201, 'successfully added pengeluaran');
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
            return $this->responseError(422, $validator->errors(), 'failed to update pengeluaran');
        }

        $pengeluaran = Pengeluaran::find($id);

        $params = [
            'jenis_pengeluaran' => $request->jenis_pengeluaran ?? $pengeluaran->jenis_pengeluaran,
            'nominal' => $request->nominal ?? $pengeluaran->nominal,
        ];

        $pengeluaran->update($params);
        return $this->responseOk($pengeluaran, 200, 'successfully updated pengeluaran');
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

        return $this->responseOk(null, 200, 'successfully deleted pengeluaran');
    }
}
