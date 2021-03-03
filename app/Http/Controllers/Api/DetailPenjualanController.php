<?php

namespace App\Http\Controllers\Api;

use App\Product;
use App\DetailPenjualan;
use App\Http\Controllers\Api\BaseController;
use App\Member;
use App\Penjualan;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DetailPenjualanController extends BaseController
{
    public function request()
    {
        $detailPenjualan = DetailPenjualan::with('penjualan')->where('status', 0)->get();
        $jumlah_barang = 0;
        $array = array();
        foreach ($detailPenjualan as $data) {
            $jumlah_barang += $data->penjualan->total_harga;
            $array[] = [
                'id' => $data->id,
                'uid' => $data->penjualan->product->uid,
                'name' => $data->penjualan->product->name,
                'jumlah_barang' => $data->penjualan->jumlah_barang,
                'total_harga' => $data->penjualan->total_harga,
                'diskon' => $data->penjualan->product->diskon
            ];
        }

        $response = [
            'keranjang' => $array,
            'total_semua' => $jumlah_barang
        ];
        // $array['total_semua'] = $jumlah_barang;

        if ($array == []) {
            return $this->responseError('request product data is empty');
        }
        return $this->responseOk($array, 200, 'requested products');
    }

    public function dibeli()
    {
        $detailPenjualan = DetailPenjualan::with('penjualan')->where('status', 1)->get();
        $array = array();
        foreach ($detailPenjualan as $data) {
            $array[] = [
                'id' => $data->id,
                'uid' => $data->penjualan->product->uid,
                'name' => $data->penjualan->product->name,
                'jumlah_barang' => $data->penjualan->jumlah_barang,
                'total_harga' => $data->penjualan->total_harga

            ];
        }

        if ($array == []) {
            return $this->responseError('there is no products data that have been purchased or confirmed');
        }
        return $this->responseOk($array, 200, 'products data that have been purchased or confirmed');
    }

    public function confirm(Request $request)
    {
        $getdetailPenjualan = DetailPenjualan::where('status', 0)->get()->load('penjualan');

        $total_item = 0;
        $total_barang = 0;
        $total_harga = 0;
        $total_diskon = 0;
        $dibayar = request('dibayar');
        $array = array();
        foreach ($getdetailPenjualan as $data) {
            $total_item += 1;
            $total_barang += $data->penjualan->jumlah_barang;
            $total_harga += $data->penjualan->total_harga;
            $total_diskon += $data->penjualan->product->diskon;

            $array[] = [
                'id' => $data->id,
                'uid' => $data->penjualan->product->uid,
                'name' => $data->penjualan->product->name,
                'jumlah' => $data->penjualan->jumlah_barang,
                'harga' => $data->penjualan->total_harga,
                'diskon' => $data->penjualan->product->diskon,
                'tanggal' => $data->penjualan->created_at,

            ];
        }
        $user = User::find(Auth::user()->id);
        $member = Member::where('user_id', request('member_id'))->first();

        if ($array == []) {
            return $this->responseError('requested products data is empty');
        } elseif ($request->has('member_id') && $request->has('dibayar')) {
            return $this->responseError('do you want to directly pay or with saldo member?');
        } elseif (request('member_id') == null && request('dibayar') == null) {
            return $this->responseError('there is no payment');
        } elseif ($request->has('dibayar') && request('dibayar') < $total_harga) {
            $kurang = $total_harga - request('dibayar');
            return $this->responseError('your payment is ' . $kurang . ' less');
        } elseif ($request->has('member_id') && $member == null) {
            return $this->responseError('member with ID ' . request('member_id') . ' not found');
        } elseif ($request->has('member_id') && $member->saldo < ($total_harga - $total_diskon)) {
            $kurang = ($total_harga - $total_diskon) - $member->saldo;
            return $this->responseError('your payment is ' . $kurang . ' less');
        } elseif ($request->has('member_id')) {
            foreach ($getdetailPenjualan as $data) {
                $detailpenjualan = DetailPenjualan::find($data->id);
                $penjualan = Penjualan::find($data->penjualan_id);
                $product = Product::find($penjualan->product_id);
                $member = Member::where('user_id', request('member_id'))->first();

                $bayarpenjualan['dibayar'] = $penjualan->total_harga - ($product->diskon * $penjualan->jumlah_barang);
                $bayarpenjualan['member_id'] = request('member_id');
                $bayarpenjualan['user_id'] = $user->id;
                $penjualan->update($bayarpenjualan);

                $saldomember['saldo'] = $member->saldo - $bayarpenjualan['dibayar'];
                $member->update($saldomember);

                $stokbarang['stok'] = $product->stok - $penjualan->jumlah_barang;
                $product->update($stokbarang);

                $statusdetailpenjualan['status'] = 1;
                $detailpenjualan->update($statusdetailpenjualan);

                $response = [
                    'total_item' => $total_item,
                    'total_barang' => $total_barang,
                    'total_harga' => $total_harga,
                    'total_diskon' => $total_diskon,
                    'dibayar' => $total_harga - $total_diskon,
                    'kembalian' => 0,
                    'member' => $member->user->name,
                    'kasir' => $user->name,
                    'data' => $array,
                ];
            }
        } else {
            foreach ($getdetailPenjualan as $data) {
                $detailpenjualan = DetailPenjualan::find($data->id);
                $penjualan = Penjualan::find($data->penjualan_id);
                $product = Product::find($penjualan->product_id);

                $bayarpenjualan['dibayar'] = $dibayar ;
                $bayarpenjualan['kembalian'] = $bayarpenjualan['dibayar'] - $data->penjualan->total_harga;
                $bayarpenjualan['user_id'] = $user->id;
                $penjualan->update($bayarpenjualan);
                $dibayar = $penjualan->kembalian;

                $stokbarang['stok'] = $product->stok - $penjualan->jumlah_barang;
                $product->update($stokbarang);

                $statusdetailpenjualan['status'] = 1;
                $detailpenjualan->update($statusdetailpenjualan);

                $response = [
                    'total_item' => $total_item,
                    'total_barang' => $total_barang,
                    'total_harga' => $total_harga,
                    'total_diskon' => 0,
                    'dibayar' => request('dibayar'),
                    'kembalian' => request('dibayar') - $total_harga,
                    'member' => null,
                    'kasir' => $user->name,
                    'data' => $array,
                ];
            }
        }
        return $this->responseOk($response, 200, 'thank you, your purchase is successful');
    }
}
