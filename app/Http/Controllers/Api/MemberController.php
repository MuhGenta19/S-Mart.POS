<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Member;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MemberController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $member = Member::get();
        $member->load('user');
        if (empty($member)) {
            return $this->responseError('member empty', 403);
        }
        return $this->responseOk($member);
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
            'kode_member' => 'integer|unique:users',
            'telepon' => 'required|string',
            'password' => 'required|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->responseError(422, $validator->errors(), 'failed to add member');
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
            'kode_member' => $request->kode_member ?? rand(10000,999999999),
            'telepon' => $request->telepon,
            'password' => bcrypt($request->password),
        ];
        $params['photo'] = $photo ?? 'https://i.ibb.co/cFZfrYC/administrator.png';

        $user = User::create($params);
        $user->assignRole('member');
        $data['saldo'] = 0;
        $data['user_id'] = $user->id;
        $member = Member::create($data);

        return $this->responseOk($user, 201, 'successfully added member');
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
        $user->load('member');
        if ($user->hasRole('member')) {
            return $this->responseOk($user);
        } else {
            return $this->responseError('there is no member with this ID');
        }
    }

    public function kodeMember($kode_member)
    {
        $user = User::role('member')->where('kode_member', $kode_member)->get();
        if ($user == []) {
            return $this->responseError('there is no member with this member code');
        } else {
            return $this->responseOk($user);
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
            'kode_member' => 'integer|unique:users',
            'telepon' => 'string',
            'password' => 'confirmed',
        ]);

        if ($validator->fails()) {
            return $this->responseError(422, $validator->errors(), 'failed to update member');
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
            'kode_member' => $request->kode_member ?? $user->kode_member,
            'telepon' => $request->telepon ?? $user->telepon,
        ];
        $params['photo'] = $photo ?? $user->photo;

        if ($user->hasRole('member')) {
            $user->update($params);
            return $this->responseOk($user, 200, 'successfully updated member');
        } else {
            return $this->responseError('this is not a member account');
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
        } elseif ($user->hasRole('member')) {
            $user->delete();
            return $this->responseOk(null, 200, 'successfully deleted member');
        } else {
            return $this->responseError('this is not a member account');
        }
    }

    public function saldo($id)
    {
        $member = Member::where('user_id', $id)->get();
        return $this->responseOk($member);
    }

    public function topup(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            // 'user_id' => 'required|integer',
            'saldo' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->responseError('failed to add saldo', 422, $validator->errors());
        }
        $member = Member::where('user_id', $id)->first();

        $params['saldo'] = $request->saldo + $member->saldo;

        $member->update($params);
        return $this->responseOk($member->get(), 200, 'topup successful');
    }
}
