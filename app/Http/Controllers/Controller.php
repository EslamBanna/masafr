<?php

namespace App\Http\Controllers;

use App\Models\Masafr\Trips;
use App\Models\User\RequestService;
use App\Traits\GeneralTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, GeneralTrait;

    public function getAllTrips(Request $request)
    {
        try {
            $trips = Trips::with('masafr')
                ->with('ways')
                ->with('days')
                ->paginate($request->paginateCount);
            return $this->returnData('data', $trips);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function getAllRequestServices(Request $request)
    {
        try {
            $trips = RequestService::with('user')->paginate($request->paginateCount);
            return $this->returnData('data', $trips);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }
}

