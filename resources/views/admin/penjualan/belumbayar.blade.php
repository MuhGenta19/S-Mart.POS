@extends('layouts.app')

@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <div id="counterBasic" class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4 class="text-center">Keranjang</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area text-center">
                        <div class="simple--counter-container">
                            <div class="counter-container">
                                <div class="">
                                    <h1 class="s-counter1 s-counter">{{ $total_keranjang }}</h1>
                                </div>
                                <p class="s-counter-text">Total Produk</p>
                            </div>
                            <div class="counter-container">
                                <div class="">
                                    <h1 class="s-counter2 s-counter">{{ $total_barang }}</h1>
                                </div>
                                <p class="s-counter-text">Total Kuantitas</p>
                            </div>
                            <div class="counter-container">
                                <div class="">
                                    <h1 class="s-counter3 s-counter">{{ $total_harga }}</h1>
                                </div>
                                <p class="s-counter-text">Total Harga</p>
                            </div>
                            <div class="counter-container">
                                <div class="">
                                    <h1 class="s-counter4 s-counter">{{ $total_diskon }}</h1>
                                </div>
                                <p class="s-counter-text">Total Discount</p>
                            </div>
                        </div>
                        @role('kasir|admin')
                        <button type="button" class="btn btn-outline-primary btn-rounded mb-2" data-toggle="modal"
                            data-target="#confirmbayar">Confirm Payment With Cash</button>
                        <button type="button" class="btn btn-outline-primary btn-rounded mb-2" data-toggle="modal"
                            data-target="#confirmmember">Confirm Payment With Balance</button>
                        @endrole
                    </div>
                    @role('kasir|admin')
                    <div class="widget-content widget-content-area text-center">
                        <form action="{{ route('penjualan.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-row mb-4">
                                <div class="form-group col-md-6">
                                    <label for="product_id">Produk</label>
                                    <select id="product_id" name="product_id" class="form-control">
                                        @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="jumlah_barang">Kuantitas</label>
                                    <input type="number" name="jumlah_barang" class="form-control" id="jumlah_barang"
                                        value="{{ old('jumlah_barang') }}">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-outline-primary btn-rounded mb-2">Add To Cart</button>
                        </form>
                    </div>
                    @endrole

                </div>
            </div>

            <div class="widget-content widget-content-area br-6">
                <div class="table-responsive mb-4 mt-4">
                    <table id="html5-extension" class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Produk</th>
                                <th>Kuantitas</th>
                                <th>Total Harga</th>
                                <th>Tanggal</th>
                                @role('admin|kasir')
                                <th class="text-center">Action</th>
                                @endrole
                            </tr>
                        </thead>
                        <tbody>
                            <p hidden>{{ $no = 1 }}</p>
                            @foreach ($penjualans as $penjualan)
                            <tr>
                                <td>{{ $no ++ }}</td>
                                <td>{{ $penjualan->product->name }}</td>
                                <td>{{ $penjualan->jumlah_barang }}</td>
                                <td>{{ $penjualan->total_harga }}</td>
                                <td>{{ $penjualan->created_at }}</td>
                                @role('admin|kasir')
                                <td class="text-center">
                                    <div class="btn-group-vertical" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-outline-secondary btn-rounded btn-sm"
                                            data-toggle="modal" data-target="#update-{{$penjualan->id}}"> Edit </button>
                                        <button type="button" class="btn btn-outline-danger btn-rounded btn-sm"
                                            data-toggle="modal" data-target="#delete-{{$penjualan->id}}"> Delete
                                        </button>
                                    </div>
                                </td>
                                @endrole
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.penjualan.modal')
@endsection
