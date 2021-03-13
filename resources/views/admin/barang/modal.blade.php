@foreach ($products as $product)
<div id="update-{{$product->id}}" class="modal animated zoomInUp custo-zoomInUp" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="{{ route('barang.update', $product) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Edit Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-4">
                        <label for="name">Nama Produk</label>
                        <input type="text" name="name" class="form-control" id="name" value="{{ $product->name ?? old('name') }}">
                    </div>
                    <div class="form-group mb-4">
                        <label for="uid">UID</label>
                        <input type="number" name="uid" class="form-control" id="uid" value="{{ $product->uid ?? old('uid') }}">
                    </div>
                    <div class="form-row mb-4">
                        <div class="form-group col-md-6">
                            <label for="harga_beli">Harga Beli</label>
                            <input type="number" name="harga_beli" class="form-control" id="harga_beli"
                                value="{{ $product->harga_beli ?? old('harga_beli') }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="harga_jual">Harga Jual</label>
                            <input type="number" name="harga_jual" class="form-control" id="harga_jual"
                                value="{{ $product->harga_jual ?? old('harga_jual') }}">
                        </div>
                    </div>
                    <div class="form-row mb-4">
                        <div class="form-group col-md-6">
                            <label for="merk">Brand</label>
                            <input type="text" name="merk" class="form-control" id="merk"
                                value="{{ $product->merk ?? old('merk') }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="category_id">Kategori</label>
                            <select id="category_id" name="category_id" class="form-control">
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row mb-4">
                        <div class="form-group col-md-6">
                            <label for="stok">Stok</label>
                            <input type="number" name="stok" class="form-control" id="stok"
                                value="{{ $product->stok ?? old('stok') }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="diskon">Discount</label>
                            <input type="number" name="diskon" class="form-control" id="diskon"
                                value="{{ $product->diskon ?? old('diskon') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                    <button type="submit" class="btn btn-primary">Done</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal modal-notification animated zoomInUp custo-zoomInUp" id="delete-{{$product->id}}" tabindex="-1"
    role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" id="deleteLabel">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="icon-content">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-bell">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                </div>
                <p class="modal-text">Are you sure you want to delete this item?</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Nope</button>
                <form method="POST" action="{{ route('barang.destroy', $product) }}">
                    @csrf
                    @method("DELETE")
                    <button type="submit" class="btn btn-primary">Yeah</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
