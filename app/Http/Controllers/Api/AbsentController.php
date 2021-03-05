<?php

namespace App\Http\Controllers\Api;

use App\Absent;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\User;
use Carbon\Carbon;

class AbsentController extends BaseController
{
    public function absent()
    {
        $first = Carbon::today('Asia/Jakarta')->subMonth(1)->format('Y-m-d');
        $last = Carbon::today('Asia/Jakarta')->format('Y-m-d');
        $absents = Absent::whereDate('created_at', '>=', $first)
            ->whereDate('created_at', '<=', $last)
            ->get();


        foreach ($absents->unique('created_at') as $a) {
            $response[] = [
                'user_id' => $a->user->id,
                'name' => $a->user->name,
                'hadir' => $a->created_at,
            ];
        }

        return $this->responseOk($response, 200, 'showing rekap absent harian');
    }
}
