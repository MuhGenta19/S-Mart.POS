<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Member;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends BaseController
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseError($validator->errors());
        }

        if (Auth::attempt([
            'email' => request('email'),
            'password' => request('password'),
        ], $request->has('remember_me') ? true : false)) {
            $user = Auth::user();
            $user->load('roles');
            $response = [
                'token' => $user->createToken('pos')->accessToken,
                'user' => $user,
            ];

            return $this->responseOk($response);
        } else {
            return $this->responseError('Invalid Credentials');
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'kode_member' => 'integer',
            'telepon' => 'required|string',
            'password' => 'required|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->responseError($validator->errors());
        }

        $params = [
            'name' => $request->name,
            'email' => $request->email,
            'kode_member' => $request->kode_member ?? rand(10000, 999999999),
            'telepon' => $request->telepon,
            'password' => bcrypt($request->password),
        ];

        if ($user = User::create($params)) {
            $user->assignRole('member');
            $token = $user->createToken('pos')->accessToken;

            $data['user_id'] = $user->id;
            $data['saldo'] = 0;
            $member = Member::create($data);

            $response = [
                'token' => $token,
                'user' => $user,
            ];
            return $this->responseOk($response, 201, 'successfully registered');
        } else {
            return $this->responseError('failed to register');
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return $this->responseOk('logged out');
    }

    public function index()
    {
        $user = User::get();

        if (empty($user)) {
            return $this->responseError(403, 'users empty');
        }
        return $this->responseOk($user, 200, 'successfully loaded users data');
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
            return $this->responseError(422, $validator->errors(), 'failed to add user');
        }

        $image = base64_encode(file_get_contents(request('photo')));
        $client = new Client();
        $res = $client->request('POST', 'https://api.imgbb.com/1/upload', [
            'form_params' => [
                'key' => 'b07a227db8a98165791eda2376549b1c',
                'action' => 'upload',
                'source' => $image,
                'format' => 'json'
            ]
        ]);

        $get = $res->getBody()->getContents();
        $data  = json_decode($get);
        $photo = $data->image->display_url ?? null;

        $params = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'photo' => $photo,
            'kode_member' => $request->kode_member ?? rand(10000, 999999999),
            'telepon' => $request->telepon,
            'umur' => $request->umur,
            'alamat' => $request->alamat,
        ];

        $user = User::create($params);
        $user->assignRole(request('role'));

        return $this->responseOk($user, 201, 'successfully added user');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        return $this->responseOk($user);
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
            'name' => 'string',
            'email' => 'email|unique:users',
            'password' => 'string',
            'photo' => 'file|image',
            'kode_member' => 'integer',
            'telepon' => 'string',
            'umur' => 'integer',
            'alamat' => 'string',
            'role' => 'string'
        ]);

        if ($validator->fails()) {
            return $this->responseError(422, $validator->errors(), 'failed to update user');
        }

        $image = base64_encode(file_get_contents(request('photo')));
        $client = new Client();
        $res = $client->request('POST', 'https://api.imgbb.com/1/upload', [
            'form_params' => [
                'key' => 'b07a227db8a98165791eda2376549b1c',
                'action' => 'upload',
                'source' => $image,
                'format' => 'json'
            ]
        ]);

        $get = $res->getBody()->getContents();
        $data  = json_decode($get);

        $user = User::find($id);
        $role = Role::pluck('name', 'name')->all();

        $photo = $data->image->display_url ?? $user->photo;

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
        $user->assignRole(request('role') ?? $user->role);

        return $this->responseOk($user, 200, 'successfully updated user');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        return $this->responseOk(null, 200, 'failed to delete user');
    }
}
