<?php

namespace App\Http\Controllers\Masafr;

use App\Http\Controllers\Controller;
use App\Models\Common\AdminNotifiactions;
use App\Models\Masafr\FreeService;
use App\Models\Masafr\FreeServicePlace;
use App\Models\Masafr\Masafr;
use App\Models\Masafr\TripDays;
use App\Models\Masafr\Trips;
use App\Models\Masafr\TripWays;
use App\Models\User\RequestService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Validator;
use Auth;
use JWTAuth;
use DB;

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
            $masafr = Auth::guard('masafr-api')->user();
            $masafr->token = $token;
            $notifications = AdminNotifiactions::where('type', 1)
                ->where('person_id', $masafr->id)
                ->where('showed', 0)
                ->first();
            if ($notifications) {
                $notifications->update([
                    'showed' => 1
                ]);
            }
            $masafr->notification = $notifications;
            return $this->returnSuccessMessage($masafr);
        } catch (\Exception $e) {
            return $this->returnError('201', $e->getMessage());
        }
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
            return $this->returnData('masafr id', $masafrID);
        } catch (\Exception $e) {
            return $this->returnError('E205', 'fail');
        }
    }

    public function addMasafrInfo(Request $request)
    {
        try {
            $masafr = Masafr::find($request->id);
            if (!$masafr) {
                return $this->returnError('202', 'fail');
            }

            if (!$masafr->active) {
                return $this->returnError('202', 'active first');
            }
            $masafr->update([
                'national_id_number' => $request->national_id_number,
                'nationality' => $request->nationality,
                'car_name' => $request->car_name,
                'car_model' => $request->car_model,
                'car_number' => $request->car_number,
                'id_Photo' => $request->id_Photo,
                'driving_license_photo' => $request->driving_license_photo,
                'car_image_east' => $request->car_image_east,
                'car_image_west' => $request->car_image_west,
                'car_image_north' => $request->car_image_north
            ]);
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError('E205', 'fail');
        }
    }



    public function updateMasafrInfo(Request $request)
    {
        try {
            $rules = [
                'id' => 'required|numeric|exists:masafr,id'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            if ($request->has('phone')) {
                $rules = [
                    'phone' => 'required|unique:masafr,phone'
                ];
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $code = $this->returnCodeAccordingToInput($validator);
                    return $this->returnValidationError($code, $validator);
                }
            }

            if ($request->has('email')) {
                $rules = [
                    'email' => 'required|unique:masafr,email'
                ];
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $code = $this->returnCodeAccordingToInput($validator);
                    return $this->returnValidationError($code, $validator);
                }
            }
            $user = Masafr::find($request->id);
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
                'trips_count' => $request->trips_count ?? $user->trips_count,
                'negative_points_count' => $request->negative_points_count ?? $user->negative_points_count,
                'bargains_count' => $request->bargains_count ?? $user->bargains_count,
                'email_notifications' => $request->email_notifications ?? $user->email_notifications,
                'sms_notifications' => $request->sms_notifications ?? $user->sms_notifications,
                'balance' => $request->balance ?? $user->balance,
            ]);
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError('E205', 'fail');
        }
    }


    public function createTrip(Request $request)
    {
        try {
            DB::beginTransaction();
            $rules = [
                'masafr_id' => 'required|numeric|exists:masafr,id',
                'type_of_trips' => 'required|numeric',
                'type_of_services' => 'required|numeric',
                'from_place' => 'required',
                'description' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            if ($request->type_of_trips == 1) {
                $tripID = Trips::insertGetId([
                    'masafr_id' => $request->masafr_id,
                    'only_women' => $request->only_women ?? 0,
                    'type_of_trips' => $request->type_of_trips,
                    'type_of_services' => $request->type_of_services,
                    'from_place' => $request->from_place,
                    'from_longitude' => $request->from_longitude,
                    'from_latitude' => $request->from_latitude,
                    'description' => $request->description,
                    'to_place' => $request->to_place,
                    'to_longitude' => $request->to_longitude,
                    'to_latitude' => $request->to_latitude,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date
                ]);
                foreach ($request['trip_ways'] as $way) {
                    TripWays::create([
                        'trip_id' => $tripID,
                        'place' => $way['place'],
                        'longitude' => $way['longitude'] ?? null,
                        'latitude' => $way['latitude'] ?? null,
                        'time' => $way['time'] ?? null
                    ]);
                }
            } else if ($request->type_of_trips == 2) {
                $tripID = Trips::insertGetId([
                    'masafr_id' => $request->masafr_id,
                    'only_women' => $request->only_women ?? 0,
                    'type_of_trips' => $request->type_of_trips,
                    'type_of_services' => $request->type_of_services,
                    'from_place' => $request->from_place,
                    'from_longitude' => $request->from_longitude,
                    'from_latitude' => $request->from_latitude,
                    'description' => $request->description,
                    'to_place' => $request->to_place,
                    'to_longitude' => $request->to_longitude,
                    'to_latitude' => $request->to_latitude,
                ]);
                foreach ($request['trip_ways'] as $way) {
                    TripWays::create([
                        'trip_id' => $tripID,
                        'place' => $way['place'],
                        'longitude' => $way['longitude'] ?? null,
                        'latitude' => $way['latitude'] ?? null,
                    ]);
                }
                foreach ($request['trip_days'] as $day) {
                    TripDays::create([
                        'trip_id' => $tripID,
                        'trip_day' => $day['trip_day']
                    ]);
                }
            } else if ($request->type_of_trips == 3) {

                $tripID = Trips::insertGetId([
                    'masafr_id' => $request->masafr_id,
                    'only_women' => $request->only_women ?? 0,
                    'type_of_trips' => $request->type_of_trips,
                    'type_of_services' => $request->type_of_services,
                    'from_place' => $request->from_place,
                    'from_longitude' => $request->from_longitude,
                    'from_latitude' => $request->from_latitude,
                    'description' => $request->description,
                    'to_place' => $request->to_place,
                    'to_longitude' => $request->to_longitude,
                    'to_latitude' => $request->to_latitude,
                ]);
                foreach ($request['trip_ways'] as $way) {
                    TripWays::create([
                        'trip_id' => $tripID,
                        'place' => $way['place'],
                        'longitude' => $way['longitude'] ?? null,
                        'latitude' => $way['latitude'] ?? null,
                    ]);
                }
            } else if ($request->type_of_trips == 4) {
                $tripID = Trips::insertGetId([
                    'masafr_id' => $request->masafr_id,
                    'only_women' => $request->only_women ?? 0,
                    'type_of_trips' => $request->type_of_trips,
                    'type_of_services' => $request->type_of_services,
                    'from_place' => $request->from_place,
                    'from_longitude' => $request->from_longitude,
                    'from_latitude' => $request->from_latitude,
                    'description' => $request->description
                ]);
            }
            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('201', 'fail');
        }
    }


    public function updateTrip(Request $request)
    {
        try {
            $trip = Trips::find($request->id);
            if (!$trip) {
                return $this->returnError('202', 'fail');
            }

            $trip->update([
                'type_of_trips' => $request->type_of_trips ?? $trip->type_of_trips,
                'type_of_services' => $request->type_of_services ?? $trip->type_of_services,
                'only_women' => $request->only_women ?? $trip->only_women,
                'from_place' => $request->from_place ?? $trip->from_place,
                'from_longitude' => $request->from_longitude ?? $trip->from_longitude,
                'from_latitude' => $request->from_latitude ?? $trip->from_latitude,
                'to_place' => $request->to_place ?? $trip->to_place,
                'to_longitude' => $request->to_longitude ?? $trip->to_longitude,
                'to_latitude' => $request->to_latitude ?? $trip->to_latitude,
                'start_date' => $request->start_date ?? $trip->start_date,
                'end_date' => $request->end_date ?? $trip->end_date,
                'description' => $request->description ?? $trip->description,
                'active' => $request->active ?? $trip->active
            ]);

            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('201', 'fail');
        }
    }

    public function deleteTrip(Request $request)
    {
        try {
            $trip = Trips::find($request->id);
            if (!$trip) {
                return $this->returnError('202', 'fail');
            }
            $trip->delete();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function getRequestService(Request $request)
    {
        try {
            $userRequestService = RequestService::find($request->id);
            if (!$userRequestService) {
                return $this->returnError('202', 'fail');
            }
            return $this->returnData('data', $userRequestService);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function searchRequestService(Request $request)
    {
        try {
            $rules = [
                'type_of_service' => 'required|numeric',
                'from_place' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $requestSerices = null;
            if ($request->type_of_service == 0) {
                if (!$request->has('to_place')) {
                    return $this->returnError('202', 'fail');
                }
                $requestSerices = RequestService::where('from_place', 'like', '%' . $request->from_place . '%')
                    ->where('to_place', 'like', '%' . $request->to_place . '%')
                    ->get();
            } else if ($request->type_of_service == 1) {
                $requestSerices = RequestService::where('from_place', 'like', '%' . $request->from_place . '%')
                    ->where('to_place', 'like', '%' . $request->to_place . '%')
                    ->where('type_of_trips', 4)
                    ->get();
            } else if ($request->type_of_service == 2) {
                $trips = RequestService::where([
                    ['from_place', 'like', '%' . $request->from_place . '%'],
                    ['to_place', 'like', '%' . $request->to_place . '%'],
                    ['type_of_trips', '=', 2],
                    ['type_of_services', '=', 3]
                ])->orWhere([
                    ['from_place', 'like', '%' . $request->from_place . '%'],
                    ['to_place', 'like', '%' . $request->to_place . '%'],
                    ['type_of_trips', '=', 2],
                    ['type_of_services', '=', 4]
                ])->orWhere([
                    ['from_place', 'like', '%' . $request->from_place . '%'],
                    ['to_place', 'like', '%' . $request->to_place . '%'],
                    ['type_of_trips', '=', 2],
                    ['type_of_services', '=', 5]
                ])->orWhere([
                    ['from_place', 'like', '%' . $request->from_place . '%'],
                    ['to_place', 'like', '%' . $request->to_place . '%'],
                    ['type_of_trips', '=', 2],
                    ['type_of_services', '=', 6]
                ])->get();
            } else if ($request->type_of_service == 3) {
                $requestSerices = RequestService::where('from_place', 'like', '%' . $request->from_place . '%')
                    ->where('to_place', 'like', '%' . $request->to_place . '%')
                    ->where('type_of_trips', 4)
                    ->get();
            } else if ($request->type_of_service == 4) {
            } else if ($request->type_of_service == 5) {
                $requestSerices = RequestService::where('from_place', 'like', '%' . $request->from_place . '%')
                    ->where('to_place', 'like', '%' . $request->to_place . '%')
                    ->where('only_women', 1)
                    ->get();
            }

            return $this->returnData('data', $requestSerices);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }


    public function createFreeService(Request $request)
    {
        // return $request;
        try {
            DB::beginTransaction();
            $rules = [
                'masafr_id' => 'required|numeric|exists:masafr,id',
                'type' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $freeServiceID = FreeService::insertGetId([
                'masafr_id' => $request->masafr_id,
                'type' => $request->type,
                'photo' => $request->photo,
                'description' => $request->description
            ]);
            foreach ($request['places'] as $place) {
                FreeServicePlace::create([
                    'free_service_id' => $freeServiceID,
                    'place' => $place['place']
                ]);
            }
            // FreeServicePlace::insert($request['places']);
            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('201', 'fail');
        }
    }
}
