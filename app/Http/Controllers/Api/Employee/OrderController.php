<?php

namespace App\Http\Controllers\Api\Employee;

use App\Http\Controllers\Api\ApiController;
use App\Models\Category;
use App\Models\Location;
use App\Models\Order;
use App\Models\Setting;
use App\Models\OrderShift;
use App\Models\Rate;
use App\User;
use App\Models\RejectedUser;
use App\Models\Device;
use App\Models\RejectedDate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Validator;

class OrderController extends Controller
{
    //

    public function filterCategory(Request $request)
    {
        $rules = [
            'status' => 'required|in:1,2,3,4,5',
            'category_id' => 'required|exists:categories,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));
        $user = $request->user();

        $orders = $user->employeeOrders()->where([
            ['status', $request->status],
            ['category_id', $request->category_id]
        ])->orderBy('id', 'desc')->get();
        if ($orders->count() > 0) {
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
                    'real_num'=>intval($order->real_num),
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
                    'date' => $order->date,
                    'order_shift_id' => intval($order->order_shift_id),
                    'fromOrderShift' => OrderShift::find($order->order_shift_id)->from,
                    'toOrderShift' => OrderShift::find($order->order_shift_id)->to,
                    'tax' => intval($order->tax),

                    'employee_note' => $order->employee_note,
                    'work_duration' => $order->work_duration,

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
                    'images' => $img,
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
                'value' => $request->header('X-localization') == 'en' ? ' no data' : 'لا يوجد طلبات'
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }
    }

    public function filterDate(Request $request)
    {
        $rules = [
            'status' => 'required|in:1,2,3,4',
            'date' => 'required|date_format:Y-m-d',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));
        $user = $request->user();

        $orders = $user->employeeOrders()->where([
            ['status', $request->status],
            ['date', $request->date]
        ])->orderBy('id', 'desc')->get();
        if ($orders->count() > 0) {
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
                    'real_num'=>intval($order->real_num),
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
                    'date' => $order->date,
                    'order_shift_id' => intval($order->order_shift_id),
                    'fromOrderShift' => OrderShift::find($order->order_shift_id)->from,
                    'toOrderShift' => OrderShift::find($order->order_shift_id)->to,
                    'tax' => intval($order->tax),

                    'employee_note' => $order->employee_note,
                    'work_duration' => $order->work_duration,

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
                    'images' => $img,
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
                'value' => $request->header('X-localization') == 'en' ? ' no data' : 'لا يوجد طلبات'
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }
    }



    public function waitedOrders(Request $request)
    {
        $user = $request->user();
        if ($user->type == 2) {
            $orders = $user->employeeOrders()->where('status', 5)->orderBy('id', 'desc')->get();
//            dd($orders->count() );
            if ($orders->count() > 0) {
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
                        'real_num'=>intval($order->real_num),
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
                        'date' => $order->date,
                        'order_shift_id' => intval($order->order_shift_id),
                        'fromOrderShift' => OrderShift::find($order->order_shift_id)->from,
                        'toOrderShift' => OrderShift::find($order->order_shift_id)->to,
                        'tax' => intval($order->tax),

                        'employee_note' => $order->employee_note,
                        'work_duration' => $order->work_duration,

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
                        'images' => $img,
                        'vedio' => $order->vedio != null ? asset('uploads/orders/vedio/' . $order->vedio) : null,

                        'anotherServicesWithOrder' => $anotherServicesWithOrder,
                        'notes_on_what_was_done' => $order->notes_on_what_was_done,
                        'complete_in_another_day' => intval($order->complete_in_another_day),
                        'restOfOrder' => $restOfOrder,
                    ]);
                }


                return ApiController::respondWithSuccess($data);

            } else {
                $errors = ['key' => 'error',
                    'value' => $request->header('X-localization') == 'en' ? ' no data' : 'لا يوجد طلبات'
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



    public function activeOrder(Request $request)
    {
        $user = $request->user();
        if ($user->type == 2) {
            $orders = $user->employeeOrders()->where('status', 2)->orderBy('id', 'desc')->get();
//            dd($orders->count() );
            if ($orders->count() > 0) {
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
                        'real_num'=>intval($order->real_num),
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
                        'date' => $order->date,
                        'order_shift_id' => intval($order->order_shift_id),
                        'fromOrderShift' => OrderShift::find($order->order_shift_id)->from,
                        'toOrderShift' => OrderShift::find($order->order_shift_id)->to,
                        'tax' => intval($order->tax),

                        'employee_note' => $order->employee_note,
                        'work_duration' => $order->work_duration,

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
                        'images' => $img,
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
                    'value' => $request->header('X-localization') == 'en' ? ' no data' : 'لا يوجد طلبات'
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

    public function completedOrder(Request $request)
    {
        $user = $request->user();
        if ($user->type == 2) {
            $orders = $user->employeeOrders()->where('status', 3)->orderBy('id', 'desc')->get();
//            dd($orders->count() );
            if ($orders->count() > 0) {
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
                        'real_num'=>intval($order->real_num),
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
                        'date' => $order->date,
                        'order_shift_id' => intval($order->order_shift_id),
                        'fromOrderShift' => OrderShift::find($order->order_shift_id)->from,
                        'toOrderShift' => OrderShift::find($order->order_shift_id)->to,
                        'tax' => intval($order->tax),

                        'employee_note' => $order->employee_note,
                        'work_duration' => $order->work_duration,

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
                        'images' => $img,
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
                    'value' => $request->header('X-localization') == 'en' ? ' no data' : 'لا يوجد طلبات'
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

    public function canceledOrder(Request $request)
    {
        $user = $request->user();
        if ($user->type == 2) {
            $orders = $user->employeeOrders()->where('status', 4)->orWhere('status', 6)->orderBy('id', 'desc')->get();
//            dd($orders->count() );
            if ($orders->count() > 0) {
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
                        'real_num'=>intval($order->real_num),
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
                        'date' => $order->date,
                        'order_shift_id' => intval($order->order_shift_id),
                        'fromOrderShift' => OrderShift::find($order->order_shift_id)->from,
                        'toOrderShift' => OrderShift::find($order->order_shift_id)->to,
                        'tax' => intval($order->tax),

                        'employee_note' => $order->employee_note,
                        'work_duration' => $order->work_duration,

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
                        'images' => $img,
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
                    'value' => $request->header('X-localization') == 'en' ? ' no data' : 'لا يوجد طلبات'
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
    * cancel order before his date is coming depend on period specified by administrator 
    * cancel_order column in settings table
    * status  6 canceled order by employee
    */

    public function cancelOrder(Request $request){
        $rules = [
            'status' => 'required|in:6',
            'order_id' => 'required|exists:orders,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));


        $order = Order::find($request->order_id);
//        dd($order->date);
        $now = Carbon::today()->toDateString();
        if ($now != $order->date) {
            $errors = ['key' => 'error',
                'value' => $request->header('') == 'en' ? 'Check the date of the order ' : 'تاكد من تاريخ الطلب  '
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }
        $order->status = $request->status;
        $order->save();



//                         send notification to drivers
$devicesTokens = Device::where('user_id', $order->user_id)
->get()
->pluck('device_token')
->toArray();

if ($devicesTokens) {
//                         $order->employee_id
$request->header('') == 'en' ?
sendMultiNotification( "  Your order has been canceled by the employee", " Contact the administration to find out the reason  ", $devicesTokens)
:
sendMultiNotification( "  تواصل مع الادارة لمعرفه السبب ", " تم الغاء طلبك من قبل الموظف  ", $devicesTokens);
}
saveNotification($order->user_id,' your order is canceled',' تم الغاء طلبك ','Your order has been canceled ','  تم الغاء طلبك ',$order->id);



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
    
        $data = [];
        array_push($data, [
            'id' => intval($order->id),
            'real_num'=>intval($order->real_num),
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
            'images' => $img,
            'vedio' => $order->vedio != null ? asset('uploads/orders/vedio/' . $order->vedio) : null,
            'anotherServicesWithOrder' => $anotherServicesWithOrder,
            'notes_on_what_was_done' => $order->notes_on_what_was_done,
            'complete_in_another_day' => intval($order->complete_in_another_day),
            'restOfOrder' =>$restOfOrder,

        ]);
        return ApiController::respondWithSuccess($data);
    }




    public function showMyOrder(Request $request)
    {
        $user = $request->user();
        if ($user->type == 2) {
            $orders = $user->employeeOrders()->whereIn('status', [3, 4, 2])->orderBy('id', 'desc')->get();
//            dd($orders->count() );
            if ($orders->count() > 0) {
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
                        'real_num'=>intval($order->real_num),
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
                        'date' => $order->date,
                        'order_shift_id' => intval($order->order_shift_id),
                        'fromOrderShift' => OrderShift::find($order->order_shift_id)->from,
                        'toOrderShift' => OrderShift::find($order->order_shift_id)->to,
                        'tax' => intval($order->tax),

                        'employee_note' => $order->employee_note,
                        'work_duration' => $order->work_duration,

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
                        'images' => $img,
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
                    'value' => $request->header('X-localization') == 'en' ? ' no data' : 'لا يوجد طلبات'
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

    public function myAppointments(Request $request)
    {
        $user = $request->user();
        if ($user->type == 2) {

            // $orders = $user->employeeOrders()->where('status', 1)->orderBy('id', 'desc')->get();
            $orders = $user->employeeOrders()
            ->where('employee_id', $user->id)
            ->where('status' , 1)
            ->orWhere('employee_id', $user->id)
            ->where('status' , 5)
            ->orderBy('id','desc')
            ->get();
            if ($orders->count() > 0) {
                $data = [];
                foreach ($orders as $order) {

//                    $ordersRelated = $order->withOrders()->get();
//                    $orderRelatedCount = $order->withOrders()->count();
//                    $orderRelatedArray = [];
//                    if($orderRelatedCount > 0){
//foreach ($ordersRelated as $orderRelated){
//
//    $employee_id = Order::find($orderRelated->order_id)->employee_id;
//    array_push($orderRelatedArray ,[
//        'id' => $orderRelated->order_id,
//    'employee_id' => $employee_id,
//        'employee' => User::find($employee_id)->name,
//        'phone' => User::find($employee_id)->phone,
//
//    ]);
//}
//                    }


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
                        'real_num'=>intval($order->real_num),
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
                        'date' => $order->date,
                        'order_shift_id' => intval($order->order_shift_id),
                        'fromOrderShift' => OrderShift::find($order->order_shift_id)->from,
                        'toOrderShift' => OrderShift::find($order->order_shift_id)->to,
                        'employee_note' => $order->employee_note,
                        'work_duration' => $order->work_duration,
//                        'relatedOrderCount' => $orderRelatedCount,
//                        'relatedOrder' =>$orderRelatedArray,
                        'images' => $img,
                        'tax' => intval($order->tax),

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
                    'value' => $request->header('X-localization') == 'en' ? 'There are no appointments' : 'لا يوجد مواعيد'
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
    *is_paid = 1 by كاش
    *is_paid = 2 by فيزا
    *is_paid = 0 لم يتم الدفع  
    */
    public function rejectOrder(Request $request)
    {
        $rules = [
            'status' => 'required|in:4',
            'employee_note' => 'required|string',
            'order_id' => 'required|exists:orders,id',
            'is_paid' => 'required|in:1,0,2',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));


        $order = Order::find($request->order_id);
        $order->status = $request->status;
        $order->employee_note = $request->employee_note;
        $order->is_paid = $request->is_paid;
        $order->save();


//                         send notification to drivers
           $devicesTokens = Device::where('user_id', $order->user_id)
            ->get()
            ->pluck('device_token')
            ->toArray();

if ($devicesTokens) {
//                         $order->employee_id
$request->header('X-localization') == 'en' ?
sendMultiNotification( " Your order has been canceled ", " Contact the administration to find out the reason for canceling your order ", $devicesTokens)
:
sendMultiNotification( " تواصل مع الادارة لمعرفه سبب الغاء طلبك ", " تم الغاء طلبك ", $devicesTokens);
}
saveNotification($order->user_id,' your order is reject','تم الغاء  طلبك','Contact the administration to find out the reason for canceling your order ','  تواصل مع الادارة لمعرفه سبب الغاء طلبك  ',$order->id);

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
            'real_num'=>intval($order->real_num),
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
            'date' => $order->date,
            'order_shift_id' => intval($order->order_shift_id),
            'fromOrderShift' => OrderShift::find($order->order_shift_id)->from,
            'toOrderShift' => OrderShift::find($order->order_shift_id)->to,
            'tax' => intval($order->tax),

            'employee_note' => $order->employee_note,
            'work_duration' => $order->work_duration,

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
            'images' => $img,
            'vedio' => $order->vedio != null ? asset('uploads/orders/vedio/' . $order->vedio) : null,
            'anotherServicesWithOrder' => $anotherServicesWithOrder,
            'notes_on_what_was_done' => $order->notes_on_what_was_done,
            'complete_in_another_day' => intval($order->complete_in_another_day),
            'restOfOrder' =>$restOfOrder,
        ]);
        return ApiController::respondWithSuccess($data);

    }



    public function CompleteStartOrder(Request $request)
    {
        $rules = [
            'start' => 'required|date_format:H:i',
            'order_id' => 'required|exists:orders,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));


        $order = Order::find($request->order_id);
//        dd($order->date);
        $now = Carbon::today()->toDateString();
        $timeOrder = $order->timeOrders()->latest()->first();
        if ($now !=  $timeOrder->date) {
            $errors = ['key' => 'error',
                'value' => $request->header('') == 'en' ? 'Check the date of the order ' : 'تاكد من تاريخ الطلب  '
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }

        $timeOrder->start = $request->start;
        $timeOrder->save();


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

        $data = [];
        array_push($data, [
            'id' => intval($order->id),
            'real_num'=>intval($order->real_num),
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
            'images' => $img,
            'vedio' => $order->vedio != null ? asset('uploads/orders/vedio/' . $order->vedio) : null,
            'anotherServicesWithOrder' => $anotherServicesWithOrder,
            'notes_on_what_was_done' => $order->notes_on_what_was_done,
            'complete_in_another_day' => intval($order->complete_in_another_day),
            'restOfOrder' => $restOfOrder,

        ]);
        return ApiController::respondWithSuccess($data);

    }

    public function startOrder(Request $request)
    {
        $rules = [
            'status' => 'required|in:2',
            'from' => 'required|date_format:H:i',
            'order_id' => 'required|exists:orders,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));


        $order = Order::find($request->order_id);
//        dd($order->date);
        $now = Carbon::today()->toDateString();
        if ($now != $order->date) {
            $errors = ['key' => 'error',
                'value' => $request->header('') == 'en' ? 'Check the date of the order ' : 'تاكد من تاريخ الطلب  '
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }
        $order->status = $request->status;
        $order->from = $request->from;
        $order->save();



//                         send notification to drivers
$devicesTokens = Device::where('user_id', $order->user_id)
->get()
->pluck('device_token')
->toArray();

if ($devicesTokens) {
//                         $order->employee_id
$request->header('X-localization') == 'en' ?
sendMultiNotification( " Your order has been initiated ", "  Your order is active", $devicesTokens)
:
sendMultiNotification( " طلبك نشط", "  تم البدء في طلبك ", $devicesTokens);
}
saveNotification($order->user_id,' your order is active','تم البدء في طلب','Your order has been initiated ','  طلبك نشط الان ',$order->id);



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
    
        $data = [];
        array_push($data, [
            'id' => intval($order->id),
            'real_num'=>intval($order->real_num),
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
            'images' => $img,
            'vedio' => $order->vedio != null ? asset('uploads/orders/vedio/' . $order->vedio) : null,
            'anotherServicesWithOrder' => $anotherServicesWithOrder,
            'notes_on_what_was_done' => $order->notes_on_what_was_done,
            'complete_in_another_day' => intval($order->complete_in_another_day),
            'restOfOrder' =>$restOfOrder,

        ]);
        return ApiController::respondWithSuccess($data);
    }




    public function rescheduleAgain(Request $request){
        $rules = [
            'order_id' => 'required|exists:orders,id',
            'date' => 'required|date_format:Y-m-d',
            'end' => 'required|date_format:H:i',
            'order_shift_id' => 'required|exists:order_shifts,id',
        ];
    
    
    
        $today = Carbon::today();
    //        $today = Carbon::today()->addDay(1);
    
        // check send order in expired date or note
        if (($request->date >= $today->format('Y-m-d')) == false) {
            $errors = ['key' => 'message',
                'value' => $request->header('X-localization') == 'en' ? 'You cannot add a previous date You cannot submit a request on an earlier date' : 'لا يمكنك ارسال طلب في تاريخ سابق'
            ];
            return ApiController::respondWithErrorArray(array($errors));
        }
        // check send order in rejected date or note
        // $rejectedDate = RejectedDate::where('reject_date', $request->date)->first();
        // if ($rejectedDate) {
        //     $errors = ['key' => 'message',
        //         'value' => $request->header('X-localization') == 'en' ?'This date cannot be booked because:'. $rejectedDate->reason_en :'لا يمكن حجز هذا التاريخ بسبب : ' . $rejectedDate->reason
        //     ];
        //     return ApiController::respondWithErrorArray(array($errors));
        // }


        $user = $request->user();
        $reject = RejectedDate::where('reject_date', $request->date)->first();
        $rejectDateTime =  $reject != null ?RejectedUser::where('order_shift_id', $request->order_shift_id)->where('rejected_date_id',$reject->id)->first() : null;
    
    if($rejectDateTime != null){
     $rejectUser =  $rejectDateTime->rejecteds()->where('user_id',$user->id)->first();
    }
    
    
    
    
        // check send order in rejected date or note
    
        if ($rejectUser != null) { 
            $errors = ['key' => 'message',
                'value' => $request->header('X-localization') == 'en' ?'This date cannot be booked because:'. $reject->reason_en :'لا يمكن حجز هذا التاريخ بسبب : ' . $reject->reason
            ];
            return ApiController::respondWithErrorArray(array($errors));
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));
    
    
            $order = Order::find($request->order_id);
            if($order->status != 5){
                $errors = ['key' => 'message',
                'value' => $request->header('X-localization') == 'en' ? 'make sure your order status' : 'تأكد من حالة طلبك'
            ];
            return ApiController::respondWithErrorArray(array($errors));
            }
    
            $orderswherestatus1 = $user->employeeOrders()->where([
                ['employee_id', $user->id],
                ['order_shift_id', $request->order_shift_id],
                ['status', 1],
        
            ])
                ->count();
        
                $orderswherestatus5 = $user->employeeOrders()->where([
                    ['employee_id', $user->id],
                    ['order_shift_id', $request->order_shift_id],
                    ['status', 5],
            
                ])
                    ->count();
    
            $count_of_order_in_period = Setting::find(1)->count_of_order_in_period;
    
    if(($orderswherestatus1 + $orderswherestatus5) < $count_of_order_in_period){

        $timeOrder = $order->timeOrders()->latest()->first();
        $timeOrder->end = $request->end;
$timeOrder->save();
    $order->timeOrders()->create([
        'date' =>$request->date,
        'order_shift_id' =>$request->order_shift_id,
    ]);
    
    
    
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
    $data = [];
    array_push($data, [
        'id' => intval($order->id),
        'real_num'=>intval($order->real_num),
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
        'images' => $img,
        'vedio' => $order->vedio != null ? asset('uploads/orders/vedio/' . $order->vedio) : null,
        'anotherServicesWithOrder' => $anotherServicesWithOrder,
        'notes_on_what_was_done' => $order->notes_on_what_was_done,
        'complete_in_another_day' => intval($order->complete_in_another_day),
    'restOfOrder' =>$restOfOrder,
    
    ]);
    return ApiController::respondWithSuccess($data);
    
    
    
    
    }else{
        $errors = ['key' => 'errorUser',
        'value' => $request->header('') == 'en' ? 'You cannot place orders in a period more than the limit allowed by the administration ' : ' لا يمكنك وضع طلبات في الفترة  اكتر من الحد المسموح به من قبل الادارة'
    ];
    return ApiController::respondWithErrorClient(array($errors));
    }
    
    
     }


 public function reschedule(Request $request){
    $rules = [
        'order_id' => 'required|exists:orders,id',
        'date' => 'required|date_format:Y-m-d',
        'order_shift_id' => 'required|exists:order_shifts,id',
    ];



    $today = Carbon::today();
//        $today = Carbon::today()->addDay(1);

    // check send order in expired date or note
    if (($request->date >= $today->format('Y-m-d')) == false) {
        $errors = ['key' => 'message',
            'value' => $request->header('X-localization') == 'en' ? 'You cannot add a previous date You cannot submit a request on an earlier date' : 'لا يمكنك ارسال طلب في تاريخ سابق'
        ];
        return ApiController::respondWithErrorArray(array($errors));
    }

    $user = $request->user();
    $reject = RejectedDate::where('reject_date', $request->date)->first();
    $rejectDateTime =  $reject != null ?RejectedUser::where('order_shift_id', $request->order_shift_id)->where('rejected_date_id',$reject->id)->first() : null;

if($rejectDateTime != null){
 $rejectUser =  $rejectDateTime->rejecteds()->where('user_id',$user->id)->first();
}




    // check send order in rejected date or note

    if ($rejectUser != null) {
        $errors = ['key' => 'message',
            'value' => $request->header('X-localization') == 'en' ?'This date cannot be booked because:'. $reject->reason_en :'لا يمكن حجز هذا التاريخ بسبب : ' . $reject->reason
        ];
        return ApiController::respondWithErrorArray(array($errors));
    }

    // // check send order in rejected date or note
    // $rejectedDate = RejectedDate::where('reject_date', $request->date)->first();
    // if ($rejectedDate) {
    //     $errors = ['key' => 'message',
    //         'value' => $request->header('X-localization') == 'en' ?'This date cannot be booked because:'. $rejectedDate->reason_en :'لا يمكن حجز هذا التاريخ بسبب : ' . $rejectedDate->reason
    //     ];
    //     return ApiController::respondWithErrorArray(array($errors));
    // }
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails())
        return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));


        $order = Order::find($request->order_id);
        if($order->status != 5){
            $errors = ['key' => 'message',
            'value' => $request->header('X-localization') == 'en' ? 'make sure your order status' : 'تأكد من حالة طلبك'
        ];
        return ApiController::respondWithErrorArray(array($errors));
        }

        $orderswherestatus1 = $user->employeeOrders()->where([
            ['employee_id', $user->id],
            ['order_shift_id', $request->order_shift_id],
            ['status', 1],
    
        ])
            ->count();
    
            $orderswherestatus5 = $user->employeeOrders()->where([
                ['employee_id', $user->id],
                ['order_shift_id', $request->order_shift_id],
                ['status', 5],
        
            ])
                ->count();

        $count_of_order_in_period = Setting::find(1)->count_of_order_in_period;

if(($orderswherestatus1 + $orderswherestatus5) < $count_of_order_in_period){
$order->timeOrders()->create([
    'date' =>$request->date,
    'order_shift_id' =>$request->order_shift_id,
]);



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
$data = [];
array_push($data, [
    'id' => intval($order->id),
    'real_num'=>intval($order->real_num),
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
    'images' => $img,
    'vedio' => $order->vedio != null ? asset('uploads/orders/vedio/' . $order->vedio) : null,
    'anotherServicesWithOrder' => $anotherServicesWithOrder,
    'notes_on_what_was_done' => $order->notes_on_what_was_done,
    'complete_in_another_day' => intval($order->complete_in_another_day),
'restOfOrder' =>$restOfOrder,

]);
return ApiController::respondWithSuccess($data);




}else{
    $errors = ['key' => 'errorUser',
    'value' => $request->header('') == 'en' ? 'You cannot place orders in a period more than the limit allowed by the administration ' : ' لا يمكنك وضع طلبات في الفترة  اكتر من الحد المسموح به من قبل الادارة'
];
return ApiController::respondWithErrorClient(array($errors));
}


 }

   public function rescheduleTheOrder(Request $request){
    $rules = [
        'status' => 'required|in:5',
        'to' => 'required|date_format:H:i',
        'order_id' => 'required|exists:orders,id',
        'complete_in_another_day' => 'required|in:1',
    ];

    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails())
        return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));


        $order = Order::find($request->order_id);
        $order->complete_in_another_day = $request->complete_in_another_day;
        $order->status = $request->status;
        $order->to = $request->to;
        $t1 = Carbon::parse($request->to);
        $t2 = Carbon::parse($order->from);
        $diff = date_diff($t1, $t2);

        $order->work_duration = $diff->format("%H:%I:%S");

        $order->save();

//                         send notification to drivers
$devicesTokens = Device::where('user_id', $order->user_id)
->get()
->pluck('device_token')
->toArray();

if ($devicesTokens) {
//                         $order->employee_id
$request->header('X-localization') == 'en' ? 
sendMultiNotification( "  The order will be completed on another day ", " The order will be completed on another day ", $devicesTokens)
:
sendMultiNotification( "  سيتم تكلمه الطلب في يوم اخر  ", " سيتم تكمله طلبك في يوم اخر ", $devicesTokens);
}
saveNotification($order->user_id,' your order witll complete in another day',' سيتم تكمله الطلب في يوم اخر','Your order has been uncompleted yet ','  طلبك لم يكتمل بعد  ',$order->id);


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

$data = [];
array_push($data, [
    'id' => intval($order->id),
    'real_num'=>intval($order->real_num),
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
    'images' => $img,
    'vedio' => $order->vedio != null ? asset('uploads/orders/vedio/' . $order->vedio) : null,
    'anotherServicesWithOrder' => $anotherServicesWithOrder,
    'notes_on_what_was_done' => $order->notes_on_what_was_done,
    'complete_in_another_day' => intval($order->complete_in_another_day),
    'restOfOrder' =>$restOfOrder,
]);
return ApiController::respondWithSuccess($data);

   }



   public function busyPeriodForEmployee(Request $request){
$user  = $request->user();
    $orderswherestatus1 = $user->employeeOrders()
        ->where('employee_id', $user->id)
        ->where('status' , 1)
        ->orWhere('employee_id', $user->id)
        ->where('status' , 5)
        ->get();
        
$data = [];
foreach($orderswherestatus1 as $order){

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
        'real_num'=>intval($order->real_num),
        'category_id' => intval($order->category_id),
        'category' => $request->header('X-localization') == 'en' ? Category::find($order->category_id)->name : Category::find($order->category_id)->name_ar,
        'categoryImage' => Category::find($order->category_id)->image != null ? asset('/uploads/categories/' . Category::find($order->category_id)->image) : null,
        'user_id' => intval($order->user_id),
        'user' => User::find($order->user_id)->name,
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
        'complete_in_another_day' =>$order->complete_in_another_day,
        'restOfOrder' =>$restOfOrder,
    ]);
}



//                    dd($orderCountwherestatus1 + $orderCountwherestatus2);
    $count_of_order_in_period = Setting::find(1)->count_of_order_in_period;
    return ApiController::respondWithSuccess([
        'data' =>$data,
        'count_of_order_in_period' =>  $count_of_order_in_period ,
        ]);


   }



   public function CompleteEndOrder(Request $request)
    {
        $rules = [
            'status' => 'required|in:3',
            'end' => 'required|date_format:H:i',
            'order_id' => 'required|exists:orders,id',
            // 'completed_order_accepte_tax' => 'required|in:0,1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));


        $order = Order::find($request->order_id);
        // $order->completed_order_accepte_tax = $request->completed_order_accepte_tax;
        $order->status = $request->status;
        $order->complete_in_another_day = 2;
        $order->save();
        $timeOrder = $order->timeOrders()->latest()->first();
        $timeOrder->end = $request->end;
        $timeOrder->save();

//                         send notification to drivers
$devicesTokens = Device::where('user_id', $order->user_id)
->get()
->pluck('device_token')
->toArray();

if ($devicesTokens) {
//                         $order->employee_id
$request->header('X-localization') == 'en' ?
sendMultiNotification( "Your order has been finalized ", "  Your order is now complete ", $devicesTokens)
:
sendMultiNotification( " طلبك مكتمل الان ", "  تم انهاء طلبك ", $devicesTokens);
}
saveNotification($order->user_id,' your order is complete','تم انهاء الطلب','Your order has been endes ','  طلبك مكتمل الان ',$order->id);
        
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

        $data = [];
        array_push($data, [
            'id' => intval($order->id),
            'real_num'=>intval($order->real_num),
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
            'images' => $img,
            'vedio' => $order->vedio != null ? asset('uploads/orders/vedio/' . $order->vedio) : null,
            'anotherServicesWithOrder' => $anotherServicesWithOrder,
            'notes_on_what_was_done' => $order->notes_on_what_was_done,
            'complete_in_another_day' => intval($order->complete_in_another_day),
            'restOfOrder' => $restOfOrder,

        ]);
        return ApiController::respondWithSuccess($data);

    }


    public function finishOrder(Request $request)
    {
        $rules = [
            'status' => 'required|in:3',
            'to' => 'required|date_format:H:i',
            'order_id' => 'required|exists:orders,id',
            // 'completed_order_accepte_tax' => 'required|in:0,1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));


        $order = Order::find($request->order_id);
        // $order->completed_order_accepte_tax = $request->completed_order_accepte_tax;
        $order->status = $request->status;
        $order->to = $request->to;
        $t1 = Carbon::parse($request->to);
        $t2 = Carbon::parse($order->from);
        $diff = date_diff($t1, $t2);

        $order->work_duration = $diff->format("%H:%I:%S");
        $order->save();

//                         send notification to drivers
$devicesTokens = Device::where('user_id', $order->user_id)
->get()
->pluck('device_token')
->toArray();

if ($devicesTokens) {
//                         $order->employee_id
$request->header('X-localization') == 'en' ?
sendMultiNotification( " Your order has been finalized ", "  Your order is now complete ", $devicesTokens)
:
sendMultiNotification( " طلبك مكتمل الان ", "  تم انهاء طلبك ", $devicesTokens);
}
saveNotification($order->user_id,' your order is complete','تم انهاء الطلب','Your order has been endes ','  طلبك مكتمل الان ',$order->id);
        
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
        $data = [];
        array_push($data, [
            'id' => intval($order->id),
            'real_num'=>intval($order->real_num),
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
            'images' => $img,
            'vedio' => $order->vedio != null ? asset('uploads/orders/vedio/' . $order->vedio) : null,
            'anotherServicesWithOrder' => $anotherServicesWithOrder,
            'notes_on_what_was_done' => $order->notes_on_what_was_done,
            'complete_in_another_day' => intval($order->complete_in_another_day),
            'restOfOrder' =>$restOfOrder,
        ]);
        return ApiController::respondWithSuccess($data);

    }

    public function fillTheBill(Request $request)
    {
        $rules = [
            'material_cost' => 'required|numeric',
            'price' => 'required|numeric',
            'order_id' => 'required|exists:orders,id',
            'notes_on_what_was_done'=>'nullable|max:225|string',

        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));


        $order = Order::find($request->order_id);
        if ($order->status == 3) {
            $addTax = Setting::find(1)->tax_for_completed_order_active;
            if ($addTax == 0) {
                $total = $request->price + $request->material_cost;
            } elseif ($addTax == 1) {

                if(Setting::find(1)->tax_for_completed_order == 0){
                    $total = $request->price + $request->material_cost ;
                }else{
                    $valueOfTax =  ($request->price + $request->material_cost) *  Setting::find(1)->tax_for_completed_order/ 100 ;

                    $total = $request->price + $request->material_cost + $valueOfTax;
                }

            }

            $order->material_cost = $request->material_cost;
            $order->price = $request->price;
            $order->total = $total;
            $order->notes_on_what_was_done = $request->notes_on_what_was_done;
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
                'real_num'=>intval($order->real_num),
                'category_id' => intval($order->category_id),
                'category' => $request->header('X-localization') == 'en' ? Category::find($order->category_id)->name : Category::find($order->category_id)->name_ar,
                'categoryImage' => Category::find($order->category_id)->image != null ? asset('/uploads/categories/' . Category::find($order->category_id)->image) : null,

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
                'total' => number_format((float)$order->total, 2, '.', ''),
                'is_paid' => intval($order->is_paid),
                'images' => $img,
                'vedio' => $order->vedio != null ? asset('uploads/orders/vedio/' . $order->vedio) : null,
                'tax_for_completed_order_active'=>Setting::find(1)->tax_for_completed_order_active,
'notes_on_what_was_done' => $order->notes_on_what_was_done,
'complete_in_another_day' => intval($order->complete_in_another_day),
'restOfOrder' =>$restOfOrder,
            ]);
            return ApiController::respondWithSuccess($data);

        } else {
            $errors = ['key' => 'error',
                'value' => $request->header('X-localization') == 'en' ? 'you must finish your  work before fill the bill' : 'يجب انهاء العمل اولا قبل ملئ الفاتورة'
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }


    }

    public function billPage(Request $request)
    {
        $rules = [
            'order_id' => 'required|exists:orders,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $order = Order::find($request->order_id);

        $rate = $order->rate()->first();
        $sentences = [];
        if ($rate != null) {
            if ($rate->sentences) {
                foreach ($rate->sentences as $sentence) {
                    array_push($sentences, [
                        'rate_id' => intval($sentence->pivot->rate_id),
                        'sentence_id' => intval($sentence->pivot->sentence_id),
                        'sentence' => $request->header('X-localization') == 'en' ? $sentence->sentence : $sentence->sentence_ar,
                    ]);
                }
            }
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
        $setting = Setting::find(1);
        $data = [];
        array_push($data, [
            'id' => intval($order->id),
            'real_num'=>intval($order->real_num),
            'category_id' => intval($order->category_id),
            'category' => $request->header('X-localization') == 'en' ? Category::find($order->category_id)->name : Category::find($order->category_id)->name_ar,
            'categoryImage' => Category::find($order->category_id)->image != null ? asset('/uploads/categories/' . Category::find($order->category_id)->image) : null,
 'tax_for_completed_order' => intval($setting->tax_for_completed_order),
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
            'is_paid' => intval($order->is_paid),
            'location_id' => intval($order->location_id),
            'locationName' => $order->location_id == null || $order->location_id == 0 ? null : ($request->header('X-localization') == 'en' ? Location::find($order->location_id)->name : Location::find($order->location_id)->name),
            'locationLat' => $order->location_id == null || $order->location_id == 0 ? null : Location::find($order->location_id)->latitude,
            'locationLong' => $order->location_id == null || $order->location_id == 0 ? null : Location::find($order->location_id)->longitude,
            'images' => $img,
            'total' => number_format((float)$order->total, 2, '.', ''),
            'vedio' => $order->vedio != null ? asset('uploads/orders/vedio/' . $order->vedio) : null,
            'rate' => $order->rate()->first() != null ? $order->rate()->first()->rate : null,
            'sentence' => $sentences,
            'comment' => $order->rate()->first() != null ? $order->rate()->first()->comment : null,
            'tax_for_completed_order_active'=>Setting::find(1)->tax_for_completed_order_active,
            'notes_on_what_was_done' => $order->notes_on_what_was_done,

            'complete_in_another_day' => intval($order->complete_in_another_day),
            'restOfOrder' =>$restOfOrder,

        ]);
        return ApiController::respondWithSuccess($data);
    }

}
