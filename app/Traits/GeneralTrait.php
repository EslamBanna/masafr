<?php

namespace App\Traits;

use App\Models\Common\Comment;
use App\Models\Common\Complain;
use App\Models\Common\ComplainList;
use App\Models\Common\CustomerService;
use App\Models\Common\Message;
use App\Models\Common\Notification;
use App\Models\Common\Transaction;
use App\Models\Masafr\Masafr;
use App\Models\User\User;
use Illuminate\Http\Request;
use Validator;
use Auth;
use JWTAuth;
use DB;

trait GeneralTrait
{

    public function varifyAccount(Request $request)
    {
        try {
            $rules = [
                'type' => 'required|boolean',
                'id' => 'required|numeric',
                'code' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            if ($request->type == 0) {
                // user
                $user = User::find($request->id);
                if (!$user) {
                    return $this->returnError('202', 'fail');
                }

                if ($user->verification_code == $request->code) {
                    $user->update([
                        'is_verified' => 1
                    ]);
                    return $this->returnSuccessMessage('success');
                } else {
                    if($user->active_try < 3){
                    $wrong_active_try = $user->active_try + 1;
                    $user->update([
                        'active_try' => $wrong_active_try
                    ]);
                    return $this->returnError('700', 'fail');
                }else if($user->active_try == 3)
                    return $this->returnError('800', 'fail');
                }
            } else if ($request->type == 1) {
                // masafr

                $masafr = Masafr::find($request->id);
                if (!$masafr) {
                    return $this->returnError('202', 'fail');
                }

                if ($masafr->verification_code == $request->code) {
                    $masafr->update([
                        'is_verified' => 1
                    ]);
                } else {
                    $wrong_active_try = $masafr->active_try++;
                    $masafr->update([
                        'active_try' =>$wrong_active_try
                    ]);
                    return $this->returnError('data', 'fail');
                }
            }
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function saveImage($photo, $folder)
    {
        $photo->store('/', $folder);
        $filename = $photo->hashName();
        // $path = 'images/' . $folder . '/' . $filename;
        return $filename;
    }

    public function CustomerService(Request $request)
    {
        try {
            $rules = [
                'email' => 'required|email',
                'name' => 'required',
                'title' => 'required',
                'body' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $file_name_attachment = null;
            if ($request->hasFile('attachment')) {
                $file_name_attachment  = $this->saveImage($request->attachment, 'customers_service');
            }
            CustomerService::create([
                'email' => $request->email,
                'name' => $request->name,
                'body' => $request->body,
                'title' => $request->title,
                'attachment' => $file_name_attachment
            ]);
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function storeTransaction(Request $request)
    {
        try {
            $rules = [
                'type' => 'required|boolean',
                'user_id' => 'required',
                'subject' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            if ($request->type == 0) {
                $user = User::find($request->user_id);
                if (!$user) {
                    return $this->returnError('202', 'fail');
                }
            } else if ($request->type == 1) {
                $masafr = Masafr::find($request->user_id);
                if (!$masafr) {
                    return $this->returnError('202', 'fail');
                }
            }
            Transaction::create([
                'type' => $request->type,
                'user_id' => $request->user_id,
                'subject' => $request->subject
            ]);
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function getTransactions(Request $request)
    {
        try {
            $rules = [
                'type' => 'required|boolean',
                'user_id' => 'required|numeric',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            if ($request->type == 0) {
                $user = User::find($request->user_id);
                if (!$user) {
                    return $this->returnError('202', 'fail');
                }
            } else if ($request->type == 1) {
                $masafr = Masafr::find($request->user_id);
                if (!$masafr) {
                    return $this->returnError('202', 'fail');
                }
            }
            // $user = auth()->guard('masafr-api')->user()['id'];
            $transactions = Transaction::where('type', '=', $request->type)
                ->where('user_id', $request->user_id)
                ->get();
            return $this->returnData('transactions', $transactions);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function makeComment(Request $request)
    {
        try {
            $rules = [
                'type' => 'required|boolean',
                'user_id' => 'required|numeric',
                'masafr_id' => 'required|numeric',
                'subject' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $user = User::find($request->user_id);
            if (!$user) {
                return $this->returnError('202', 'fail');
            }
            $masafr = Masafr::find($request->masafr_id);
            if (!$masafr) {
                return $this->returnError('202', 'fail');
            }
            // $user = auth()->guard('masafr-api')->user()['id'];
            Comment::create([
                'type' => $request->type,
                'user_id' => $request->user_id,
                'masafr_id' => $request->masafr_id,
                'subject' => $request->subject,
            ]);
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function getComments(Request $request)
    {
        try {
            if (!$request->has('type')) {
                return $this->returnError('202', 'fail');
            }
            if ($request->type == 0) {
                if (!$request->has('pesron_id')) {
                    return $this->returnError('202', 'fail');
                }
                $masafr = Masafr::find($request->pesron_id);
                if (!$masafr) {
                    return $this->returnError('202', 'fail');
                }
            } else if ($request->type == 1) {
                if (!$request->has('pesron_id')) {
                    return $this->returnError('202', 'fail');
                }
                $user = User::find($request->pesron_id);
                if (!$user) {
                    return $this->returnError('202', 'fail');
                }
            }
            // $user = auth()->guard('masafr-api')->user()['id'];
            if ($request->type == 0) {
                $comments = Comment::with('User')->where('type', 0)
                    ->where('masafr_id', $request->pesron_id)
                    ->get();
                return $this->returnData('comments', $comments);
            }
            $comments = Comment::with('Masafr')->where('type', 1)
                ->where('user_id', $request->pesron_id)
                ->get();
            return $this->returnData('comments', $comments);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function updateComment(Request $request)
    {
        try {
            $rules = [
                'id' => 'required|numeric',
                'subject' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $comment = Comment::find($request->id);
            if (!$comment) {
                return $this->returnError('202', 'fail');
            }
            $comment->update([
                'wait' => 1,
                'wait_subject' => $request->subject
            ]);
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function makeComplain(Request $request)
    {
        try {
            DB::beginTransaction();
            $rules = [
                'subject' => 'required',
                'type' => 'required',
                'user_id' => 'required|numeric',
                'masafr_id' => 'required|numeric',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $user = User::find($request->user_id);
            if (!$user) {
                return $this->returnError('202', 'fail');
            }
            $masafr = Masafr::find($request->masafr_id);
            if (!$masafr) {
                return $this->returnError('202', 'fail');
            }
            $complain = Complain::where('user_id', $request->user_id)
                ->where('masafr_id', $request->masafr_id)
                ->first();
            if (!$complain) {
                // return 'empty';
                $complainID = Complain::insertGetId([
                    'user_id' => $request->user_id,
                    'masafr_id' => $request->masafr_id,
                    'status' => 1
                ]);

                $file_name_attach = null;
                if ($request->hasFile('attach')) {
                    $file_name_attach  = $this->saveImage($request->attach, 'complains');
                }

                ComplainList::create([
                    'complain_id' => $complainID,
                    'type' => $request->type,
                    'subject' => $request->subject,
                    'attach' => $file_name_attach
                ]);
                DB::commit();
                return $this->returnSuccessMessage('success');
            }
            $file_name_attach = null;
            if ($request->hasFile('attach')) {
                $file_name_attach  = $this->saveImage($request->attach, 'complains');
            }
            ComplainList::create([
                'complain_id' => $complain['id'],
                'type' => $request->type,
                'subject' => $request->subject,
                'attach' => $file_name_attach
            ]);
            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError('201', 'fail');
        }
    }

    public function getComplains(Request $request)
    {
        try {

            $rules = [
                'user_id' => 'required|numeric',
                'masafr_id' => 'required|numeric',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $user = User::find($request->user_id);
            if (!$user) {
                return $this->returnError('202', 'fail');
            }
            $masafr = Masafr::find($request->masafr_id);
            if (!$masafr) {
                return $this->returnError('202', 'fail');
            }
            $complain = Complain::with('complainList')->where('user_id', $request->user_id)
                ->where('masafr_id', $request->masafr_id)
                ->first();
            return $this->returnData('data', $complain);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function getNotifications(Request $request)
    {
        try {

            $rules = [
                'type' => 'required|numeric',
                'person_id' => 'required|numeric',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            if ($request->type == 0) {
                $user = User::find($request->person_id);
                if (!$user) {
                    return $this->returnError('202', 'fail');
                }
            } else  if ($request->type == 1) {
                $masafr = Masafr::find($request->person_id);
                if (!$masafr) {
                    return $this->returnError('202', 'fail');
                }
            }
            $notifications = Notification::where('type', $request->type)
                ->where('person_id', $request->person_id)
                ->paginate($request->paginateCount);
            return $this->returnData('data', $notifications);
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function storeNotifications(Request $request)
    {
        try {
            $rules = [
                'type' => 'required|numeric',
                'person_id' => 'required|numeric',
                'subject' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            if ($request->type == 0) {
                $user = User::find($request->person_id);
                if (!$user) {
                    return $this->returnError('202', 'fail');
                }
            } else if ($request->type == 1) {
                $masafr = Masafr::find($request->person_id);
                if (!$masafr) {
                    return $this->returnError('202', 'fail');
                }
            }

            Notification::create([
                'type' => $request->type,
                'person_id' => $request->person_id,
                'subject' => $request->subject,
                'target_code' => $request->target_code ?? null,
                'related_trip' => $request->related_trip ?? null,
                'related_request_service' => $request->related_request_service ?? null,
            ]);

            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
    }

    public function sendMessage(Request $request)
    {
        try {
            $rules = [
                'sender_type' => 'required|numeric',
                'user_id' => 'required|numeric|exists:users,id',
                'masafr_id' => 'required|numeric|exists:masafr,id',
                'related_trip' => 'required|numeric|exists:trips,id',
                'related_request_service' => 'required|numeric|exists:request_services,id'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $file_name_attach = null;
            if ($request->hasFile('attach')) {
                $file_name_attach  = $this->saveImage($request->attach, 'messages');
            }

            Message::create([
                'sender_type' => $request->sender_type,
                'user_id' => $request->user_id,
                'masafr_id' => $request->masafr_id,
                'related_trip' => $request->related_trip,
                'related_request_service' => $request->related_request_service,
                'subject' => $request->subject,
                'attach' => $file_name_attach
            ]);

            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError('201', $e->getMessage());
        }
    }

    public function getMessages(Request $request)
    {
        try {
            $rules = [
                'user_id' => 'required|numeric|exists:users,id',
                'masafr_id' => 'required|numeric|exists:masafr,id'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $messages = Message::with(['masafr' => function ($q) {
                $q->select('id', 'name', 'photo', 'id');
            }])
                ->with(['user' => function ($q) {
                    $q->select('id', 'name', 'photo');
                }])
                ->where('user_id', $request->user_id)
                ->where('masafr_id', $request->masafr_id)
                ->get();
            // $messages->user =  $messages->user;
            // $messages['masafr'] =  $messages->masafr;

            return $this->returnData('data', $messages);
        } catch (\Exception $e) {
            return $this->returnError('201', $e->getMessage());
        }
    }


    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout(Request $request)
    {
        try {
            $token = $request->header('authToken');
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

    public function returnError($errNum, $msg)
    {
        return response()->json([
            'status' => false,
            'errNum' => $errNum,
            'msg' => $msg
        ]);
    }


    public function returnSuccessMessage($msg = "", $errNum = "S000")
    {
        return [
            'status' => true,
            'errNum' => $errNum,
            'msg' => $msg
        ];
    }

    public function returnData($key, $value, $msg = "")
    {
        return response()->json([
            'status' => true,
            'errNum' => "S000",
            'msg' => $msg,
            $key => $value
        ]);
    }


    //////////////////
    public function returnValidationError($code, $validator)
    {
        return $this->returnError($code, $validator->errors()->first());
    }


    public function returnCodeAccordingToInput($validator)
    {
        $inputs = array_keys($validator->errors()->toArray());
        $code = $this->getErrorCode($inputs[0]);
        return $code;
    }

    public function getErrorCode($input)
    {
        if ($input == "name")
            return 'E0011';

        else if ($input == "password")
            return 'E002';

        else if ($input == "mobile")
            return 'E003';

        else if ($input == "id_number")
            return 'E004';

        else if ($input == "birth_date")
            return 'E005';

        else if ($input == "agreement")
            return 'E006';

        else if ($input == "email")
            return 'E007';

        else if ($input == "city_id")
            return 'E008';

        else if ($input == "insurance_company_id")
            return 'E009';

        else if ($input == "activation_code")
            return 'E010';

        else if ($input == "longitude")
            return 'E011';

        else if ($input == "latitude")
            return 'E012';

        else if ($input == "id")
            return 'E013';

        else if ($input == "promocode")
            return 'E014';

        else if ($input == "doctor_id")
            return 'E015';

        else if ($input == "payment_method" || $input == "payment_method_id")
            return 'E016';

        else if ($input == "day_date")
            return 'E017';

        else if ($input == "specification_id")
            return 'E018';

        else if ($input == "importance")
            return 'E019';

        else if ($input == "type")
            return 'E020';

        else if ($input == "message")
            return 'E021';

        else if ($input == "reservation_no")
            return 'E022';

        else if ($input == "reason")
            return 'E023';

        else if ($input == "branch_no")
            return 'E024';

        else if ($input == "name_en")
            return 'E025';

        else if ($input == "name_ar")
            return 'E026';

        else if ($input == "gender")
            return 'E027';

        else if ($input == "nickname_en")
            return 'E028';

        else if ($input == "nickname_ar")
            return 'E029';

        else if ($input == "rate")
            return 'E030';

        else if ($input == "price")
            return 'E031';

        else if ($input == "information_en")
            return 'E032';

        else if ($input == "information_ar")
            return 'E033';

        else if ($input == "street")
            return 'E034';

        else if ($input == "branch_id")
            return 'E035';

        else if ($input == "insurance_companies")
            return 'E036';

        else if ($input == "photo")
            return 'E037';

        else if ($input == "logo")
            return 'E038';

        else if ($input == "working_days")
            return 'E039';

        else if ($input == "insurance_companies")
            return 'E040';

        else if ($input == "reservation_period")
            return 'E041';

        else if ($input == "nationality_id")
            return 'E042';

        else if ($input == "commercial_no")
            return 'E043';

        else if ($input == "nickname_id")
            return 'E044';

        else if ($input == "reservation_id")
            return 'E045';

        else if ($input == "attachments")
            return 'E046';

        else if ($input == "summary")
            return 'E047';

        else if ($input == "user_id")
            return 'E048';

        else if ($input == "mobile_id")
            return 'E049';

        else if ($input == "paid")
            return 'E050';

        else if ($input == "use_insurance")
            return 'E051';

        else if ($input == "doctor_rate")
            return 'E052';

        else if ($input == "provider_rate")
            return 'E053';

        else if ($input == "message_id")
            return 'E054';

        else if ($input == "hide")
            return 'E055';

        else if ($input == "checkoutId")
            return 'E056';

        else
            return "";
    }
}
