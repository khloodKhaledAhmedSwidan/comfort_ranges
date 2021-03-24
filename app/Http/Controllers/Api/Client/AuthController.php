<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Api\ApiController;
use App\Models\Location;
use App\Notifications\Newvisit;
use App\PhoneVerification;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class AuthController extends Controller
{
    //
    public function registerMobile(Request $request)
    {
        $rules = [
            'phone' => 'required|min:10|max:15',
//            'phone_number' => 'required|max:9|unique:users||regex:/(9665)[0-9]{11}/',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        if (substr($request->phone, 0, 2) === '05') {
            $result = substr($request->phone, 1);
            $phone = '00966' . $result;
            $code = mt_rand(1000, 9999);
            $jsonObj = array(
                'mobile' => 'tqnee.com.sa',
                'password' => '589935sa',
                'sender' => 'TQNEE',
                'numbers' => $phone,
                'msg' => 'كود التأكيد الخاص بك في نطاقات الصيانة هو :' . $code,

                'msgId' => rand(1, 99999),

                'timeSend' => '0',

                'dateSend' => '0',

                'deleteKey' => '55348',
                'lang' => '3',
                'applicationType' => 68,
            );
            // دالة الإرسال JOSN
            $result = $this->sendSMS($jsonObj);


//        $ans= substr($ans,0,1);
            $created = \App\Models\PhoneVerification::create([
                'code' => $code,
                'phone' => $phone
            ]);


            return ApiController::respondWithSuccess([]);


        } else {
            $errors = ['key' => 'message',
                'value' => trans('messages.phone_validation')
            ];
            return ApiController::respondWithErrorArray(array($errors));
        }

    }

    public function register_phone_post(Request $request)
    {

        $rules = [
            'code' => 'required',
            'phone' => 'required|min:10|max:15',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));


        if (substr($request->phone, 0, 2) === '05') {
            $result = substr($request->phone, 1);
            $phone = '00966' . $result;
            $user = \App\Models\PhoneVerification::where('phone', $phone)->orderBy('id', 'desc')->first();
            if ($user) {

                if ($user->code == $request->code) {
                    $successLogin = ['key' => 'message',
                        'value' => "كود التفعيل صحيح"
                    ];
                    return ApiController::respondWithSuccess($successLogin);
                } else {
                    $errorsLogin = ['key' => 'message',
                        'value' => trans('messages.error_code')
                    ];
                    return ApiController::respondWithErrorClient(array($errorsLogin));
                }

            } else {

                $errorsLogin = ['key' => 'message',
                    'value' => trans('messages.error_code')
                ];
                return ApiController::respondWithErrorClient(array($errorsLogin));
            }
        } else {
            $errors = ['key' => 'message',
                'value' => trans('messages.phone_validation')
            ];
            return ApiController::respondWithErrorArray(array($errors));
        }


    }

    public function resend_code(Request $request)
    {

        $rules = [
            'phone' => 'required|min:10|max:15',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));


        if (substr($request->phone, 0, 2) === '05') {
            $result = substr($request->phone, 1);
            $phone = '00966' . $result;

            $code = mt_rand(1000, 9999);


            $jsonObj = array(
                'mobile' => 'tqnee.com.sa',
                'password' => '589935sa',
                'sender' => 'TQNEE',
                'numbers' => $phone,
                'msg' => 'كود التأكيد الخاص بك في نظاقات الصيانة هو :' . $code,

                'msgId' => rand(1, 99999),

                'timeSend' => '0',

                'dateSend' => '0',

                'deleteKey' => '55348',
                'lang' => '3',
                'applicationType' => 68,
            );
            // دالة الإرسال JOSN
            $result = $this->sendSMS($jsonObj);

            $created = \App\Models\PhoneVerification::create([
                'code' => $code,
                'phone' => $phone
            ]);


            return $created
                ? ApiController::respondWithSuccess(trans('messages.success_send_code'))
                : ApiController::respondWithServerErrorObject();

        } else {
            $errors = ['key' => 'message',
                'value' => trans('messages.phone_validation')
            ];
            return ApiController::respondWithErrorArray(array($errors));
        }
    }

    public function forgetPassword(Request $request)
    {
        $rules = [
            'phone' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $user = User::where('phone', $request->phone)->where('type' , 1)->first();

        if ($user) {
            $code = mt_rand(1000, 9999);
            $updated = User::where('phone', $request->phone)->update([
                'verification_code' => $code,
            ]);
            $user->notify(new Newvisit($code));
            $success = ['key' => 'message',
                'value' => "تم ارسال الكود بنجاح"
            ];

            return $updated
                ? ApiController::respondWithSuccess($success)
                : ApiController::respondWithServerErrorObject();

        }
        $errorsLogin = ['key' => 'message',
            'value' => 'رقم الهاتف غير صحيح'
        ];
        return ApiController::respondWithErrorClient(array($errorsLogin));
    }

    public function confirmResetCode(Request $request)
    {

        $rules = [
            'phone' => 'required|numeric',
            'code' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $user = User::where('phone', $request->phone)->where('verification_code', $request->code)->where('type' , 1)->first();
        if ($user) {
            $updated = User::where('phone', $request->phone)->where('verification_code', $request->code)->update([
                'verification_code' => null
            ]);
            $success = ['key' => 'message',
                'value' => "الكود صحيح"
            ];
            return $updated
                ? ApiController::respondWithSuccess($success)
                : ApiController::respondWithServerErrorObject();
        } else {

            $errorsLogin = ['key' => 'message',
                'value' => trans('messages.error_code')
            ];
            return ApiController::respondWithErrorClient(array($errorsLogin));
        }


    }

    public function resetPassword(Request $request)
    {
        $rules = [
            'phone' => 'required',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $user = User::where('phone', $request->phone)->where('type',1)->first();
//        $user = User::wherePhone($request->phone)->first();

        if ($user)
            $updated = $user->update(['password' => Hash::make($request->password)]);
        else {
            $errorsLogin = ['key' => 'message',
                'value' => trans('messages.Wrong_phone')
            ];
            return ApiController::respondWithErrorClient(array($errorsLogin));
        }


        return $updated
            ? ApiController::respondWithSuccess(trans('messages.Password_reset_successfully'))
            : ApiController::respondWithServerErrorObject();
    }

    public function changePassword(Request $request)
    {

        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min:8',
            'password_confirmation' => 'required|same:new_password',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $error_old_password = ['key' => 'message',
            'value' => trans('messages.error_old_password')
        ];
        if (!(Hash::check($request->current_password, $request->user()->password)))
            return ApiController::respondWithErrorNOTFoundObject(array($error_old_password));
//        if( strcmp($request->current_password, $request->new_password) == 0 )
//            return response()->json(['status' => 'error', 'code' => 404, 'message' => 'New password cant be the same as the old one.']);

        //update-password-finally ^^
        $updated = $request->user()->update(['password' => Hash::make($request->new_password)]);

        $success_password = ['key' => 'message',
            'value' => trans('messages.Password_reset_successfully')
        ];

        return $updated
            ? ApiController::respondWithSuccess($success_password)
            : ApiController::respondWithServerErrorObject();
    }

    public function change_phone_number(Request $request)
    {

//dd($request->user());
        $rules = [
            'phone' => 'required|numeric',

        ];

        $validator = Validator::make($request->all(), $rules);

//        dd($rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

            if(Auth::user()->type != 1 ) {
                $errors = ['key' => 'message',
                    'value' => trans('messages.valiadate_Data')
                ];
                return ApiController::respondWithErrorArray(array($errors));
            }



            $phoneCheck = User::where('phone',$request->phone)->where('type',1)->whereNotIn('id',[$request->user()->id])->first();
            if($phoneCheck)
            {
                $errors = [
                    'key'=>'message',
                    'value'=>$request->header('X-localization') == 'en' ?'this number is used before' :'رقم الهاتف مستخدم من قبل',
                ];
                return ApiController::respondWithErrorNOTFoundArray(array($errors));
            }
        $code = mt_rand(1000, 9999);


        $jsonObj = array(
            'mobile' => 'tqnee.com.sa',
            'password' => '589935sa',
            'sender' => 'TQNEE',
            'numbers' => $request->phone,
            'msg' => 'كود التأكيد الخاص بك في نطاقات الصيانة هو :' . $code,

            'msgId' => rand(1, 99999),

            'timeSend' => '0',

            'dateSend' => '0',

            'deleteKey' => '55348',
            'lang' => '3',
            'applicationType' => 68,
        );
        // دالة الإرسال JOSN
        $result = $this->sendSMS($jsonObj);
        $updated = User::where('id', $request->user()->id)->update([
            'verification_code' => $code,
        ]);

        $success = ['key' => 'message',
            'value' => trans('messages.success_send_code')
        ];
        return $updated
            ? ApiController::respondWithSuccess($success)
            : ApiController::respondWithServerErrorObject();

    }

    public function check_code_changeNumber(Request $request)
    {

        $rules = [
            'code' => 'required',
            'phone' => 'required|numeric',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $user = User::where('id', $request->user()->id)->where('verification_code', $request->code)->where('type',1)->first();
        if ($user) {
            $updated = $user->update([
                'verification_code' => null,
                'phone' => $request->phone,
            ]);

            $success = ['key' => 'message',
                'value' => trans('messages.change_phone'),
            ];
            return $updated
                ? ApiController::respondWithSuccess($success)
                : ApiController::respondWithServerErrorObject();
        } else {

            $errorsLogin = ['key' => 'message',
                'value' => trans('messages.error_code')
            ];
            return ApiController::respondWithErrorClient(array($errorsLogin));
        }


    }

    public function register(Request $request)
    {

        $rules = [
            'name' => 'required|max:255',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|same:password',
            'device_token' => 'required',
            'phone' => 'required|numeric',
            'longitude' => 'required',
            'latitude' => 'required',
            'image' => 'nullable|mimes:jpeg,jpg,png|max:3000|image',
            'nameOfLocation' => 'required|max:225|string',
            'language' =>'nullable|in:en,ar',

        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));


            $check =  User::where('phone', $request->phone)
            ->where('type' , 1)
            ->first();
        if ($check == null)
        {

        $all = [];

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'active' => 1,
            'type' => 1,
            'language' =>$request->language != null ?$request->language : 'ar',
            'password' => Hash::make($request->password),
            'image' => $request->image != null ? UploadImage($request->image, 'user', 'uploads/users') : 'default.png',

        ]);
        Location::create([
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'user_id' => $user->id,
            'name' =>$request->nameOfLocation,
            'main' => 1,
        ]);

        $user->update(['api_token' => generateApiToken($user->id, 10)]);


//         App\PhoneVerification::where('phone_number',$request->phone_number)->orderBy('id','desc')->delete();
        array_push($all, [
            'id' => intval($user->id),
            'name' => $user->name,
            'phone' => $user->phone,
            'longitude' => doubleval(Location::where(['user_id'=> $user->id],['main'=>1])->first()->longitude),
            'latitude' => doubleval(Location::where(['user_id'=> $user->id],['main'=>1])->first()->latitude),
            'active' => intval($user->active),
            'api_token' => $user->api_token,
            'nameOfLocation' => Location::where(['user_id'=> $user->id],['main'=>1])->first()->name,
            'image' =>  asset('uploads/users/' . $user->image) ,
            'language' => $user->language,
            'created_at' => $user->created_at->format('Y-m-d'),
        ]);


        //save_device_token....
        $created = ApiController::createUserDeviceToken($user->id, $request->device_token);

        return $user
            ? ApiController::respondWithSuccess($all)
            : ApiController::respondWithServerErrorArray();
        }else{
            $errors = [
                'key'=>'message',
                'value'=>$request->header('X-localization') == 'en' ?'this number is used before' :'رقم الهاتف مستخدم من قبل',
            ];
            return ApiController::respondWithErrorNOTFoundArray(array($errors));
        }
    }

    public function login(Request $request)
    {

        $rules = [
            'phone' => 'required',
            'password' => 'required',
            'device_token' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));


        if (Auth::attempt(['phone' => $request->phone, 'password' => $request->password , 'type'=> 1])) {

            if (Auth::user()->active == 0) {
                $errors = ['key' => 'message',
                    'value' => trans('messages.Sorry_your_membership_was_stopped_by_Management')
                ];
                return ApiController::respondWithErrorArray(array($errors));
            }
if(Auth::user()->type != 1 ) {
    $errors = ['key' => 'message',
        'value' => trans('messages.valiadate_Data')
    ];
    return ApiController::respondWithErrorArray(array($errors));
}
            //save_device_token....
            $created = ApiController::createUserDeviceToken(Auth::user()->id, $request->device_token);

            $all = User::where('phone', $request->phone)->first();
            $all->update(['api_token' => generateApiToken($all->id, 10)]);
            $user = User::where('phone', $request->phone)->first();

            $all = [];
            array_push($all, [
                'id' => intval($user->id),
                'name' => $user->name,
                'phone' => $user->phone,
                'active' => $user->active,
                'api_token' => $user->api_token,
                'image' => $user->image != null ? asset('uploads/users/' . $user->image) : asset('/uploads/users/default.png'),

                'created_at' => $user->created_at->format('Y-m-d'),
            ]);


            return $created
                ? ApiController::respondWithSuccess($all)
                : ApiController::respondWithServerErrorArray();
        } else {
            $errors = [
                'key' => 'message',
                'value' => trans('messages.Wrong_credential'),
            ];
            return ApiController::respondWithErrorNOTFoundArray(array($errors));
        }


    }

    public function getClientData(Request $request)
    {
        $user = User::find($request->user()->id);
        $data = [];
        array_push($data, [
            'id' => intval($user->id),
            'name' => $user->name,
            // 'language' =>$user->language,
            'phone' => $user->phone,
            'longitude' => doubleval(Location::where('user_id', $user->id)->first()->longitude),
            'latitude' => doubleval(Location::where('user_id', $user->id)->first()->latitude),
            'image' => $user->image != null ? asset('uploads/users/' . $user->image) : asset('/uploads/users/default.png'),
            'created_at' => $user->created_at->format('Y-m-d'),
        ]);

        return $user
            ? ApiController::respondWithSuccess($data)
            : ApiController::respondWithServerErrorArray();

    }

    public function editProfile(Request $request)
    {
        $rules = [
            'image' => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
            'name' => 'nullable',
            // 'language' =>'nullable|in:en,ar',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $user = User::where('id', $request->user()->id)->first();
        $updated = $user->update([
            'name' => $request->name == null ? $user->name : $request->name,
            // 'language' => $request->language,
        ]);
        if ($request->image) {
            $updated = $user->update([
                'image' => UploadImage($request->file('image'), 'user', '/uploads/users'),
            ]);
        }
        if ($request->longitude && $request->latitude) {
            $location = Location::where('user_id', $user->id)->first();
            $location->update([
                'longitude' => $request->longitude,
                'latitude' => $request->latitude,
            ]);
        }
        return $updated
            ? ApiController::respondWithSuccess([
                'id' => intval($user->id),
                'name' => $user->name,
                'phone' => $user->phone,
                // 'language' =>$user->language,
                'longitude' => doubleval(Location::where('user_id', $user->id)->first()->longitude),
                'latitude' => doubleval(Location::where('user_id', $user->id)->first()->latitude),
                'image' => $user->image != null ? asset('uploads/users/' . $user->image) : asset('/uploads/users/default.png'),
                'created_at' => $user->created_at->format('Y-m-d'),


            ])
            : ApiController::respondWithServerErrorObject();
    }

    public function sendSMS($jsonObj)
    {
        $contextOptions['http'] = array('method' => 'POST', 'header' => 'Content-type: application/json', 'content' => json_encode($jsonObj), 'max_redirects' => 0, 'protocol_version' => 1.0, 'timeout' => 10, 'ignore_errors' => TRUE);
        $contextResouce = stream_context_create($contextOptions);
        $url = "http://www.alfa-cell.com/api/msgSend.php";
        $arrayResult = file($url, FILE_IGNORE_NEW_LINES, $contextResouce);
        $result = $arrayResult[0];

        return $result;
    }


}
