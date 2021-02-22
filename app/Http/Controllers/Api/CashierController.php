<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CashierController extends BaseController
{
    public function index()
    {
        $user = User::role('kasir')->get();
        $user->load('roles');
        if (empty($user)) {
            return $this->responseError(403, 'cashier empty');
        }
        return $this->responseOk($user);
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
            'photo' => 'file|image',
            'umur' => 'required|integer',
            'alamat' => 'required|string',
            'password' => 'required|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->responseError(422, $validator->errors(), 'failed to add cashier');
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
            'umur' => $request->umur,
            'alamat' => $request->alamat,
            'password' => bcrypt($request->password),
        ];
        $params['photo'] = $photo ?? 'https://i.ibb.co/cFZfrYC/administrator.png';

        $user = User::create($params);
        $user->assignRole('cashier');

        return $this->responseOk($user, 201, 'successfully added cashier');
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
        if ($user->hasRole('kasir')) {
            return $this->responseOk($user);
        } else {
            return $this->responseError('there is no cashier with this ID');
        }
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
            'photo' => 'file|image',
            'umur' => 'integer',
            'alamat' => 'string',
            'password' => 'confirmed',
        ]);

        if ($validator->fails()) {
            return $this->responseError(422, $validator->errors(), 'failed to update cashier');
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

        $params = [
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
            'password' => bcrypt($request->password) ?? $user->password,
            'photo' => $photo ?? $user->photo,
            'umur' => $request->umur ?? $user->umur,
            'alamat' => $request->alamat ?? $user->alamat,
        ];
        $params['photo'] = $photo ?? $user->photo;

        if ($user->hasRole('kasir')) {
            $user->update($params);
            return $this->responseOk($user, 200, 'successfully updated cashier');
        } else {
            return $this->responseError('this is not a cashier account');
        }
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
        if ($user == []) {
            return $this->responseError('there is no account with this ID');
        } elseif ($user->hasRole('kasir')) {
            $user->delete();
            return $this->responseOk(null, 200, 'successfully deleted cashier');
        } else {
            return $this->responseError('this is not a cashier account');
        }
    }
}
