@foreach ($penjualans as $penjualan)
<div id="update-{{$penjualan->id}}" class="modal animated zoomInUp custo-zoomInUp" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="{{ route('penjualan.update', $penjualan) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Edit Keranjang</h5>
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
                        <label for="product_id">Produk</label>
                        <select id="product_id" name="product_id" class="form-control">
                            @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label for="jumlah_barang">Kuantitas</label>
                        <input type="number" name="jumlah_barang" class="form-control" id="jumlah_barang" value="{{ $penjualan->jumlah_barang ?? old('jumlah_barang') }}">
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

<div class="modal modal-notification animated zoomInUp custo-zoomInUp" id="delete-{{$penjualan->id}}" tabindex="-1"
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
                <p class="modal-text">Are you sure you want to delete this cart?</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Nope</button>
                <form method="POST" action="{{ route('penjualan.destroy', $penjualan) }}">
                    @csrf
                    @method("DELETE")
                    <button type="submit" class="btn btn-primary">Yeah</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<div id="confirmbayar" class="modal animated zoomInUp custo-zoomInUp" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="{{ route('penjualan.konfirmasi') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi keranjang</h5>
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
                        <label for="dibayar">Bayar</label>
                        <input type="number" name="dibayar" class="form-control" id="dibayar" value="{{ old('dibayar') }}">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Cancel</button>
                    <button type="submit" class="btn btn-primary">Bayar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="confirmmember" class="modal animated zoomInUp custo-zoomInUp" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="{{ route('penjualan.konfirmasi') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi keranjang</h5>
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
                        <label for="member_id">Member</label>
                        <select id="member_id" name="member_id" class="form-control">
                            <option>--pilih</option>
                            @foreach ($members as $member)
                            <option value="{{ $member->id }}">{{ $member->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Cancel</button>
                    <button type="submit" class="btn btn-primary">Bayar pakai saldo member</button>
                </div>
            </form>
        </div>
    </div>
</div>
