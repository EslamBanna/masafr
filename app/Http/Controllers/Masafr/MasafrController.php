<?php

namespace App\Http\Controllers\Masafr;

use App\Http\Controllers\Controller;
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
            // return $request;
            $rules = [
                'email' => 'required|email|exists:masafr,email',
                'password' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $cardintions = $request->only(['email', 'password']);
            $token = Auth::guard('masafr-api')->attempt($cardintions);
            if (!$token) {
                return $this->returnError('E001', 'not founded');
            }
            $admin = Auth::guard('masafr-api')->user();
            $admin->token = $token;
            return $this->returnSuccessMessage($admin);
        } catch (\Exception $e) {
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            $token = $request->authToken;
            if ($token) {
                JWTAuth::setToken($token)->invalidate();
                return $this->returnSuccessMessage('logout successfully');
            } else {
                return $this->returnError('E205', 'some thing went wrong');
            }
            // return response()->json(['logout' => true]);
        } catch (\Exception $e) {
            return $this->returnError('E205', 'some thing went wrong');
        }
    }


    public function me()
    {
        return response()->json(auth()->user());
    }

}
