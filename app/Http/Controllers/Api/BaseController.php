<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function responseOk($result, $code = 200, $message = 'success')
    {
        $response = [
            'code' => $code,
            'message' => $message,
            'data' => $result,
        ];


        return response()->json($response, $code);
    }

    public function responseError($errorDetails = [], $code = 422, $error = 'fail' )
    {
        $response = [
            'code' => $code,
            'error' => $error,
        ];

        if (!empty($errorDetails)) {
            $response['errorDetails'] = $errorDetails;
        }

        return response()->json($response, $code);
    }
}
