<?php

namespace App\Http\Controllers\Api\Employee;

use App\Http\Controllers\Api\ApiController;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Location;
use App\Models\PhoneVerification;
use App\Notifications\Newvisit;
use App\User;
use Validator;
use Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

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
                'mobile' => '',
                'password' => '',
                'sender' => '',
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
            $created = PhoneVerification::create([
                'code' => $code,
                'phone' => $phone
            ]);


            return ApiController::respondWithSuccess([]);


        }
        else {
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


        if(substr($request->phone, 0, 2) === '05'){
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
        }        else {
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

        if(substr($request->phone, 0, 2) === '05') {
            $result = substr($request->phone, 1);
            $phone = '00966' . $result;
            $code = mt_rand(1000, 9999);


            $jsonObj = array(
                'mobile' => '',
                'password' => '',
                'sender' => '',
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
                'phone' =>$phone
            ]);


            return $created
                ? ApiController::respondWithSuccess(trans('messages.success_send_code'))
                : ApiController::respondWithServerErrorObject();
        }  else {
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

        $user = User::where('phone', $request->phone)->where('type',2)->first();

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

        $user = User::where('phone', $request->phone)->where('verification_code', $request->code)->where('type',2)->first();
        if ($user) {
            $updated = User::where('phone', $request->phone)->where('verification_code', $request->code)->where('type',2)->update([
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

        $user = User::where('phone', $request->phone)->where('type',2)->first();
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
            'new_password' => 'required',
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



            if(Auth::user()->type != 2 ) {
                $errors = ['key' => 'message',
                    'value' => trans('messages.valiadate_Data')
                ];
                return ApiController::respondWithErrorArray(array($errors));
            }



            $phoneCheck = User::where('phone',$request->phone)->where('type',2)->whereNotIn('id',[$request->user()->id])->first();
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
            'mobile' => '',
            'password' => '',
            'sender' => '',
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

        $user = User::where('id', $request->user()->id)->where('verification_code', $request->code)->where('type',2)->first();
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



    public function cahngeLanguage(Request $request)
    {


        $rules = [

            'language' =>'nullable|in:en,ar',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $user = $request->user();

$user->language = $request->language;
$user->save();
return ApiController::respondWithSuccess(
['language'  =>  $user->language]);
 }

    public function register(Request $request)
    {

        $rules = [
            'name' => 'required|max:255',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|same:password',
            'device_token' => 'required',
            'phone' => 'required|numeric',
            'branch_id' => 'required|exists:branches,id',
            'category_id' => 'required|array',
            'image' => 'nullable|mimes:jpeg,jpg,png|max:3000|image',
            'language' =>'nullable|in:en,ar',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));


//if(Category::find($request->category_id)->branch_id  != $request->branch_id ) {
//    $errors = [
//        'key' => 'message',
//        'value' => trans('messages.wrong_id'),
//    ];
//    return ApiController::respondWithErrorNOTFoundArray(array($errors));
//}
$check =  User::where('phone', $request->phone)
->where('type' , 2)
->first();
if ($check == null)
{
        $all = [];

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'active' => 0,
            'type' => 2,
            'language' =>$request->language != null ?$request->language : 'ar',
            'branch_id' => $request->branch_id,
            'password' => Hash::make($request->password),
            'image' => $request->image != null ? UploadImage($request->image, 'user', 'uploads/users') : 'default.png',

        ]);
        foreach ($request->category_id as $cat) {
            $user->categories()->attach($cat);
        }


//
        $user->update(['api_token' => generateApiToken($user->id, 10)]);

        $allCategory = [];
        foreach ($request->category_id as $cat) {
            $cat = Category::find($cat);
            array_push($allCategory, [
                'id' => intval($cat->id),
                'name' => $request->header('X-localization') == 'en' ? $cat->name : $cat->name_ar,
                'image' => $cat->image != null ? asset('/uploads/categories/' . $cat->image) : null
            ]);
        }
//         App\PhoneVerification::where('phone_number',$request->phone_number)->orderBy('id','desc')->delete();
        array_push($all, [
            'id' => intval($user->id),
            'name' => $user->name,
            'phone' => $user->phone,
            'active' => intval($user->active),
            'api_token' => $user->api_token,
            'branch_id' => intval($user->branch_id),
            'branch' => $user->branch_id != null ? ($request->header('X-localization') == 'en' ? Branch::find($user->branch_id)->name : Branch::find($user->branch_id)->name_ar) : null,
            'categories' => $allCategory,
            'language' => $user->language,
            'image' =>  asset('uploads/users/' . $user->image) ,

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


        if (Auth::attempt(['phone' => $request->phone, 'password' => $request->password,'type' => 2])) {

            if (Auth::user()->active == 0) {
                $errors = ['key' => 'message',
                    'value' => trans('messages.Sorry_your_membership_was_stopped_by_Management')
                ];
                return ApiController::respondWithErrorArray(array($errors));
            }
            if(Auth::user()->type != 2 ) {
                $errors = ['key' => 'message',
                    'value' => trans('messages.valiadate_Data')
                ];
                return ApiController::respondWithErrorArray(array($errors));
            }
            //save_device_token....
            $created = ApiController::createUserDeviceToken(Auth::user()->id, $request->device_token);

            $all = User::where('phone', $request->phone)->where('type',2)->first();
            $all->update(['api_token' => generateApiToken($all->id, 10)]);
            $user = User::where('phone', $request->phone)->where('type',2)->first();


            $allCategory = [];
            foreach ($user->categories()->get() as $cat) {
//                $cat = Category::find($cat);
                array_push($allCategory, [
                    'id' => intval($cat->id),
                    'name' => $request->header('X-localization') == 'en' ? $cat->name : $cat->name_ar,
                    'image' => $cat->image != null ? asset('/uploads/categories/' . $cat->image) : null,
                ]);
            }
            $all = [];
            array_push($all, [
                'id' => intval($user->id),
                'name' => $user->name,
                'phone' => $user->phone,
                'image' => $user->image != null ? asset( 'uploads/users/'.$user->image) : asset('/uploads/users/default.png'),
                'active' => intval($user->active),
                'api_token' => $user->api_token,
                'branch_id' => intval($user->branch_id),
                'branch' => $user->branch_id != null ? ($request->header('X-localization') == 'en' ? Branch::find($user->branch_id)->name : Branch::find($user->branch_id)->name_ar) : null,
                'categories' => $allCategory,
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

    public function getEmployeeData(Request $request)
    {
        $user = User::find($request->user()->id);
        $allCategory = [];
        if ($user->categories()->get()) {
            $categories = $user->categories()->get();

            foreach ($categories as $category) {
                array_push($allCategory, [
                    'id' => intval($category->id),
                    'name' => $request->header('X-localization') == 'en' ? $category->name : $category->name_ar,
                    'image' => $category->image != null ? asset('/uploads/categories/' . $category->image) : null
                ]);
            }
        }

        $data = [];
        array_push($data, [
            'id' => intval($user->id),
            'name' => $user->name,
            'phone' => $user->phone,
            'branch_id' => intval($user->branch_id),
            'branch' => $user->branch_id != null ? ($request->header('X-localization') == 'en' ? Branch::find($user->branch_id)->name : Branch::find($user->branch_id)->name_ar) : null,
            'categories' => $allCategory,
            // 'language' => $user->language,
            'image' => $user->image != null ? asset('/uploads/users/' . $user->image) : asset('/uploads/users/default.png'),
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
            'branch_id' => 'exists:branches,id',
            'category_id' => 'array|exists:categories,id'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $user = User::where('id', $request->user()->id)->first();
        if ($request->category_id) {
//            foreach ($request->category_id as $cat) {
                $user->categories()->sync($request->category_id );
//            }
        }

        $updated = $user->update([
            'name' => $request->name == null ? $user->name : $request->name,
            'branch_id' => $request->branch_id == null ? $user->branch_id : $request->branch_id,
            // 'language' => $request->language,
        ]);
if($request->image){
    $updated = $user->update([
        'image' =>UploadImage($request->file('image'), 'users', '/uploads/users'),
    ]);
}
        $allCategory = [];
        if ($user->categories()->get()) {
            $categories = $user->categories()->get();

            foreach ($categories as $category) {
                array_push($allCategory, [
                    'id' => intval($category->id),
                    'name' => $request->header('X-localization') == 'en' ? $category->name : $category->name_ar,
                    'image' => $category->image != null ? asset('/uploads/categories/' . $category->image) : null
                ]);
            }
        }

        return $updated
            ? ApiController::respondWithSuccess([
                'notification' => trans('messages.save_profile'),
                'id' => intval($user->id),
                'name' => $user->name,
                // 'language' =>$user->language,
                'image' => $user->image != null ? asset('/uploads/users/' . $user->image) : asset('/uploads/users/default.png'),
                'branch_id' => intval($user->branch_id),
                'branch' => $user->branch_id != null ? ($request->header() == 'en' ? Branch::find($user->branch_id)->name : Branch::find($user->branch_id)->name_ar) : null,
                'categories' => $allCategory,

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
