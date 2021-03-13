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
                                <h4 class="text-center">Kategori</h4>
                            </div>
                        </div>
                    </div>

                    <div class="widget-content widget-content-area text-center">

                        <div class="simple--counter-container">

                            <div class="counter-container">
                                <div class="">
                                    <h1 class="s-counter1 s-counter">{{ $total_category }}</h1>
                                </div>
                                <p class="s-counter-text">Total Kategori</p>
                            </div>
                        </div>

                        @role('admin|staff')
                        <form action="{{ route('kategori.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mb-4">
                                <label for="name">Nama Kategori</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="{{ old('name') }}">
                            </div>
                            <button type="submit" class="btn btn-outline-primary btn-rounded mb-2">Add Catgeory</button>
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
                                <th>Nama Kategori </th>
                                @role('admin|staff')
                                <th class="text-center">Action</th>
                                @endrole
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                @role('admin|staff')
                                <td class="text-center">
                                    <div class="btn-group-vertical" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-outline-secondary btn-rounded btn-sm"
                                            data-toggle="modal" data-target="#update-{{$category->id}}"> Edit </button>
                                        <button type="button" class="btn btn-outline-danger btn-rounded btn-sm"
                                            data-toggle="modal" data-target="#delete-{{$category->id}}"> Delete
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
@include('admin.kategori.modal')
@endsection
