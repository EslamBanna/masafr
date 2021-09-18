<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Validator;
use Auth;
use JWTAuth;

class UserController extends Controller
{

    use GeneralTrait;
    public function login(Request $request)
    {
        try {
            // return $request;
            $rules = [
                'email' => 'required|email|exists:users,email',
                'password' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $cardintions = $request->only(['email', 'password']);
            $token = Auth::guard('user-api')->attempt($cardintions);
            if (!$token) {
                return $this->returnError('E001', 'fail');
            }
            $admin = Auth::guard('user-api')->user();
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
                return $this->returnSuccessMessage('success');
            } else {
                return $this->returnError('E205', 'fail');
            }
            // return response()->json(['logout' => true]);
        } catch (\Exception $e) {
            return $this->returnError('E205', 'fail');
        }
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function createUser(Request $request){
        // return $request;
        try{
        $rules = [
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|unique:users,phone',
            'name' => 'required|min:4',
            'gender' => 'required',
            'password' => 'required|min:4',
            'photo' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }

        User::create([
            'email' => $request->email,
            'phone' => $request->phone,
            'name' => $request->name,
            'gender' => $request->gender,
            'password' => $request->password,
            'photo' => $request->photo,
        ]);
        return $this->returnData('user',$request,'success');
    }catch(\Exception $e){
        return $this->returnError('E205', $e->getMessage());
    }
    }

}
