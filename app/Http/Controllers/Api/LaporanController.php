<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Pembelian;
use App\Pengeluaran;
use App\Penjualan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends BaseController
{
    public function index(Request $request)
    {
        $first = Carbon::today('Asia/Jakarta')->subMonth(1)->format('Y-m-d');
        $last = Carbon::today('Asia/Jakarta')->format('Y-m-d');

        if (request('first') && request('last')) {
            $first = request('first');
            $last = request('last');
        }

        $no = 0;
        $array = array();
        $pendapatan = 0;
        $total_pembelian = 0;
        $total_penjualan = 0;
        $total_pengeluaran = 0;
        $total_pendapatan = 0;
        while (strtotime($first) <= strtotime($last)) {
            $tanggal = $first;
            $first = date('Y-m-d', strtotime("+1 day", strtotime($first)));

            $pembelian  = Pembelian::where('created_at', 'LIKE', "$tanggal%")->orderBy('id', 'DESC')->sum('total_biaya');
            $dibayar = Penjualan::where('created_at', 'LIKE', "$tanggal%")->orderBy('id', 'DESC')->sum('dibayar');
            $kembalian = Penjualan::where('created_at', 'LIKE', "$tanggal%")->orderBy('id', 'DESC')->sum('kembalian');
            $pengeluaran = Pengeluaran::where('created_at', 'LIKE', "$tanggal%")->orderBy('id', 'DESC')->sum('nominal');
            $penjualan = $dibayar - $kembalian;
            $pendapatan = $penjualan - $pembelian  - $pengeluaran;

            $total_pembelian += $pembelian;
            $total_penjualan += $penjualan;
            $total_pengeluaran += $pengeluaran;
            $total_pendapatan += $pendapatan;

            $no ++;
            $array[] = [
                'no' => $no,
                'tanggal' =>  $tanggal,
                'penjualan' => $penjualan,
                'pembelian' =>$pembelian ,
                'pengeluaran' => $pengeluaran,
                'pendapatan' => $pendapatan
            ];
        }

        $response = [
            'total_pembelian' => $total_pembelian,
            'total_penjualan' => $total_penjualan,
            'total_pengeluaran' => $total_pengeluaran,
            'total_pendapatan' => $total_pendapatan,
            'data' => $array,
        ];

        return $this->responseOk($response, 200, 'showing a financial reports');
    }

}
