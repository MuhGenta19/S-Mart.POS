@extends('layouts.app')

@section('content')

<div class="layout-px-spacing">

    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
            <div id="counterBasic" class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4 class="text-center">Supplier</h4>
                            </div>
                        </div>
                    </div>

                    <div class="widget-content widget-content-area text-center">

                        <div class="simple--counter-container">

                            <div class="counter-container">
                                <div class="">
                                    <h1 class="s-counter1 s-counter">{{ $total_supplier }}</h1>
                                </div>
                                <p class="s-counter-text">Total Supplier</p>
                            </div>
                        </div>

                        @role('admin|staff')
                        <form action="{{ route('supplier.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-row mb-4">
                                <div class="form-group col-md-6">
                                    <label for="name">Nama</label>
                                    <input type="text" name="name" class="form-control" id="name"
                                        value="{{ old('name') }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="telepon">No Telepon</label>
                                    <input type="number" name="telepon" class="form-control" id="telepon"
                                        value="{{ old('telepon') }}">
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label for="alamat">Alamat</label>
                                <input type="text" name="alamat" class="form-control" id="alamat"
                                    value="{{ old('alamat') }}">
                            </div>
                            <button type="submit" class="btn btn-outline-primary btn-rounded mb-2">Add Supplier</button>
                        </form>
                        @endrole
                    </div>
                </div>
            </div>
            <div class="widget-content widget-content-area br-6">
                <div class="table-responsive mb-4 mt-4">
                    <table id="html5-extension" class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>No Telepon</th>
                                @role('admin|staff')
                                <th class="text-center">Action</th>
                                @endrole
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($suppliers as $supplier)
                            <tr>
                                <td>{{ $supplier->id }}</td>
                                <td>{{ $supplier->name }}</td>
                                <td>{{ $supplier->alamat }}</td>
                                <td>{{ $supplier->telepon }}</td>
                                @role('admin|staff')
                                <td class="text-center">
                                    <div class="btn-group-vertical" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-outline-secondary btn-rounded btn-sm"
                                            data-toggle="modal" data-target="#update-{{$supplier->id}}"> Edit </button>
                                        <button type="button" class="btn btn-outline-danger btn-rounded btn-sm"
                                            data-toggle="modal" data-target="#delete-{{$supplier->id}}"> Delete
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
@include('admin.supplier.modal')
@endsection
