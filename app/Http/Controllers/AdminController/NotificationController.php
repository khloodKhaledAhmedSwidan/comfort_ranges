<?php

namespace App\Http\Controllers\AdminController;

use App\Models\Device;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    //

    public function generalNotificationPage()
    {
        return view('admin.notifications.general_notification');
    }

    public function generalNotification(Request $request)
    {
        $this->validate($request, [
            "title" => "required",
            "title_ar" => "required",
            "description_ar" => "required",
            "description" => "required",
        ]);
        // Create New Notification


        $users = User::where('active', 1)->get();
        foreach ($users as $user) {
            // Notification type 1 to public
            saveNotification($user->id, $request->title, $request->title_ar, $request->description, $request->description_ar, null);
        }
        $devicesTokens = Device::all()->pluck('device_token')->toArray();
        if ($devicesTokens) {
            if($user->language == 'en'){
                sendMultiNotification($request->title, $request->description, $devicesTokens);
            }else{
                sendMultiNotification($request->title_ar, $request->description_ar, $devicesTokens);
            }
      
        }
        flash(app()->getLocale()=='en'?'The notification has been sent to all app users':'تم ارسال الاشعار لجميع مستخدمي التطبيق')->success();
        return redirect()->back();

    }

    public function categoryNotificationPage()
    {
        return view('admin.notifications.category_notification');

    }

    public function categoryNotification(Request $request)
    {
        $this->validate($request, [
            "category" => "required|in:1,2",
            "title" => "required",
            "title_ar" => "required",
            "description_ar" => "required",
            "description" => "required",
        ]);
        // Create New Notification


        $users = User::where([

            ['type', $request->category],
            ['active', 1]
        ])->pluck('id');
        foreach ($users as $user) {
            // Notification type 1 to public
            saveNotification($user, $request->title, $request->title_ar, $request->description, $request->description_ar, null);
        }
        $devicesTokens = Device::all()->pluck('device_token')->toArray();
        if ($devicesTokens) {
            if($user->language == 'en'){
                sendMultiNotification($request->title, $request->description, $devicesTokens);
            }else{
                sendMultiNotification($request->title_ar, $request->description_ar, $devicesTokens);
            }
     
        }
        flash(app()->getLocale() == 'en'?'The notification has been sent to all application personnel':'تم ارسال الاشعار لجميع موظفي التطبيق')->success();
        return redirect()->back();
    }

    public function userNotificationPage()
    {
        return view('admin.notifications.user_notification');

    }

    public function userNotification(Request $request)
    {
        $this->validate($request, [
            "user_id" => "required",
            'user_id.*'=>'exists:users,id',
            "title" => "required",
            "title_ar" => "required",
            "description_ar" => "required",
            "description" => "required",
        ]);
        // Create New Notification




        foreach ($request->user_id as $one) {
            $user = User::find($one);
            $devicesTokens = Device::where('user_id', $user->id)
                ->pluck('device_token')
                ->toArray();
            if ($devicesTokens) {
                if($user->language == 'en'){
                    sendMultiNotification($request->title, $request->description, $devicesTokens);
                }else{
                    sendMultiNotification($request->title_ar, $request->description_ar, $devicesTokens);
                }
         
            }
            saveNotification($user->id, $request->title, $request->title_ar, $request->description, $request->description_ar, null);
        }
        flash(app()->getLocale() =='en' ?'Notification sent':'تم ارسال الاشعار ')->success();
        return back();
    



        // $user = User::where([

        //     ['id', $request->user_id],
        //     ['active', 1]
        // ])->latest()->first();
        // // Notification type 1 to public
        // saveNotification($user->id, $request->title, $request->title_ar, $request->description, $request->description_ar, null);

        // $devicesTokens = Device::where('user_id', $user->id)->pluck('device_token')->toArray();
        // if ($devicesTokens) {
        //     sendMultiNotification($request->title_ar, $request->description_ar, $devicesTokens);
        // }
        // flash(app()->getLocale() =='en' ?'Notification sent':'تم ارسال الاشعار ')->success();
        // return redirect()->back();
    }

}
