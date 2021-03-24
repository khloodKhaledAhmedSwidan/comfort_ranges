<?php

namespace App\Http\Controllers\Api\Employee;

use App\Http\Controllers\Api\ApiController;
use App\Models\Notification;
use Illuminate\Http\Request;
use Validator;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;

class NotificationController extends Controller
{
    //
    public function listNotification(Request $request)
    {
//        dd($request->user()->id);
        $notifications = Notification::Where('user_id', $request->user()->id)->select('id', 'title', 'description','title_ar','description_ar', 'order_id', 'created_at')->orderBy('id', 'desc')->get();
//        dd($notifications);
        if ($notifications) {
            $data = [];
            foreach ($notifications as $notification) {
                $startTime = Carbon::parse($notification->created_at);
                $endTime = Carbon::now();

                $totalDuration = $startTime->diffForHumans($endTime);
                array_push($data, [
                    'id' => intval($notification->id),
                    'order_id' => intval($notification->order_id),
                    'title' =>$request->header('X-localization') == 'en' ? $notification->title:$notification->title_ar,
                    'description' =>$request->header('X-localization') == 'en' ? $notification->description:$notification->description_ar,
                    'created_at' => $notification->created_at->format('Y-m-d'),
                    'totalDuration' => $totalDuration,
                ]);
            }

            return ApiController::respondWithSuccess($data);
        } else {
            $errors = ['key' => 'error',
                'value' => $request->header('X-localization') == 'en' ? ' there is no notifications' : 'لا يوجد اشعارات '
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }
//        return $this->respondWithSuccess($data);
    }

    public function deleteNotification(Request $request)
    {

        $rules = [
            'notification_id' => 'required|exists:notifications,id',

        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $data = Notification::Where('id', $request->notification_id)->where('user_id', $request->user()->id)->delete();
        return $data
            ? ApiController::respondWithSuccess([])
            : ApiController::respondWithServerErrorArray();
    }


}
