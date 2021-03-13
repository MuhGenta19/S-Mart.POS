<div id="create" class="modal animated zoomInUp custo-zoomInUp" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="{{ route('user.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add User</h5>
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
                    <div class="form-row mb-4">
                        <div class="form-group col-md-6">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" id="email"
                                value="{{ old('email') }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" id="password"
                                value="{{ old('password') }}">
                        </div>
                    </div>
                    <div class="form-row mb-4">
                        <div class="form-group col-md-6">
                            <label for="name">Nama</label>
                            <input type="text" name="name" class="form-control" id="name"
                                value="{{ old('name') }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="telepon">No telepon</label>
                            <input type="number" name="telepon" class="form-control" id="telepon"
                                value="{{ old('telepon') }}">
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label for="alamat">Alamat</label>
                        <input type="text" name="alamat" class="form-control" id="alamat"
                            value="{{ old('alamat') }}">
                    </div>
                    <div class="form-row mb-4">
                        <div class="form-group col-md-6">
                            <label for="umur">Umur</label>
                            <input type="number" name="umur" class="form-control" id="umur"
                                value="{{ old('umur') }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="role">Role</label>
                            <select id="role" name="role" class="form-control">
                                @foreach ($roles as $role)
                                <option>{{ $role }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="custom-file-container" data-upload-id="myFirstImage">
                        <label>Upload Foto <a href="javascript:void(0)" class="custom-file-container__image-clear"
                                title="Clear Image">x</a></label>
                        <label class="custom-file-container__custom-file">
                            <input type="file" name="photo" class="custom-file-container__custom-file__custom-file-input"
                                accept="image/*">
                            <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                            <span class="custom-file-container__custom-file__custom-file-control"></span>
                        </label>
                        <div class="custom-file-container__image-preview"></div>
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

@foreach ($users as $user)
<div id="update-{{$user->id}}" class="modal animated zoomInUp custo-zoomInUp" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="{{ route('user.update', $user) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
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
                    <div class="form-row mb-4">
                        <div class="form-group col-md-6">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" id="email"
                                value="{{$user->email ?? old('email') }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" id="password"
                                value="{{ old('password') }}">
                        </div>
                    </div>
                    <div class="form-row mb-4">
                        <div class="form-group col-md-6">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" id="name"
                                value="{{$user->name ?? old('name') }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="telepon">No Telepon</label>
                            <input type="number" name="telepon" class="form-control" id="telepon"
                                value="{{ $user->telepon ?? old('telepon') }}">
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label for="alamat">Alamat</label>
                        <input type="text" name="alamat" class="form-control" id="alamat"
                            value="{{ $user->alamat ??old('alamat') }}">
                    </div>
                    <div class="form-row mb-4">
                        <div class="form-group col-md-6">
                            <label for="umur">Umur</label>
                            <input type="number" name="umur" class="form-control" id="umur"
                                value="{{ $user->umur ??old('umur') }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="role">Role</label>
                            <select id="role" name="role" class="form-control">
                                @foreach ($roles as $role)
                                <option>{{ $role }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- <div class="custom-file-container" data-upload-id="myFirstImage">
                        <label>Upload Foto <a href="javascript:void(0)" class="custom-file-container__image-clear"
                                title="Clear Image">x</a></label>
                        <label class="custom-file-container__custom-file">
                            <input type="file" name="photo" class="custom-file-container__custom-file__custom-file-input"
                                accept="image/*">
                            <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                            <span class="custom-file-container__custom-file__custom-file-control"></span>
                        </label>
                        <div class="custom-file-container__image-preview"></div>
                    </div> --}}
                    <div class="custom-file mb-4">
                        <input type="file" name="photo" class="custom-file-input" id="customFile">
                        <label class="custom-file-label" for="customFile">Choose file</label>
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

<div class="modal modal-notification animated zoomInUp custo-zoomInUp" id="delete-{{$user->id}}" tabindex="-1"
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
                <p class="modal-text">Are you sure you want to delete this user?</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Nope</button>
                <form method="POST" action="{{ route('user.destroy', $user) }}">
                    @csrf
                    @method("DELETE")
                    <button type="submit" class="btn btn-primary">Yeah</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
