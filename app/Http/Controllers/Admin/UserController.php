<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::get();
        $pimpinan = User::role('pimpinan')->count();
        $staff = User::role('staff')->count();
        $kasir = User::role('kasir')->count();
        $member = User::role('member')->count();
        $roles = Role::pluck('name', 'name')->all();

        $data = [
            'category_name' => 'user',
            'page_name' => 'index_user',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
            'alt_menu' => 0,

        ];
        return view('admin.user.index', compact('users', 'roles', 'pimpinan', 'staff', 'kasir', 'member'))->with($data);
    }

    public function create()
    {
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
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
            'photo' => 'file|image',
            'kode_member' => 'integer',
            'telepon' => 'string',
            'umur' => 'integer',
            'alamat' => 'string',
            'role' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withToastError($validator->messages()->all()[0])->withInput();
        }

        if ($request->photo) {
            $image = base64_encode(file_get_contents(request('photo')));
            $client = new Client();
            $res = $client->request('POST', 'https://freeimage.host/api/1/upload', [
                'form_params' => [
                    'key' => '6d207e02198a847aa98d0a2a901485a5',
                    'action' => 'upload',
                    'source' => $image,
                    'format' => 'json'
                ]
            ]);

            $get = $res->getBody()->getContents();
            $data  = json_decode($get);
            $photo = $data->image->display_url;
        }

        $params = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'photo' => $photo ?? 'https://i.ibb.co/cFZfrYC/administrator.png',
            'kode_member' => $request->kode_member ?? rand(999999999, 999999999999),
            'telepon' => $request->telepon,
            'umur' => $request->umur,
            'alamat' => $request->alamat,
        ];

        $user = User::create($params);
        $user->assignRole(request('role'));

        return back()->withToastSuccess('successfully added user');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'email' => 'email',
            'password' => 'string',
            'photo' => 'file|image',
            'kode_member' => 'integer',
            'telepon' => 'string',
            'umur' => 'integer',
            'alamat' => 'string',
            'role' => 'string'
        ]);

        if ($validator->fails()) {
            return back()->withToastError($validator->messages()->all()[0])->withInput();
        }

        if ($request->photo) {
            $image = base64_encode(file_get_contents(request('photo')));
            $client = new Client();
            $res = $client->request('POST', 'https://freeimage.host/api/1/upload', [
                'form_params' => [
                    'key' => '6d207e02198a847aa98d0a2a901485a5',
                    'action' => 'upload',
                    'source' => $image,
                    'format' => 'json'
                ]
            ]);

            $get = $res->getBody()->getContents();
            $data  = json_decode($get);
            $photo = $data->image->display_url;
        }

        $user = User::find($id);
        $role = Role::pluck('name', 'name')->all();
        $userrole = $user->role;

        $params = [
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
            'password' => bcrypt($request->password) ?? $user->password,
            'photo' => $photo ?? $user->photo,
            'kode_member' => $request->kode_member ?? $user->kode_member,
            'telepon' => $request->telepon ?? $user->telepon,
            'umur' => $request->umur ?? $user->umur,
            'alamat' => $request->alamat ?? $user->alamat,
        ];

        $user->update($params);
        $user->syncRoles(request('role')) ?? $user->getRoleNames();

        return back()->withToastSuccess('successfully updated user');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        // dd($user);
        $user->delete();

        return back()->withToastSuccess('successfully deleted user');
    }
}
