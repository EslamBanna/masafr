<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Common\AdminNotifiactions;
use App\Models\Masafr\Trips;
use App\Models\User\RequestService;
use App\Models\User\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Validator;
use Auth;
use JWTAuth;
use PDO;

class UserController extends Controller
{

    use GeneralTrait;
    public function login(Request $request)
    {
        try {
            $loginType = 'phone';
            $rules = [
                'phone' => 'required|exists:users,phone',
                'password' => 'required'
            ];
            if ($request->has('email')) {
                $loginType = 'email';
                $rules = [
                    'email' => 'required|email|exists:users,email',
                    'password' => 'required'
                ];
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $cardintions = $request->only([$loginType, 'password']);
            $token = Auth::guard('user-api')->attempt($cardintions);
            if (!$token) {
                return $this->returnError('E001', 'fail');
            }
            $user = Auth::guard('user-api')->user();
            $user->token = $token;

            $notifications = AdminNotifiactions::where('type', 0)
                ->where('person_id', $user->id)
                ->where('showed', 0)
                ->first();
            if ($notifications) {
                $notifications->update([
                    'showed' => 1
                ]);
            }
            $user->notification = $notifications;
            return $this->returnSuccessMessage($user);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }



    public function createUser(Request $request)
    {
        try {
            $rules = [
                'email' => 'required|email|unique:users,email',
                'country_code' => 'required',
                'phone' => 'required|unique:users,phone',
                'name' => 'required|min:4',
                'gender' => 'required',
                'password' => 'required|min:4',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $userID =  User::insertGetId([
                'email' => $request->email,
                'phone' => $request->phone,
                'name' => $request->name,
                'gender' => $request->gender,
                'password' => bcrypt($request->password),
                'photo' => $request->photo,
                'country_code' => $request->country_code
            ]);
            return $this->returnData('user id', $userID);
        } catch (\Exception $e) {
            return $this->returnError('E205', 'fail');
        }
    }

    public function updateUserInfo(Request $request)
    {
        try {
            $rules = [
                'id' => 'required|numeric|exists:users,id'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            if ($request->has('phone')) {
                $rules = [
                    'phone' => 'required|unique:users,phone'
                ];
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $code = $this->returnCodeAccordingToInput($validator);
                    return $this->returnValidationError($code, $validator);
                }
            }

            if ($request->has('email')) {
                $rules = [
                    'email' => 'required|unique:users,email'
                ];
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $code = $this->returnCodeAccordingToInput($validator);
                    return $this->returnValidationError($code, $validator);
                }
            }
            $user = User::find($request->id);
            if (!$user) {
                return $this->returnError('202', 'fail');
            }
            $user->update([
                'id_Photo' => $request->id_Photo ?? $user->id_Photo,
                'national_id_number' => $request->national_id_number ?? $user->national_id_number,
                'phone' => $request->phone ?? $user->phone,
                'email' => $request->email ?? $user->email,
                'name' => $request->name ?? $user->name,
                'password' => bcrypt($request->password) ?? $user->password,
                'gender' => $request->gender ?? $user->gender,
                'photo' => $request->photo ?? $user->photo,
                'country_code' => $request->country_code ?? $user->country_code,
                'rate' => $request->rate ?? $user->rate,
                'validation_code' => $request->validation_code ?? $user->validation_code,
                'active' => $request->active ?? $user->active,
                'active_try' => $request->active_try ?? $user->active_try,
                'orders_count' => $request->orders_count ?? $user->orders_count,
                'bargains_count' => $request->bargains_count ?? $user->bargains_count,
                'email_notifications' => $request->email_notifications ?? $user->email_notifications,
                'balance' => $request->balance ?? $user->balance,

            ]);
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError('E205', 'fail');
        }
    }

    public function createRequestService(Request $request)
    {
        // return $request;
        try {
            $rules = [
                'user_id' => 'required|exists:users,id',
                'description' => "required",
                'type_of_trips' => 'required|numeric',
                'type_of_services' => 'required|numeric'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            RequestService::create([
                'user_id' => $request->user_id,
                'type_of_trips' => $request->type_of_trips,
                'type_of_services' => $request->type_of_services,
                'from_place' => $request->from_place,
                'from_longitude' => $request->from_longitude,
                'from_latitude' => $request->from_latitude,
                'to_place' => $request->to_place,
                'to_longitude' => $request->to_longitude,
                'to_latitude' => $request->to_latitude,
                'max_day' => $request->max_day,
                'delivery_to' => $request->delivery_to,
                'photo' => $request->photo,
                'description' => $request->description,
                'only_women' => $request->only_women,
                'have_insurance' => $request->have_insurance,
                'website_service' => $request->website_service,
                'number_of_passengers' => $request->number_of_passengers,
                'type_of_car' => $request->type_of_car
            ]);
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError('E205', $e->getMessage());
        }
    }

    public function getTrip(Request $request)
    {
        try {
            $trip = Trips::find($request->id);
            if (!$trip) {
                return $this->returnError('202', 'fail');
            }
            return $this->returnData('data', $trip);
        } catch (\Exception $e) {
            return $this->returnError('201', $e->getMessage());
        }
    }
}
