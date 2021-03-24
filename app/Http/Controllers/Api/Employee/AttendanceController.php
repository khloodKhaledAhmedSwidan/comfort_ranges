<?php

namespace App\Http\Controllers\Api\Employee;

use App\Http\Controllers\Api\ApiController;
use App\Models\Setting;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class AttendanceController extends Controller
{
    //


    /*
     *  fingerprint -> 1 true
     *  fingerprint  -> 0 false
     *  add check-in for employee
     * $user->type -> 2 (employee)
     */
    public function checkIn(Request $request)
    {
        $rules = [
            'fingerprint' => 'required|numeric|in:0,1',
            'time' => 'required|date_format:H:i',
            'date' => 'required|date_format:Y-m-d',
            'latitude' => 'required',
            'longitude' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));


        $user = $request->user();


        $distance = Setting::find(1)->shift_range / 1000;
        $lat = $request->latitude;
        $lon = $request->longitude;
// check employee in place of work or not

        $is_inRange = $user->branch->selectRaw('*, ( 6367 * acos( cos( radians( ? ) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians( ? ) ) + sin( radians( ? ) ) * sin( radians( latitude) ) ) ) AS distance', [$lat, $lon, $lat])
            ->having('distance', '<=', $distance)
            ->first();

        if ($user->type == 2) {
            if ($is_inRange != null) {

                if ($request->fingerprint == 1) {
                    $checkIn = Shift::create([
                        'from' => $request->time,
                        'date' => $request->date,
                        'user_id' => auth()->user()->id,
                        'status' => 1,
                        'type' => 0,
                    ]);

                    $data = [];
                    array_push($data, [
                        'id' => intval($checkIn->id),
                        'time' => $checkIn->from,
                        'date' => $checkIn->date,
                        'status' => intval($checkIn->status),
                        'created_at' => $user->created_at->format('Y-m-d'),

                    ]);
                    return $checkIn
                        ? ApiController::respondWithSuccess($data)
                        : ApiController::respondWithServerErrorObject();

                }
                else {
                    $errors = ['key' => 'errorUser',
                        'value' => $request->header('X-localization') == 'en' ? 'The fingerprint is incorrect' : 'البصمة غير صحيحة'
                    ];
                    return ApiController::respondWithErrorClient(array($errors));
                }
            } else {
                $errors = ['key' => 'errorUser',
                    'value' => $request->header('X-localization') == 'en' ? 'You cannot check-in. Go to the workplace first' : 'لا يمكنك تسجيل الحضور .اذهب لمكان العمل اولا'
                ];
                return ApiController::respondWithErrorClient(array($errors));
            }


        } else {
            $errors = ['key' => 'errorUser',
                'value' => $request->header('X-localization') == 'en' ? 'check your data' : 'تأكد من بياناتك'
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }
    }

    public function checkOut(Request $request)
    {
        $rules = [
            'fingerprint' => 'required|numeric|in:0,1',
            'time' => 'required|date_format:H:i',
            'date' => 'required|date_format:Y-m-d',
            'latitude' => 'required',
            'longitude' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $user = $request->user();

// check employee in place of work or not
        $distance = Setting::find(1)->shift_range / 1000;
        $lat = $request->latitude;
        $lon = $request->longitude;

        $is_inRange = $user->branch->selectRaw('*, ( 6367 * acos( cos( radians( ? ) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians( ? ) ) + sin( radians( ? ) ) * sin( radians( latitude) ) ) ) AS distance', [$lat, $lon, $lat])
            ->having('distance', '<=', $distance)
            ->first();

        if ($user->type == 2) {
            if ($is_inRange != null) {
                if ($request->fingerprint == 1) {

                    $checkOut = $user->shifts()->where('from', '!=', null)->latest()->first();
                    $day = Carbon::parse($checkOut->date)->format('Y-m-d');
                    $checkOutDate = Carbon::parse($request->date)->format('Y-m-d');
                    if ($checkOut->to == null && $day == $checkOutDate) {
                        $checkOut->to = $request->time;
                        $checkOut->status = 1;
                        $checkOut->save();
                        $data = [];
                        array_push($data, [
                            'id' => intval($checkOut->id),
                            'time' => $checkOut->to,
                            'date' => $checkOut->date,
                            'status' => intval($checkOut->status),
                            'created_at' => $checkOut->created_at->format('Y-m-d'),

                        ]);
                        return ApiController::respondWithSuccess($data);
                    } else {
                        $errors = ['key' => 'errorUser',
                            'value' => $request->header('X-localization') == 'en' ? 'now, you cannot check-out' : 'لا يمكنك تسجيل الانصراف الان'
                        ];
                        return ApiController::respondWithErrorClient(array($errors));
                    }


                }
                else {
                    $errors = ['key' => 'errorUser',
                        'value' => $request->header('X-localization') == 'en' ? 'The fingerprint is incorrect' : 'البصمة غير صحيحة'
                    ];
                    return ApiController::respondWithErrorClient(array($errors));
                }
            } else {
                $errors = ['key' => 'errorUser',
                    'value' => $request->header('X-localization') == 'en' ? 'You cannot check-out. Go to the workplace first' : 'لا يمكنك تسجيل الانصراف .اذهب لمكان العمل اولا'
                ];
                return ApiController::respondWithErrorClient(array($errors));
            }


        } else {
            $errors = ['key' => 'errorUser',
                'value' => $request->header('X-localization') == 'en' ? 'check your data' : 'تأكد من بياناتك'
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }
    }

    /*
     * fun(). show all check-in && check-out
     *  fingerprint -> 1 true
     *  fingerprint  -> 0 false
     *  add check-in for employee
     * $user->type -> 2 (employee)
     */


    public function checkInCheckOut(Request $request){
        $user = $request->user();
        $allData = $user->shifts()->get();
        // check data found or not
        if ($allData) {
            // check-in && check-out

            $allData = $user->shifts()->orderBy('id','desc')->get();

            $data = [];
            foreach ($allData as $checkIn) {
                array_push($data, [
                    'id' => intval($checkIn->id),
                    'from' => $checkIn->from,
                    'to' => $checkIn->to,
                    'date' => $checkIn->date,
                    'status' => intval($checkIn->status),
                    'created_at' => $checkIn->created_at->format('Y-m-d'),
                ]);
            }
            return ApiController::respondWithSuccess($data);


        } else {
            $errors = ['key' => 'errorUser',
                'value' => $request->header('X-localization') == 'en' ? 'no Data' : 'لا توجد بيانات '
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }
    }


    /*
     * check_type  : 0 ->  attendance
     * check_type : 1 -> check-out
     */
    public function checkTheShowButtonToAddCheckIn(Request $request)
    {
        $user = $request->user();
        // check-in button
        if ($request->check_type == 0) {
            $checkIn = $user->shifts()->where('from', '!=', null)->latest()->first();
            $day = Carbon::parse($checkIn->date)->format('d');
            $todayDate = Carbon::today()->day;
            // if this is true -> show button and allow employee make a new check-in
            // false ->hide this button
            if ($todayDate != $day) {
                return ApiController::respondWithSuccess(['status' => 0]);
            } else {
                return ApiController::respondWithSuccess(['status' => 1]);
            }
        } else {
            // check-out button
            $checkOut = $user->shifts()->where('to', '!=', null)->latest()->first();
            $day = Carbon::parse($checkOut->date)->format('d');
            $todayDate = Carbon::today()->day;
            // if this is true -> show button and allow employee make a new check-out
            // false ->hide this button
            if ($todayDate != $day) {

                return ApiController::respondWithSuccess(['status' => 0]);
            } else {

                return ApiController::respondWithSuccess(['status' => 1]);
            }
        }
    }



    /*
     * show button check-in or check-out
     * // status => 1 show button
     * status =>0 hide button
     * check =>  0 ->check_in || 1 ->check_out
     *
     */

    public function checkShowButton(Request $request)
    {

        $rules = [

            'latitude' => 'required',
            'longitude' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $user = $request->user();

// check employee in place of work or not
        $distance = Setting::find(1)->shift_range / 1000;
        $lat = $request->latitude;
        $lon = $request->longitude;

        $is_inRange = $user->branch->selectRaw('*, ( 6367 * acos( cos( radians( ? ) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians( ? ) ) + sin( radians( ? ) ) * sin( radians( latitude) ) ) ) AS distance', [$lat, $lon, $lat])
            ->having('distance', '<=', $distance)
            ->first();

        // check-in button
        if ($is_inRange != null) {
            $lastAttendance     = $user->shifts()->latest()->first();
            if($lastAttendance == null){
                return ApiController::respondWithSuccess([
                    'status' => 1,
                    'check' => 0,
                ]);
            }
            if($lastAttendance->from != null && $lastAttendance->to != null){


                $day = Carbon::parse($lastAttendance->date)->format('Y-m-d');
                // $todayDate = Carbon::today()->addDay(1)->format('Y-m-d');
                $todayDate = Carbon::today()->format('Y-m-d');


                if($todayDate == $day){
                    // hide button of add check-in and check-out

                    return ApiController::respondWithSuccess([
                        'status' => 0,
                        'check' => 3,
                    ]);
                }else{
                    // show button of add check-in

                    return ApiController::respondWithSuccess([
                        'status' => 1,
                        'check' => 0,
                    ]);
                }



            }
            if($lastAttendance->from != null && $lastAttendance->to ==  null){
                $day = Carbon::parse($lastAttendance->date)->format('Y-m-d');
                // $todayDate = Carbon::today()->addDay(1)->format('Y-m-d');
                $todayDate = Carbon::today()->format('Y-m-d');


                if($todayDate == $day){
                    // show button of add check-out
                    return ApiController::respondWithSuccess(
                        [
                            'status' => 1,
                            'check' => 1,
                        ]
                    );

                }else{

                    // show button of add check-in
                    return ApiController::respondWithSuccess(
                        [
                            'status' => 1,
                            'check' => 0,
                        ]
                    );
                }
            }
        }else{
            $errors = ['key' => 'errorUser',
                'value' => $request->header('X-localization') == 'en' ? 'You are out of work. Go to the workplace first' : 'انت خارج نطاق العمل .اذهب لمكان العمل اولا'
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }


    }



    /*
     * show all attendance
     *
     * page 0 : check-in page
     * page 1 :check-out page
     */
    public function showCheckIn(Request $request)
    {
        $user = $request->user();
        $allData = $user->shifts()->get();
        // check data found or not
        if ($allData) {
            //  all attendance
            if ($request->page == 0) {
                $allData = $user->shifts()->where('from', '!=', null)->get();

                $data = [];
                foreach ($allData as $checkIn) {
                    array_push($data, [
                        'id' => intval($checkIn->id),
                        'time' => $checkIn->from,
                        'date' => $checkIn->date,
                        'status' => intval($checkIn->status),
                        'created_at' => $checkIn->created_at->format('Y-m-d'),
                    ]);
                }
                return ApiController::respondWithSuccess($data);

            } else {
                //  all check-out
                $allData = $user->shifts()->where('to', '!=', null)->get();

                $data = [];
                foreach ($allData as $checkOut) {
                    array_push($data, [
                        'id' => intval($checkOut->id),
                        'time' => $checkOut->to,
                        'date' => $checkOut->date,
                        'status' => intval($checkOut->status),
                        'created_at' => $checkOut->created_at->format('Y-m-d'),
                    ]);
                }
                return ApiController::respondWithSuccess($data);

            }
        } else {
            $errors = ['key' => 'errorUser',
                'value' => $request->header('X-localization') == 'en' ? 'no Data' : 'لا توجد بيانات '
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }


    }
}
