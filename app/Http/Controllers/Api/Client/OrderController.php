<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Api\ApiController;
use App\Models\Category;
use App\Models\Complaint;
use App\Models\Device;
use App\Models\Location;
use App\Models\Order;
use App\Models\OrderShift;
use App\Models\Rate;
use App\Models\RejectedDate;
use App\Models\Sentence;
use App\Models\Setting;
use App\Models\WithOrder;
use App\Models\WithOrderCategory;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class OrderController extends Controller
{
 // show order for client or employee 

public function showOrder(Request $request){
    $rules = [
        'order_id' => 'required|exists:orders,id',

    ];

    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails())
        return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));
       
        $order = Order::find($request->order_id); 


        $data = [];
   


            $img = [];
            foreach ($order->images()->get() as $image) {
                array_push($img, [
                    'image' => asset('uploads/orders/' . $image->image),
                ]);
            }
            $anotherServicesWithOrder = [];
            $anotherServices = $order->withOrderCategories()->count();
            if ($anotherServices > 0) {
                $anotherServices = $order->withOrderCategories()->get();
                foreach ($anotherServices as $anotherService) {
                    array_push($anotherServicesWithOrder, [
                        'id' => intval($anotherService->id),
                        'order_id' => intval($anotherService->order_id),
                        'category_id' => intval($anotherService->category_id),
                        'nameOfCategory' => $request->header('X-localization') == 'en' ? Category::find($anotherService->category_id)->name : Category::find($anotherService->category_id)->name_ar,

                    ]);
                }

            }
            $restOfOrder = [];
    
            $timeOrders = $order->timeOrders()->count();
            if($timeOrders > 0){
                $timeOrderss = $order->timeOrders()->get();
            foreach($timeOrderss as $timeOrder){
                array_push($restOfOrder , [
                    'id' => intval($timeOrder->id),
                    'order_shift_id' => intval($timeOrder->order_shift_id),
                    'fromOrder' => OrderShift::find($timeOrder->order_shift_id)->from,
                    'toOrder' => OrderShift::find($timeOrder->order_shift_id)->to,
                    'order_id' => intval($timeOrder->order_id),
                    'start' => $timeOrder->start,
                    'end' => $timeOrder->end,
                    'date' =>$timeOrder->date
            
            
                ]);
            }
            }
        
            array_push($data, [
                'id' => intval($order->id),
                'category_id' => intval($order->category_id),
                'category' => $request->header('X-localization') == 'en' ? Category::find($order->category_id)->name : Category::find($order->category_id)->name_ar,
                'categoryImage' => Category::find($order->category_id)->image != null ? asset('/uploads/categories/' . Category::find($order->category_id)->image) : null,
                'user_id' => intval($order->user_id),
                'user' => User::find($order->user_id)->name,
                'userPhone' => User::find($order->user_id)->phone,
                'price' => number_format((float)$order->price, 2, '.', ''),
                'from' => $order->from,
                'to' => $order->to,
                'employee_id' => intval($order->employee_id),
                'employee' => User::find($order->employee_id)->name,
                'note' => $order->note,
                'status' => intval($order->status),
                'tax' => intval($order->tax),
                'date' => $order->date,
                'order_shift_id' => intval($order->order_shift_id),
                'fromOrderShift' => OrderShift::find($order->order_shift_id)->from,
                'toOrderShift' => OrderShift::find($order->order_shift_id)->to,
                'employee_note' => $order->employee_note,
                'work_duration' => $order->work_duration,
                'images' => $img,
                'longitude' => $order->longitude,
                'latitude' => $order->latitude,
                'location_id' => intval($order->location_id),
                'locationName' => $order->location_id == null || $order->location_id == 0 ? null : ($request->header('X-localization') == 'en' ? Location::find($order->location_id)->name : Location::find($order->location_id)->name),
                'locationLat' => $order->location_id == null || $order->location_id == 0 ? null : Location::find($order->location_id)->latitude,
                'locationLong' => $order->location_id == null || $order->location_id == 0 ? null : Location::find($order->location_id)->longitude,
                'material_cost' => number_format((float)$order->material_cost, 2, '.', ''),
                'valueOfTax' => number_format((float)Setting::find(1)->tax, 2, '.', ''),
                'total' => number_format((float)$order->total, 2, '.', ''),
                'is_paid' => intval($order->is_paid),
                'vedio' => $order->vedio != null ? asset('uploads/orders/vedio/' . $order->vedio) : null,
                'anotherServicesWithOrder' => $anotherServicesWithOrder,
                'notes_on_what_was_done' => $order->notes_on_what_was_done,
                'complete_in_another_day' => intval($order->complete_in_another_day),
                'restOfOrder' =>$restOfOrder,
            ]);
    
        return ApiController::respondWithSuccess($data);

}

        // check client rate order paid order or not and rejected order too
    public  function RatedOrNot(Request $request){
        $rules = [
            'order_id' => 'required|exists:orders,id',

        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $order = Order::find($request->order_id);
        if(($order->status == 3 && $order->is_paid == 1)|| ($order->status == 4 && $order->is_paid == 1) || ($order->status == 3 && $order->is_paid == 2 ) || ($order->status == 4 && $order->is_paid == 2)){

        $order->rate()->count();
if($order->rate()->count() > 0){


    $data = [];
    array_push($data, [
        'is_paid' => intval($order->is_paid),
        'rate' => 1, // rate previous



    ]);
    return ApiController::respondWithSuccess($data);

}else{
    $data = [];
    array_push($data, [
        'is_paid' => intval($order->is_paid),
        'rate' => 0, // let client rate our services



    ]);
    return ApiController::respondWithSuccess($data);
}


        }else{
            $errors = ['key' => 'message',
                'value' => $request->header('X-localization') == 'en' ? 'check order status or paid or not' : 'تأكد من حاله الطلب او حاله الدفع'
            ];
            return ApiController::respondWithErrorArray(array($errors));

        }

    }


    /*
    * cancel order before his date is coming depend on period specified by administrator 
    *cancel_order column in settings table
    */

    public function cancelOrder(Request $request){
        $rules = [
            'order_id' => 'required|exists:orders,id',
 
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));


            $settingCancelOrder =  Setting::find(1)->cancel_order;
$order  = Order::find($request->order_id);
if($order->status  !=  1){
    $errors = ['key' => 'message',
    'value' => $request->header('X-localization') == 'en' ? 'Cancel the Order for new orders only' : 'الغاء الطلب للطلبات الجديده فقط'
];
return ApiController::respondWithErrorArray(array($errors));
}

    $orderDate = Carbon::parse($order->date)->format('d');
    $today = Carbon::today()->format('d');

    // compare between day bigger than day of order
    if($today >= $orderDate) {
        $errors = ['key' => 'message',
        'value' => $request->header('X-localization') == 'en' ? 'you cannot cancel your order' : 'لا يمكن الغاء الطلب'
    ];
    return ApiController::respondWithErrorArray(array($errors));
    }

    // this case you can cancel your order
if($today < $orderDate){
$difference = $orderDate - $today;
if($difference >= $settingCancelOrder ){
$order->delete();
return ApiController::respondWithSuccess([
    'message'=> $request->header('X-localization') == 'en' ? 'canceled order successfully' : 'تم الغاء طلبك بنجاح'
    ]);

}else{
    $errors = ['key' => 'message',
    'value' => $request->header('X-localization') == 'en' ? 'The application cannot be canceled because it exceeded the period specified for cancellation by the administration' : 'لا يمكن الغاء الطلب لانه تجاوز المدة المحدده للالغاء من قبل الادارة'
];
return ApiController::respondWithErrorArray(array($errors));  
}
}
    }

    // 1 expresses send order
    // 0 expresses  ordering another service
    // cart
    public function collectOrder(Request $request)
    {
        $rules = [
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|exists:order_shifts,id',
            "location_id" => 'required|exists:locations,id',
//            "longitude" => 'sometimes',
//            "latitude" => 'sometimes',
            "image" => 'nullable',
            'vedio' => 'nullable|mimes:mp4,ogx,oga,ogv,ogg,webm',
            "image*" => 'mimes:jpeg,bmp,png,jpg,gif,ico,psd,webp,tif,tiff|max:5000',
            'note' => 'string',
            'category_id' => 'required|exists:categories,id',
            'tax' => 'required',
            '*.categories'=>'nullable|exists:categories,id',
            'photo'  =>'nullable|mimes:jpeg,bmp,png,jpg,gif,ico,psd,webp,tif,tiff|max:5000',

        ];


        if ($request->image) {
            $allImage = $request->image;

            foreach ($allImage as $image) {
                if ($image->getClientOriginalExtension() == "jfif") {


                    $errors = ['key' => 'message',
                        'value' => $request->header('X-localization') == 'en' ? 'This image format is not supported' : 'صيغه هذه الصوره غير  مدعومه'
                    ];
                    return ApiController::respondWithErrorArray(array($errors));

                }
            }
        }
// check tax acceptance
        if ($request->tax != 1) {
            $errors = ['key' => 'message',
                'value' => $request->header('X-localization') == 'en' ? 'The value of the scout must be approved' : 'يجب الموافقه علي قيمة الكشفية'
            ];
            return ApiController::respondWithErrorArray(array($errors));
        }

       if($request->photo != null && $request->image != null )
        {
            $errors = ['key' => 'message',
            'value' => $request->header('X-localization') == 'en' ? 'Photos from Gallery and Camera cannot be entered' : 'لا يمكن ادخال صور من الاستوديو والكاميرا '
        ];
        return ApiController::respondWithErrorArray(array($errors));
        }

        $today = Carbon::now();
        // dd($today);
//        $today = Carbon::today()->addDay(1);

        // check send order in expired date or note
        if (($request->date > $today->format('Y-m-d')) == false) {
            $errors = ['key' => 'message',
                'value' => $request->header('X-localization') == 'en' ? 'You cannot add a previous date You cannot submit a request on an earlier date' : 'لا يمكنك ارسال طلب في تاريخ سابق'
            ];
            return ApiController::respondWithErrorArray(array($errors));
        }
        // check send order in rejected date or note
        $rejectedDate = RejectedDate::where('reject_date', $request->date)->first();
        if ($rejectedDate) {
            $errors = ['key' => 'message',
                'value' => $request->header('X-localization') == 'en' ?'This date cannot be booked because:'. $rejectedDate->reason_en :'لا يمكن حجز هذا التاريخ بسبب : ' . $rejectedDate->reason
            ];
            return ApiController::respondWithErrorArray(array($errors));
        }


        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $category = Category::find($request->category_id);
        $users = $category->users()->where([
            ['type', 2],
            ['active', 1],
        ])->get();
//        $users = User::where('type', 2)->where('active', 1)->get();

        $order = null;
        foreach ($users as $user) {
//            $categories = $user->categories()->get();
//            foreach ($categories as $category) {
//                if ($category->id == $request->category_id) {
//                    $orderCount = $user->employeeOrders()->where('employee_id', $user->id)->where('order_shift_id', $request->time)->where('status', 1)->orWhere('status', 2)->get();
            $orderCountwherestatus1 = $user->employeeOrders()->where([
                ['employee_id', $user->id],
                ['order_shift_id', $request->time],
                ['status', 1],

            ])
                ->count();
            $orderCountwherestatus2 = $user->employeeOrders()->where([
                ['employee_id', $user->id],
                ['order_shift_id', $request->time],
                ['status', 2],

            ])
                ->count();
                $orderCountwherestatus5 = $user->employeeOrders()->where([
                    ['employee_id', $user->id],
                    ['order_shift_id', $request->time],
                    ['status', 5],
    
                ])
                    ->count();
//                    dd($orderCountwherestatus1 + $orderCountwherestatus2);
            $count_of_order_in_period = $user->available_orders != 0 ? $user->available_orders: Setting::find(1)->count_of_order_in_period;

            if (($orderCountwherestatus1 + $orderCountwherestatus2 +  $orderCountwherestatus5) < $count_of_order_in_period) {
//                        $markAnotherOrderCount = Order::where([
//                            ['status', 1],
//                            ['user_id', $request->user()->id],
//                            ['date', $request->date],
//                            ['order_shift_id', $request->time]
//                        ])->count();
//                        $markAnotherOrder = Order::where([
//                            ['status', 1],
//                            ['user_id', $request->user()->id],
//                            ['date', $request->date],
//                            ['order_shift_id', $request->time]
//                        ])->get();
//                        $anotherOrderArray = [];

                $order = Order::create([
                    'date' => $request->date,
                    'order_shift_id' => $request->time,
                    'note' => $request->note,
                    'tax' => $request->tax,
                    'category_id' => $request->category_id,
                    'user_id' => $request->user()->id,
                    'status' => 1,
                    'employee_id' => $user->id,
//                            'longitude' => $request->longitude != null ? $request->longitude : null,
//                            'latitude' => $request->latitude != null ? $request->latitude : null,
                    'location_id' => $request->location_id != null ? $request->location_id : null,
                ]);

//                        if ($markAnotherOrderCount > 0) {
//                            foreach ($markAnotherOrder as $anotherOrder) {
//                                WithOrder::create([
//                                    'main_order_id' => $order->id,
//                                    'order_id' => $anotherOrder->id,
//                                ]);
//                            }
//                        }
                if($request->categories){
                foreach ($request->categories as $cat)
                    $anotherServicesWithOrderr = WithOrderCategory::create([
                        'order_id' => $order->id,
                        'category_id' => $cat,
                    ]);

                }


                $anotherServicesWithOrder = [];
                $anotherServices = $order->withOrderCategories()->count();
                if ($anotherServices > 0) {
                    $anotherServices = $order->withOrderCategories()->get();
                    foreach ($anotherServices as $anotherService) {
                        array_push($anotherServicesWithOrder, [
                            'id' => intval($anotherService->id),
                            'order_id' => intval($anotherService->order_id),
                            'category_id' => intval($anotherService->category_id),
                            'nameOfCategory' => $request->header('X-localization') == 'en' ? Category::find($anotherService->category_id)->name : Category::find($anotherService->category_id)->name_ar,

                        ]);
                    }

                }
                if($request->image){
                    foreach ($request->image as $img) {
                        $order->images()->create([
                            'image' => UploadImage($img, 'order', 'uploads/orders'),

                        ]);
                    }
                }

                if($request->photo){
              
                        $order->images()->create([
                            'image' => UploadImage($request->photo, 'order', 'uploads/orders'),

                        ]);
                 
                }
                if ($request->vedio) {
                    $order->update([
                        'vedio' => UploadImage($request->vedio, 'order', 'uploads/orders/vedio'),

                    ]);
                }
                $img = [];
                if($order->images()->count() > 0){
                    foreach ($order->images()->get() as $image) {
                        array_push($img, [
                            'image' => asset('uploads/orders/' . $image->image),
                        ]);
                    }
                }

                $restOfOrder = [];
    
                $timeOrders = $order->timeOrders()->count();
                if($timeOrders > 0){
                    $timeOrderss = $order->timeOrders()->get();
                foreach($timeOrderss as $timeOrder){
                    array_push($restOfOrder , [
                        'id' => intval($timeOrder->id),
                        'order_shift_id' => intval($timeOrder->order_shift_id),
                        'fromOrder' => OrderShift::find($timeOrder->order_shift_id)->from,
                        'toOrder' => OrderShift::find($timeOrder->order_shift_id)->to,
                        'order_id' => intval($timeOrder->order_id),
                        'start' => $timeOrder->start,
                        'end' => $timeOrder->end,
                        'date' =>$timeOrder->date
                
                
                    ]);
                }
                }

//                         send notification to drivers
                $devicesTokens = Device::where('user_id', $order->employee_id)
                    ->get()
                    ->pluck('device_token')
                    ->toArray();

                if ($devicesTokens) {
//                         $order->employee_id

$request->header('X-localization') == 'en'  ?
sendMultiNotification( "  order from  " . ' : '.$request->user()->name, "Scan for new orders ", $devicesTokens)
: sendMultiNotification( "طلب  من  " . ' : '.$request->user()->name, " تفحص الطلبات الجديدة", $devicesTokens);
                }
                saveNotification($order->employee_id, $request->user()->name . 'order from',   "طلب  من ". ' : ' . $request->user()->name, 'see your new orders', " تفحص الطلبات الجديدة", $order->id);




//send notifications to client 
//                         send notification to drivers
$devicesTokens = Device::where('user_id', $order->user_id)
->get()
->pluck('device_token')
->toArray();

if ($devicesTokens) {
//                         $order->employee_id
$request->header('X-localization') == 'en'  ?
sendMultiNotification( 'Your order has been sent ', " Your order has been sent successfully   ", $devicesTokens)
: sendMultiNotification( 'تم ارسال طلبك بنجاح', " تم ارسال طلبك", $devicesTokens);
}
saveNotification($order->user_id,'your order send successfully',   'تم ارسال طلبك بنجاح', 'see your new orders', " تفحص الطلبات الجديدة", $order->id);


                $data = [];
                array_push($data, [
                    'id' => intval($order->id),
                    'category_id' => intval($order->category_id),
                    'category' => $request->header('X-localization') == 'en' ? Category::find($order->category_id)->name : Category::find($order->category_id)->name_ar,
                    'user_id' => intval($order->user_id),
                    'user' => User::find($order->user_id)->name,
                    'userPhone' => User::find($order->user_id)->phone,

                    'price' => number_format((float)$order->price, 2, '.', ''),
                    'from' => $order->from,
                    'to' => $order->to,
                    'employee_id' => intval($order->employee_id),
                    'employee' => User::find($order->employee_id)->name,
                    'note' => $order->note,
                    'status' => intval($order->status),
                    'tax' => intval($order->tax),
                    'date' => $order->date,
                    'order_shift_id' => intval($order->order_shift_id),
                    'fromOrderShift' => OrderShift::find($order->order_shift_id)->from,
                    'toOrderShift' => OrderShift::find($order->order_shift_id)->to,
                    'employee_note' => $order->employee_note,
                    'work_duration' => $order->work_duration,
                    'longitude' => $order->longitude,
                    'latitude' => $order->latitude,
                    'location_id' => intval($order->location_id),
                    'locationName' => $order->location_id == null || $order->location_id == 0 ? null : ($request->header('X-localization') == 'en' ? Location::find($order->location_id)->name : Location::find($order->location_id)->name),
                    'locationLat' => $order->location_id == null || $order->location_id == 0 ? null : Location::find($order->location_id)->latitude,
                    'locationLong' => $order->location_id == null || $order->location_id == 0 ? null : Location::find($order->location_id)->longitude,
                    'images' => $img,
                    'vedio' => $order->vedio != null ? asset('uploads/orders/vedio/' . $order->vedio) : null,
                    'anotherServicesWithOrder' => $anotherServicesWithOrder,
                    'notes_on_what_was_done' => $order->notes_on_what_was_done,
                    'complete_in_another_day' => intval($order->complete_in_another_day),
                    'restOfOrder' =>$restOfOrder,

                ]);
                return ApiController::respondWithSuccess($data);

            }

//                }
//            }

        }
        $errors = ['key' => 'errorUser',
            'value' => $request->header('') == 'en' ? 'the order has not been approved,No staff are currently available ' : 'لم يتم اعتماد الطلب ,لا يوجد موظفين متاحيين حاليا '
        ];
        return ApiController::respondWithErrorClient(array($errors));
    }

    public function anotherOrderSendCategories(Request $request)
    {
        $rules = [
            'order_id' => 'required|exists:orders,id',
            'category_id.*' => 'required|exists:categories,id'
        ];


        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));


        $order = Order::find($request->order_id);
        if ($order->status == 1) {
            $arr = [];
            foreach ($request->category_id as $cat) {
                $anotherServicesWithOrder = WithOrderCategory::create([
                    'order_id' => $order->id,
                    'category_id' => $cat,
                ]);
                array_push($arr, [
                    'id' => intval($anotherServicesWithOrder->id),
                    'order_id' => intval($anotherServicesWithOrder->id),
                    'category_id' => intval($anotherServicesWithOrder->category_id),
                ]);
            }


            return ApiController::respondWithSuccess([
                'anotherServicesWithOrder' => $arr,
            ]);

        } else {
            $errors = ['key' => 'errorUser',
                'value' => $request->header('X-localization') == 'en' ? 'You cannot add other services to your order' : 'لا يمكنك اضافه خدمات اخري لطلبك'
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }

    }

    public function allMyOrders(Request $request)
    {
        $orders = auth()->user()->userOrders()->get();

        if ($orders) {
            $data = [];
            foreach ($orders as $order) {


                $img = [];
                foreach ($order->images()->get() as $image) {
                    array_push($img, [
                        'image' => asset('uploads/orders/' . $image->image),
                    ]);
                }
                $anotherServicesWithOrder = [];
                $anotherServices = $order->withOrderCategories()->count();
                if ($anotherServices > 0) {
                    $anotherServices = $order->withOrderCategories()->get();
                    foreach ($anotherServices as $anotherService) {
                        array_push($anotherServicesWithOrder, [
                            'id' => intval($anotherService->id),
                            'order_id' => intval($anotherService->order_id),
                            'category_id' => intval($anotherService->category_id),
                            'nameOfCategory' => $request->header('X-localization') == 'en' ? Category::find($anotherService->category_id)->name : Category::find($anotherService->category_id)->name_ar,

                        ]);
                    }

                }



                $restOfOrder = [];
    
                $timeOrders = $order->timeOrders()->count();
                if($timeOrders > 0){
                    $timeOrderss = $order->timeOrders()->get();
                foreach($timeOrderss as $timeOrder){
                    array_push($restOfOrder , [
                        'id' => intval($timeOrder->id),
                        'order_shift_id' => intval($timeOrder->order_shift_id),
                        'fromOrder' => OrderShift::find($timeOrder->order_shift_id)->from,
                        'toOrder' => OrderShift::find($timeOrder->order_shift_id)->to,
                        'order_id' => intval($timeOrder->order_id),
                        'start' => $timeOrder->start,
                        'end' => $timeOrder->end,
                        'date' =>$timeOrder->date
                
                
                    ]);
                }
                }
                array_push($data, [
                    'id' => intval($order->id),
                    'category_id' => intval($order->category_id),
                    'category' => $request->header('X-localization') == 'en' ? Category::find($order->category_id)->name : Category::find($order->category_id)->name_ar,
                    'categoryImage' => Category::find($order->category_id)->image != null ? asset('/uploads/categories/' . Category::find($order->category_id)->image) : null,
                    'user_id' => intval($order->user_id),
                    'user' => User::find($order->user_id)->name,
                    'userPhone' => User::find($order->user_id)->phone,

                    'price' => number_format((float)$order->price, 2, '.', ''),
                    'from' => $order->from,
                    'to' => $order->to,
                    'employee_id' => intval($order->employee_id),
                    'employee' => User::find($order->employee_id)->name,
                    'note' => $order->note,
                    'status' => intval($order->status),
                    'tax' => intval($order->tax),
                    'date' => $order->date,
                    'order_shift_id' => intval($order->order_shift_id),
                    'fromOrderShift' => OrderShift::find($order->order_shift_id)->from,
                    'toOrderShift' => OrderShift::find($order->order_shift_id)->to,
                    'employee_note' => $order->employee_note,
                    'work_duration' => $order->work_duration,
                    'images' => $img,
                    'longitude' => $order->longitude,
                    'latitude' => $order->latitude,
                    'location_id' => intval($order->location_id),
                    'locationName' => $order->location_id == null || $order->location_id == 0 ? null : ($request->header('X-localization') == 'en' ? Location::find($order->location_id)->name : Location::find($order->location_id)->name),
                    'locationLat' => $order->location_id == null || $order->location_id == 0 ? null : Location::find($order->location_id)->latitude,
                    'locationLong' => $order->location_id == null || $order->location_id == 0 ? null : Location::find($order->location_id)->longitude,
                    'material_cost' => number_format((float)$order->material_cost, 2, '.', ''),
                    'valueOfTax' => number_format((float)Setting::find(1)->tax, 2, '.', ''),
                    'total' => number_format((float)$order->total, 2, '.', ''),
                    'is_paid' => intval($order->is_paid),
                    'vedio' => $order->vedio != null ? asset('uploads/orders/vedio/' . $order->vedio) : null,
                    'anotherServicesWithOrder' => $anotherServicesWithOrder,
                    'notes_on_what_was_done' => $order->notes_on_what_was_done,
                    'complete_in_another_day' => intval($order->complete_in_another_day),
                'restOfOrder' =>$restOfOrder,
                ]);
            }
            return ApiController::respondWithSuccess($data);

        } else {
            $errors = ['key' => 'error',
                'value' => $request->header('') == 'en' ? 'There are no data ' : 'لا يوجد طلبات'
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }
    }


    /*
    *is_paid = 1 by كاش
    *is_paid = 2 by فيزا
    *is_paid = 0 لم يتم الدفع  
    */
    public function isPaid(Request $request)
    {
        $rules = [
            'order_id' => 'required|exists:orders,id',
            'is_paid' => 'required|in:1,0,2',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));


        $order = Order::find($request->order_id);
        $order->is_paid = $request->is_paid;
        $order->status = 3 ;
        $order->save();
        $img = [];
        foreach ($order->images()->get() as $image) {
            array_push($img, [
                'image' => asset('uploads/orders/' . $image->image),
            ]);
        }


        $restOfOrder = [];
    
        $timeOrders = $order->timeOrders()->count();
        if($timeOrders > 0){
            $timeOrderss = $order->timeOrders()->get();
        foreach($timeOrderss as $timeOrder){
            array_push($restOfOrder , [
                'id' => intval($timeOrder->id),
                'order_shift_id' => intval($timeOrder->order_shift_id),
                'fromOrder' => OrderShift::find($timeOrder->order_shift_id)->from,
                'toOrder' => OrderShift::find($timeOrder->order_shift_id)->to,
                'order_id' => intval($timeOrder->order_id),
                'start' => $timeOrder->start,
                'end' => $timeOrder->end,
                'date' =>$timeOrder->date
        
        
            ]);
        }
        }
        $data = [];
        array_push($data, [
            'id' => intval($order->id),
            'category_id' => intval($order->category_id),
            'category' => $request->header('X-localization') == 'en' ? Category::find($order->category_id)->name : Category::find($order->category_id)->name_ar,
            'user_id' => intval($order->user_id),
            'user' => User::find($order->user_id)->name,
            'userPhone' => User::find($order->user_id)->phone,
            'price' => number_format((float)$order->price, 2, '.', ''),
            'material_cost' => number_format((float)$order->material_cost, 2, '.', ''),
            'valueOfTax' => number_format((float)Setting::find(1)->tax, 2, '.', ''),
            'from' => $order->from,
            'to' => $order->to,
            'employee_id' => intval($order->employee_id),
            'employee' => User::find($order->employee_id)->name,
            'note' => $order->note,
            'status' => intval($order->status),
            'tax' => intval($order->tax),
            'date' => $order->date,
            'order_shift_id' => intval($order->order_shift_id),
            'fromOrderShift' => OrderShift::find($order->order_shift_id)->from,
            'toOrderShift' => OrderShift::find($order->order_shift_id)->to,
            'employee_note' => $order->employee_note,
            'work_duration' => $order->work_duration,
            'longitude' => $order->longitude,
            'latitude' => $order->latitude,
            'location_id' => intval($order->location_id),
            'locationName' => $order->location_id == null || $order->location_id == 0 ? null : ($request->header('X-localization') == 'en' ? Location::find($order->location_id)->name : Location::find($order->location_id)->name),
            'locationLat' => $order->location_id == null || $order->location_id == 0 ? null : Location::find($order->location_id)->latitude,
            'locationLong' => $order->location_id == null || $order->location_id == 0 ? null : Location::find($order->location_id)->longitude,
            'images' => $img,
            'total' => number_format((float)$order->total, 2, '.', ''),
            'is_paid' => intval($order->is_paid),
            'vedio' => $order->vedio != null ? asset('uploads/orders/vedio/' . $order->vedio) : null,
            'notes_on_what_was_done' => $order->notes_on_what_was_done,
            'complete_in_another_day' => intval($order->complete_in_another_day),
            'restOfOrder' =>$restOfOrder,
        ]);
        return ApiController::respondWithSuccess($data);
    }

    public function orderEvaluation(Request $request)
    {
        $rules = [
            'rate' => 'required|numeric|in:1,2,3',
            'sentence' => 'required',
            'comment' => 'nullable|string|max:255',
            'sentence.*' => 'exists:sentences,id',
            'order_id' => 'exists:orders,id',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $order = Order::find($request->order_id);
        if ($order->rate()->first()) {
            $errors = ['key' => 'error',
                'value' => $request->header('X-localization') == 'en' ? 'You can not add more than one evaluation ' : 'لا يمكنك اضافه اكثر من تقييم لنفس الطلب '
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }
        if (($order->status == 3 && $order->is_paid == 1) || ($order->status == 4 && $order->is_paid == 1) || ($order->status == 3 && $order->is_paid == 2 ) || ($order->status == 4 && $order->is_paid == 2)) {


            $rate = $order->rate()->create([
                'rate' => $request->rate,
                'user_id' => auth()->user()->id,
'comment' =>$request->comment,
            ]);

            $rate->sentences()->sync($request->sentence);

            $rate = Rate::find($rate->id);
//            dd($allSentences);
            $sentences = [];
            foreach ($rate->sentences as $sentence) {
                array_push($sentences, [
                    'rate_id' => intval($sentence->pivot->rate_id),
                    'sentence_id' => intval($sentence->pivot->sentence_id),
                    'sentence' => $request->header('X-localization') == 'en' ? $sentence->sentence : $sentence->sentence_ar,
                ]);
            }

            $img = [];
            foreach ($order->images()->get() as $image) {
                array_push($img, [
                    'image' => asset('uploads/orders/' . $image->image),
                ]);
            }


            $restOfOrder = [];
    
            $timeOrders = $order->timeOrders()->count();
            if($timeOrders > 0){
                $timeOrderss = $order->timeOrders()->get();
            foreach($timeOrderss as $timeOrder){
                array_push($restOfOrder , [
                    'id' => intval($timeOrder->id),
                    'order_shift_id' => intval($timeOrder->order_shift_id),
                    'fromOrder' => OrderShift::find($timeOrder->order_shift_id)->from,
                    'toOrder' => OrderShift::find($timeOrder->order_shift_id)->to,
                    'order_id' => intval($timeOrder->order_id),
                    'start' => $timeOrder->start,
                    'end' => $timeOrder->end,
                    'date' =>$timeOrder->date
            
            
                ]);
            }
            }
            $data = [];

            array_push($data, [
                'id' => intval($order->id),
                'category_id' => intval($order->category_id),
                'category' => $request->header('X-localization') == 'en' ? Category::find($order->category_id)->name : Category::find($order->category_id)->name_ar,
                'user_id' => intval($order->user_id),
                'user' => User::find($order->user_id)->name,
                'userPhone' => User::find($order->user_id)->phone,
                'price' => number_format((float)$order->price, 2, '.', ''),
                'material_cost' => number_format((float)$order->material_cost, 2, '.', ''),
                'valueOfTax' => number_format((float)Setting::find(1)->tax, 2, '.', ''),
                'from' => $order->from,
                'to' => $order->to,
                'employee_id' => intval($order->employee_id),
                'employee' => User::find($order->employee_id)->name,
                'note' => $order->note,
                'status' => intval($order->status),
                'tax' => intval($order->tax),
                'date' => $order->date,
                'order_shift_id' => intval($order->order_shift_id),
                'fromOrderShift' => OrderShift::find($order->order_shift_id)->from,
                'toOrderShift' => OrderShift::find($order->order_shift_id)->to,
                'employee_note' => $order->employee_note,
                'work_duration' => $order->work_duration,
                'longitude' => $order->longitude,
                'latitude' => $order->latitude,
                'location_id' => intval($order->location_id),
                'locationName' => $order->location_id == null || $order->location_id == 0 ? null : ($request->header('X-localization') == 'en' ? Location::find($order->location_id)->name : Location::find($order->location_id)->name),
                'locationLat' => $order->location_id == null || $order->location_id == 0 ? null : Location::find($order->location_id)->latitude,
                'locationLong' => $order->location_id == null || $order->location_id == 0 ? null : Location::find($order->location_id)->longitude,
                'is_paid' => intval($order->is_paid),
                'images' => $img,
                'total' => number_format((float)$order->total, 2, '.', ''),
                'rate' => $order->rate()->first()->rate,
                'comment' => $order->rate()->first()->comment,
                'sentence' => $sentences,
                'vedio' => $order->vedio != null ? asset('uploads/orders/vedio/' . $order->vedio) : null,
                'notes_on_what_was_done' => $order->notes_on_what_was_done,
                'complete_in_another_day' => intval($order->complete_in_another_day),
                'restOfOrder' =>$restOfOrder,
            ]);
            return ApiController::respondWithSuccess($data);

        } else {
            $errors = ['key' => 'error',
                'value' => $request->header('X-localization') == 'en' ? 'You must pay for the service, prior to evaluation  ,, Check the status of your order' : 'يجب دفع تمن الخدمة قبل التقييم، تاكد من حاله طلبك '
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }


    }


    public function activeOrdercomplaint(Request $request)
    {
        $rules = [
            'title' => 'required|max:255',
            'description' => 'required|string',
            'order_id' => 'exists:orders,id',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));


        $order = Order::find($request->order_id);
        $user = $request->user();
        if ($order->status == 2) {
            $complaint = Complaint::create([
                'title' => $request->title,
                'description' => $request->description,
                'order_id' => $request->order_id,
                'user_id' => $user->id,
            ]);
            $data = [];
            array_push($data, [
                'id' => intval($complaint->id),
                'user_id' => intval($complaint->user_id),
                'user' => User::find($complaint->user_id)->name,
                'order_id' => intval($complaint->order_id),
                'title' => $complaint->title,
                'description' => $complaint->description,
            ]);

            return ApiController::respondWithSuccess($data);

        } else {

            $errors = ['key' => 'error',
                'value' => $request->header('X-localization') == 'en' ? 'The Order must be active in order to send a complaint' : 'يجب ان يكون الطلب نشط لكي ترسل شكوي '
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }

    }
}
