<?php

namespace App\Http\Controllers\Masafr;

use App\Http\Controllers\Controller;
use App\Models\Masafr\Masafr;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Validator;
use Auth;
use JWTAuth;

class MasafrController extends Controller
{

    use GeneralTrait;
    public function login(Request $request)
    {
        try {
            $loginType = 'phone';
            $rules = [
                'phone' => 'required|exists:masafr,phone',
                'password' => 'required'
            ];
            if ($request->has('email')) {
                $loginType = 'email';
                $rules = [
                    'email' => 'required|email|exists:masafr,email',
                    'password' => 'required'
                ];
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $cardintions = $request->only([$loginType, 'password']);
            $token = Auth::guard('masafr-api')->attempt($cardintions);
            if (!$token) {
                return $this->returnError('E001', 'fail');
            }
            $admin = Auth::guard('masafr-api')->user();
            $admin->token = $token;
            return $this->returnSuccessMessage($admin);
        } catch (\Exception $e) {
            return $this->returnError('201', $e->getMessage());
        }
    }


    public function me()
    {
        return response()->json(auth()->user());
    }


    public function createMasafr(Request $request)
    {
        try {
            $rules = [
                'email' => 'required|email|unique:masafr,email',
                'country_code' => 'required',
                'phone' => 'required|unique:masafr,phone',
                'name' => 'required|min:4',
                'gender' => 'required',
                'password' => 'required|min:4',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

           $masafrID =  Masafr::insertGetId([
                'email' => $request->email,
                'country_code' => $request->country_code,
                'phone' => $request->phone,
                'name' => $request->name,
                'gender' => $request->gender,
                'password' => bcrypt($request->password),
                'photo' => $request->photo,
            ]);
            return $this->returnData('masafr id',$masafrID);
        } catch (\Exception $e) {
            return $this->returnError('E205', $e->getMessage());
        }
    }
}
