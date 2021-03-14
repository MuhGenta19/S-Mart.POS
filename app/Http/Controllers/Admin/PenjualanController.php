<?php

namespace App\Http\Controllers\Admin;

use App\Product;
use App\DetailPenjualan;
use App\Http\Controllers\Controller;
use App\Member;
use App\Penjualan;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PenjualanController extends Controller
{
    public function dibayar()
    {
        $penjualans = Penjualan::where('dibayar', '>', 0)->get();
        $total_penjualan = Penjualan::where('dibayar', '>', 0)->count();
        $total_barang = Penjualan::sum('jumlah_barang');
        $dibayar = Penjualan::sum('dibayar');
        $kembalian = Penjualan::sum('kembalian');
        $total_harga = $dibayar - $kembalian;
        $products = Product::get();
        $data = [
            'category_name' => 'penjualan',
            'page_name' => 'dibayar',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'alt_menu' => 0,
        ];
        return view('admin.penjualan.dibayar', compact('penjualans', 'products', 'total_penjualan', 'total_barang', 'total_harga'))->with($data);
    }

    public function belumbayar()
    {
        $penjualans = Penjualan::where('dibayar', '=', 0)->get();
        $products = Product::get();
        $members = Member::get();
        $getdetailPenjualan = DetailPenjualan::where('status', 0)->get();
        $detailPenjualan = DetailPenjualan::with('penjualan')->where('status', 0)->get();

        $total_keranjang = 0;
        $total_barang = 0;
        $total_harga = 0;
        $total_diskon = 0;
        foreach ($getdetailPenjualan as $dataGetDetail) {
            $total_keranjang += 1;
            $total_barang += $dataGetDetail->penjualan->jumlah_barang;
            $total_harga += $dataGetDetail->penjualan->total_harga;
            $total_diskon += $dataGetDetail->penjualan->product->diskon ?? 0;
        }

        // $array = array();
        // foreach ($detailPenjualan as $dataDetail) {
        //     $array[] = [
        //         'id' => $dataDetail->id,
        //         'uid' => $dataDetail->penjualan->barang->uid,
        //         'name' => $dataDetail->penjualan->barang->name,
        //         'jumlah_barang' => $dataDetail->penjualan->jumlah_barang,
        //         'total_harga' => $dataDetail->penjualan->total_harga,
        //         'diskon' => $dataDetail->penjualan->barang->diskon,
        //     ];
        // }

            $data = [
            'category_name' => 'penjualan',
            'page_name' => 'belumbayar',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'alt_menu' => 0,
        ];

            return view('admin.penjualan.belumbayar', compact('penjualans','products','total_keranjang','total_barang','total_harga','total_diskon','members'))->with($data);
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
            'product_id' => 'required|integer',
            'jumlah_barang' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return back()->withToastError($validator->messages()->all()[0])->withInput();
        }

        $product = Product::find($request->product_id);
        $params = [
            'product_id' => $request->product_id,
            'jumlah_barang' => $request->jumlah_barang,
            'total_harga' => $product->harga_jual * $request->jumlah_barang,
            'dibayar' => 0,
            'kembalian' => 0,
            'member_id' => null,
            'user_id' => 0
        ];

        if ($request->jumlah_barang > $product->stok) {
            return back()->withToastError($product->stok . " product(s) left");
        }
        $penjualan = Penjualan::create($params);
        $data['penjualan_id'] = $penjualan->id;
        DetailPenjualan::create($data);
        return back()->withToastSuccess('successfully added penjualan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $penjualan = Penjualan::find($id);
        $penjualan->load('product', 'member', 'user');
        return $this->responseOk($penjualan);
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
            'product_id' => 'integer',
            'jumlah_barang' => 'integer',
        ]);
        if ($validator->fails()) {
            return back()->withToastError($validator->messages()->all()[0])->withInput();
        }
        $penjualan = Penjualan::find($id);

        if ($penjualan->dibayar > 0) {
            return back()->withToastError("cannot update or exchange product(s) that have been purchased");
        }
        $product = Barang::find($request->product_id ?? $penjualan->product_id);
        $params = [
            'product_id' => $request->product_id ?? $penjualan->product_id,
            'jumlah_barang' => $request->jumlah_barang ?? $penjualan->jumlah_barang,
            'dibayar' => 0,
            'kembalian' => 0,
            'member_id' => null,
            'user_id' => 0
        ];
        $params['total_harga'] = $product->harga_jual * $params['jumlah_barang'];

        if ($params['jumlah_barang'] > $product->stok) {
            return back()->withToastError($product->stok . " product(s) left");
        }
        $penjualan->update($params);
        return back()->withToastSuccess('successfully updated penjualan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $penjualan =  Penjualan::find($id);
        $detailpenjualan = DetailPenjualan::where('penjualan_id', $penjualan->id);

        if ($penjualan->dibayar > 0) {
            return back()->withToastError("cannot delete or return product(s) that have been purchased");
        }

        $detailpenjualan->delete();
        $penjualan->delete();
        return back()->withToastSuccess('successfully deleted penjualan');
    }

    public function konfirmasi(Request $request)
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
        // dd($array);
        $user = User::find(Auth::user()->id);
        $member = Member::find(request('member_id'));

        if ($array == []) {
            return back()->withToastError('cart empty');
        } elseif ($request->has('member_id') && $request->has('dibayar')) {
            return back()->withToastError('do you want to directly pay or with saldo member?');
        } elseif (request('member_id') == null && request('dibayar') == null) {
            return back()->withToastError('there is no payment');
        } elseif ($request->has('dibayar') && request('dibayar') < $total_harga) {
            $kurang = $total_harga - request('dibayar');
            return back()->withToastError('your payment is ' . $kurang . ' less');
        } elseif ($request->has('member_id') && $member == null) {
            return back()->withToastError('member with ID ' . request('member_id') . ' not found');
        } elseif ($request->has('member_id') && $member->saldo < ($total_harga - $total_diskon)) {
            $kurang = ($total_harga - $total_diskon) - $member->saldo;
            return back()->withToastError('your payment is ' . $kurang . ' less');
        } elseif ($request->has('member_id')) {
            foreach ($getdetailPenjualan as $data) {
                $detailpenjualan = DetailPenjualan::find($data->id);
                $penjualan = Penjualan::find($data->penjualan_id);
                $product = Product::find($penjualan->product_id);
                $member = Member::find(request('member_id'));

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
        return back()->withToastSuccess('thank you, your purchase is successful');
    }
}
