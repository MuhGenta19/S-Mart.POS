<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $user->getRoleNames();

        if (empty($user)) {
            return $this->responseError('login first', 403);
        }
        return $this->responseOk($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'string',
            'email'   => 'string|email|unique:users',
            'photo' => 'file|image',
            'kode_member' => 'integer|unique:users',
            'telepon' => 'string',
            'umur'   => 'integer',
            'alamat' => 'string'

        ]);

        if ($validator->fails()) {
            return $this->responseError('failed to update profile', 422, $validator->errors());
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

        $user = User::find(Auth::user()->id);
        $params = [
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
            'photo' => $photo ?? $user->photo,
            'kode_member' => $request->kode_member ?? $user->kode_member,
            'telepon' => $request->telepon ?? $user->telepon,
            'umur' => $request->umur ?? $user->umur,
            'alamat' => $request->alamat ?? $user->alamat,
        ];
        $params['photo'] = $photo ?? $user->photo;
        $user->update($params);

        return $this->responseOk($user, 200, 'successfully updated your profile');
    }

    public function change(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed'
        ]);

        if ($validator->fails()) {
            return $this->responseError('failed to change password', 422, $validator->errors());
        }
        $user = User::find(Auth::user()->id);
        $params['password'] = bcrypt($request->password);
        $user->update($params);

        return $this->responseOk($user, 200, 'successfully changed password');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        User::find(Auth::user()->id)->delete();
        return $this->responseOk('successfully deleted your profile');
    }
}
