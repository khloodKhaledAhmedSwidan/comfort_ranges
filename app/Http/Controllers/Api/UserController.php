<?php

namespace App\Http\Controllers\Api;

use App\PaymentValue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Validator;
use App\User;
use App;
use Auth;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function postPayment(Request $request)
    {
//        $rules = [
//            'price' => 'required',
//        ];
//
//        $validator = Validator::make($request->all(), $rules);
//        if ($validator->fails())
//            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));
        $payment = PaymentValue::find(1);
        $user = User::find($request->user()->id);
        $token = "cxu2LdP0p0j5BGna0velN9DmzKJTrx3Ftc0ptV8FmvOgoDqvXivkxZ_oqbi_XM9k7jgl3SUriQyRE2uaLWdRumxDLKTn1iNglbQLrZyOkmkD6cjtpAsk1_ctrea_MeOQCMavsQEJ4EZHnP4HoRDOTVRGvYQueYZZvVjsaOLOubLkdovx6STu9imI1zf5OvuC9rB8p0PNIR90rQ0-ILLYbaDZBoQANGND10HdF7zM4qnYFF1wfZ_HgQipC5A7jdrzOoIoFBTCyMz4ZuPPPyXtb30IfNp47LucQKUfF1ySU7Wy_df0O73LVnyV8mpkzzonCJHSYPaum9HzbvY5pvCZxPYw39WGo8pOMPUgEugtaqepILwtGKbIJR3_T5Iimm_oyOoOJFOtTukb_-jGMTLMZWB3vpRI3C08itm7ealISVZb7M3OMPPXgcss9_gFvwYND0Q3zJRPmDASg5NxRlEDHWRnlwNKqcd6nW4JJddffaX8p-ezWB8qAlimoKTTBJCe5CnjT4vNjnWlJWscvk38VNIIslv4gYpC09OLWn4rDNeoUaGXi5kONdEQ0vQcRjENOPAavP7HXtW1-Vz83jMlU3lDOoZsdEKZReNYpvdFrGJ5c3aJB18eLiPX6mI4zxjHCZH25ixDCHzo-nmgs_VTrOL7Zz6K7w6fuu_eBK9P0BDr2fpS";
        $data = "{\"PaymentMethodId\":\"2\",\"CustomerName\": \"$user->name\",\"DisplayCurrencyIso\": \"SAR\", \"MobileCountryCode\":\"+966\",\"CustomerMobile\": \"01119399781\",
                \"CustomerEmail\": \"$user->email\",\"InvoiceValue\": $payment->value,\"CallBackUrl\": \"http://127.0.0.1:8000/check-status\",\"ErrorUrl\": \"http://127.0.0.1:8000\",\"Language\": \"ar\",
                \"CustomerReference\" :\"ref 1\",\"CustomerCivilId\":12345678,\"UserDefinedField\": \"Custom field\",\"ExpireDate\": \"\",\"CustomerAddress\" :{\"Block\":\"\",\"Street\":\"\",\"HouseBuildingNo\":\"\",
                \"Address\":\"\",\"AddressInstructions\":\"\"},\"InvoiceItems\": [{\"ItemName\": \"$user->name\",\"Quantity\": 1,\"UnitPrice\": $payment->value}]}";
//        dd($data);
        $fatooraRes = MyFatoorah($token, $data);
        $result = json_decode($fatooraRes);
//         dd($result);
        if ($result->IsSuccess === true) {


//            return redirect($result->Data->PaymentURL);
            $user = User::find($request->user()->id);
            if ($result->IsSuccess === true) {
                $user->update([
                    'invoice_id' => $result->Data->InvoiceId
                ]);
                $all = [];
                array_push($all , [
                    'key' => 'user_converted_to_paid',
                    'payment_url' => $result->Data->PaymentURL,
                ]);
                return ApiController::respondWithSuccess($all);
            }
        }
    }

    public  function fatooraStatus(){
        $token = "cxu2LdP0p0j5BGna0velN9DmzKJTrx3Ftc0ptV8FmvOgoDqvXivkxZ_oqbi_XM9k7jgl3SUriQyRE2uaLWdRumxDLKTn1iNglbQLrZyOkmkD6cjtpAsk1_ctrea_MeOQCMavsQEJ4EZHnP4HoRDOTVRGvYQueYZZvVjsaOLOubLkdovx6STu9imI1zf5OvuC9rB8p0PNIR90rQ0-ILLYbaDZBoQANGND10HdF7zM4qnYFF1wfZ_HgQipC5A7jdrzOoIoFBTCyMz4ZuPPPyXtb30IfNp47LucQKUfF1ySU7Wy_df0O73LVnyV8mpkzzonCJHSYPaum9HzbvY5pvCZxPYw39WGo8pOMPUgEugtaqepILwtGKbIJR3_T5Iimm_oyOoOJFOtTukb_-jGMTLMZWB3vpRI3C08itm7ealISVZb7M3OMPPXgcss9_gFvwYND0Q3zJRPmDASg5NxRlEDHWRnlwNKqcd6nW4JJddffaX8p-ezWB8qAlimoKTTBJCe5CnjT4vNjnWlJWscvk38VNIIslv4gYpC09OLWn4rDNeoUaGXi5kONdEQ0vQcRjENOPAavP7HXtW1-Vz83jMlU3lDOoZsdEKZReNYpvdFrGJ5c3aJB18eLiPX6mI4zxjHCZH25ixDCHzo-nmgs_VTrOL7Zz6K7w6fuu_eBK9P0BDr2fpS";        $PaymentId = \Request::query('paymentId');
        $resData = MyFatoorahStatus($token, $PaymentId);
        $result = json_decode($resData);
//         dd($result);
        if($result->IsSuccess === true && $result->Data->InvoiceStatus === "Paid"){
            $InvoiceId = $result->Data->InvoiceId;
            $order = App\User::where('invoice_id',$InvoiceId)->first();
            $payment = PaymentValue::find(1)->value;
            $order->update([
                     'subscription'=>'1',
                     'subscription_date' => Carbon::now(),
                     'payment_value' => $payment,
                ]);
            return redirect()->to('/fatoora/success');
        }
    }











    public function get_payment_value()
    {
        $payment = PaymentValue::find(1);
        $success = ['key'=>'get_payment_value',
            'value'=> intval($payment->value),
        ];
        return $payment
            ? ApiController::respondWithSuccess($success)
            : ApiController::respondWithServerErrorObject();
    }




    //get all chats
    public function get_all_chats(Request $request)
    {
        $users = Chat::whereUser_id($request->user()->id)
            ->orderBy('id' , 'desc')
            ->get();
        if ($users->count() > 0)
        {
            $data = [];
            foreach ($users->unique('chat_id') as $user) {


                array_push($data , [
                    'id'           => intval($user->chat_id),
                    'chat_id'      => $user->chat_id,
                    'user_id'      => $user->user_id == $request->user()->id ? $user->second->id:$user->user_id  ,
                    'name'         => $user->user_id == $request->user()->id ? $user->second->type == 1 ? $user->second->guest_name : $user->second->name :$user->name ,
                    'user_photo'   => $user->user_id == $request->user()->id ? asset('/uploads/users/'.$user->second->photo): $user->user_photo ,
                    'created_at'   => $user->created_at->format('Y-m-d'),
                ]);
            }
            return $users
                ? ApiController::respondWithSuccess($data)
                : ApiController::respondWithServerErrorObject();
        }else{
            $errors = ['key'=>'get_all_chats',
                'value'=> 'لا توجد محادثات'
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }

    }

    //close_chat

    public function close_chat(Request $request , $chat_id)
    {

        $chats = Chat::where('chat_id',$chat_id)->get();
        if($chats)
        {
            foreach($chats as $chat){
                $chat->delete();

            }
            $data = [
                'key' => 'close_chat',
                'value' => 'تم غلق الشات '
            ];
            return  ApiController::respondWithSuccess($data);
        }

        else{
            $errors = ['key'=>'close_chat',
                'value'=> 'لا توجد محادثة'
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }


    }
    public function money(Request $request , $chat_id)
    {

        $money = PaymentValue::find(1);
        if($money)
        {

            $data = [
                'key' => 'money',
                'value' => intval($money->status),
            ];
            return  ApiController::respondWithSuccess($data);
        }
    }
    public  function upload_excel_file(Request $request)
    {
        $rules = [
            'excel_file'                 => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));
                Excel::import(new App\Imports\StudentImport,$request->file('excel_file'));

        $all = [];
        $data = App\ExcelStudentData::all();
        foreach ($data as $value)
        {
            array_push($all , [
                'id'  => intval($value->id),
                'civil_record' => $value->national_id,
                'name'     => $value->name,
                'created_at'  => $value->created_at->format('Y-m-d')
            ]);
        }
        foreach ($data as $value)
        {
            $value->delete();
        }
        return  ApiController::respondWithSuccess($all);
    }
    public function download_excel_file(Request $request)
    {
        $all = [];
        array_push($all , [
            'file'  => asset('/uploads/backup/kushoof.xlsx'),
        ]);
        return  ApiController::respondWithSuccess($all);
    }



    public function updateVerifications(Request $request, $id)
    {
        // dd($request->all());
        $user = User::find($id);
        if (!$user == null) {
            $user->update([
                'verified' => !$user->verified
            ]);

            if($request->state == '0')
            {
                $devicesTokens = UserDevice::where('user_id', $user->id)
                    ->get()
                    ->pluck('device_token')
                    ->toArray();
                // dd($devicesTokens);
                if ($devicesTokens) {
                    sendMultiNotification("طلب التوثيق", "تمت الموافقة على طلب توثيق حسابك ", $devicesTokens , 10);
                }
                saveNotification($user->id, "طلب التوثيق","تمت الموافقة على طلب توثيق حسابك " , null , '10');
            }else{
                $devicesTokens = UserDevice::where('user_id', $user->id)
                    ->get()
                    ->pluck('device_token')
                    ->toArray();
                // dd($devicesTokens);
                if ($devicesTokens) {
                    sendMultiNotification(" التوثيق", "تمت  الغاء توثيق حسابك ", $devicesTokens , 11);
                }
                saveNotification($user->id, " التوثيق","تمت  الغاء توثيق حسابك " , null , '10');
            }

            flash('تم تعديل الحساب بنجاح');
            return back();
        } else {
            flash('حدث خطأ برجاء المحاولة لاحقا');
            return back();
        }
    }
}
