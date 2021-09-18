<?php

namespace App\Traits;

use App\Models\Common\Comment;
use App\Models\Common\Complain;
use App\Models\Common\CustomerService;
use App\Models\Common\Transaction;
use App\Models\Masafr\Masafr;
use App\Models\User\User;
use Illuminate\Http\Request;
use Validator;
use Auth;
use JWTAuth;

trait GeneralTrait
{

    public function saveImage($photo, $folder)
    {
        $photo->store('/', $folder);
        $filename = $photo->hashName();
        $path = 'images/' . $folder . '/' . $filename;
        return $path;
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
            CustomerService::create([
                'email' => $request->email,
                'name' => $request->name,
                'body' => $request->body,
                'title' => $request->title,
                'attachment' => $request->attachment,
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
                if (!$request->has('masafr_id')) {
                    return $this->returnError('202', 'fail');
                }
                $masafr = Masafr::find($request->masafr_id);
                if (!$masafr) {
                    return $this->returnError('202', 'fail');
                }
            } else if ($request->type == 1) {
                if (!$request->has('user_id')) {
                    return $this->returnError('202', 'fail');
                }
                $user = User::find($request->user_id);
                if (!$user) {
                    return $this->returnError('202', 'fail');
                }
            }
            // $user = auth()->guard('masafr-api')->user()['id'];
            if ($request->type == 0) {
                $comments = Comment::where('type', 0)
                    ->where('masafr_id', $request->masafr_id)
                    ->get();
                return $this->returnData('comments', $comments);
            }
            $comments = Comment::where('type', 1)
                ->where('user_id', $request->user_id)
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
            $rules = [
                'subject' => 'required',
                'type' => 'required',
                'user_id' => 'required|numeric',
                'masafr_id' => 'required|numeric',
                'status' => 'required|boolean',
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
            Complain::create([
                'type' => $request->type,
                'subject' => $request->subject,
                'user_id' => $request->user_id,
                'masafr_id' => $request->masafr_id,
                'status' => $request->status
            ]);
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError('201', 'fail');
        }
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
