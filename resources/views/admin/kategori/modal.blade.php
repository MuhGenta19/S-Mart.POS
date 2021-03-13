@foreach ($categories as $category)
<div id="update-{{$category->id}}" class="modal animated zoomInUp custo-zoomInUp" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="{{ route('kategori.update', $category) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kategori</h5>
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
                        <label for="name">Nama Kategori</label>
                        <input type="text" name="name" class="form-control" id="name"
                            value="{{ $category->name ?? old('name') }}">
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

<div class="modal modal-notification animated zoomInUp custo-zoomInUp" id="delete-{{$category->id}}" tabindex="-1"
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
                <p class="modal-text">Are you sure you want to delete this category?</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Nope</button>
                <form method="POST" action="{{ route('kategori.destroy', $category) }}">
                    @csrf
                    @method("DELETE")
                    <button type="submit" class="btn btn-primary">Yeah</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
