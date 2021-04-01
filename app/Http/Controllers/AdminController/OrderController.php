<?php

namespace App\Http\Controllers\AdminController;

use App\Models\Category;
use App\Models\Device;
use App\Models\Image;
use App\Models\Order;
use App\Models\RejectedDate;
use App\Models\Setting;
use App\Models\RejectedUser;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use function GuzzleHttp\Promise\all;

class OrderController extends Controller
{
    //


    /**
     * filter users depends on admin selection
     *
     * @param Request $request
     * @param Integer $id
     * @return array $users
     */
    public function categoryFilter(Request $request, $id = null)
    {
//        $catgeories= Category::where('id',$request->category_id)->get()->toArray();
        $orders = Order::where([
            ['category_id',$id],
            ['status', 1]

        ])->get()->toArray();
        return $orders;
    }


    public function newOrders()
    {
        $orders = Order::where('status', 1)->orderBy('id', 'desc')->get();
        return view('admin.orders.new', compact('orders'));
    }

    public function activeOrders()
    {
        $orders = Order::where('status', 2)->orderBy('id', 'desc')->get();
        return view('admin.orders.active', compact('orders'));
    }

    public function completedOrders()
    {
        $orders = Order::where([['status', 3], ['is_paid', 1]])->orWhere([['status', 3], ['is_paid', 2]])->orderBy('id', 'desc')->get();
        return view('admin.orders.completed', compact('orders'));
    }


    public function waitedOrders()
    {
        $orders = Order::where([['status', 5], ['complete_in_another_day', 1]])->orderBy('id', 'desc')->get();
        return view('admin.orders.waited-order', compact('orders'));
    }

    public function completedOrdersNotPaid()
    {
        $orders = Order::where('status', 3)->where('is_paid', 0)->orderBy('id', 'desc')->get();
        return view('admin.orders.completedNotPaidOrder', compact('orders'));
    }
    public function canceledOrdersBeforeStated()
    {
        $orders = Order::where('status', 6)->orderBy('id', 'desc')->get();
        return view('admin.orders.canceledOrdersBeforeStated', compact('orders'));
    }

    public function canceledOrders()
    {
        $orders = Order::where('status', 4)->orderBy('id', 'desc')->get();
        return view('admin.orders.canceled', compact('orders'));
    }


    public function changeOrderStatus(Request $request, $id)
    {

        $order = Order::find($id);
        if ($request->status != null) {
            $order->status = $request->status;
            $order->save();
            if($order->status == 2){

//                         send notification to drivers
$devicesTokens = Device::where('user_id', $order->user_id)
->get()
->pluck('device_token')
->toArray();

if ($devicesTokens) {
//                         $order->employee_id
if(User::find($order->user_id)->language == 'en'){
    sendMultiNotification( "Your order has been initiated", "  Your order is active ", $devicesTokens);
}else{
    sendMultiNotification( " طلبك نشط", "  تم البدء في طلبك ", $devicesTokens);
}

}
saveNotification($order->user_id,' your order is active','تم البدء في طلب','Your order has been initiated ','  طلبك نشط الان ',$order->id);
            }
            if($order->status == 3){
                //                         send notification to drivers
$devicesTokens = Device::where('user_id', $order->user_id)
->get()
->pluck('device_token')
->toArray();

if ($devicesTokens) {
//                         $order->employee_id

if(User::find($order->user_id)->language == 'en'){
    sendMultiNotification( " Your order has been finalized ", "  Your order is now complete ", $devicesTokens);
}else{
    sendMultiNotification( " طلبك مكتمل الان ", "  تم انهاء طلبك ", $devicesTokens);
}


}
saveNotification($order->user_id,' your order is complete','تم انهاء الطلب','Your order has been endes ','  طلبك مكتمل الان ',$order->id);
            }
            if($order->status  == 4){
//                         send notification to drivers
$devicesTokens = Device::where('user_id', $order->user_id)
->get()
->pluck('device_token')
->toArray();

if ($devicesTokens) {
//                         $order->employee_id
if(User::find($order->user_id)->language == 'en'){
    sendMultiNotification( " Your order has been canceled ", " Contact the administration to find out the reason for canceling your order ", $devicesTokens);
}else{
    sendMultiNotification( " تواصل مع الادارة لمعرفه سبب الغاء طلبك ", " تم الغاء طلبك ", $devicesTokens);
}

}
saveNotification($order->user_id,' your order is reject','تم الغاء  طلبك','Contact the administration to find out the reason for canceling your order ','  تواصل مع الادارة لمعرفه سبب الغاء طلبك  ',$order->id);
            }
            flash(app()->getLocale() == 'en' ? 'The status of the order has changed successfully' : 'تم تغيير حاله الطلب بنجاح')->success();
            return redirect()->back();
        } else {
            flash(app()->getLocale() == 'en' ? 'Confirm the status of the order to be transferred to' : 'تاكد من حاله الطلب المراد التحويل لها')->error();
            return redirect()->back();
        }


    }


    public function createOrderPage()
    {
        return view('admin.orders.create');
    }

    public function createOrder(Request $request)
    {
      
        $this->validate($request, [
            'branch_id' => 'required|exists:branches,id',
            'category_id' => 'required|exists:categories,id',
            'user_id' => 'required|exists:users,id',
            'order_shift_id' => 'required|exists:order_shifts,id',
            'employee_id' => 'required|exists:users,id',
            'date' => 'required|date_format:Y-m-d',
            'note' => 'nullable|string',
            'latitude' => 'required',
            'longitude' => 'required',
            "image" => 'nullable',
            "image*" => 'nullable|mimes:jpeg,bmp,png,jpg,gif,ico,psd,webp,tif,tiff|max:5000',
        ]);
        $allImage = $request->image;
if($allImage){
    foreach ($allImage as $image) {
        if ($image->getClientOriginalExtension() == "jfif") {
            flash(app()->getLocale() == 'en' ? 'This image format is not supported' : 'صيغه هذه الصوره غير  مدعومه')->error();
            return back();
        }

    }
}
    


        $today = Carbon::today();

        if (($request->date >= $today->format('Y-m-d')) == false) {
            flash(app()->getLocale() == 'en' ? 'You cannot add a previous date You cannot submit a request on an earlier date' : 'لا يمكنك ارسال طلب في تاريخ سابق')->error();
            return redirect()->route('new_orders');
        }

        // $rejectedDate = RejectedDate::where('reject_date', $request->date)->first();
        // if ($rejectedDate) {

        //     flash(app()->getLocale() == 'en' ? 'This date is blocked by the administration' : 'هذا التاريخ محجوب من قبل الادارة')->error();
        //     return redirect()->route('new_orders');
        // }

        $categories = User::find($request->employee_id)->categories()->get();
        foreach ($categories as $category) {
            if ($category->id == $request->category_id) {


                $lastNumInOrders = Order::orderBy('id','desc')->first();


        $reject = RejectedDate::where('reject_date', $request->date)->first();
        $rejectDateTime =  $reject != null ?RejectedUser::where('order_shift_id', $request->order_shift_id)->where('rejected_date_id',$reject->id)->first() : null;
    
    if($rejectDateTime != null){
     $rejectUser =  $rejectDateTime->rejecteds()->where('user_id',$request->employee_id)->first();
    }
    
    
    
    
        // check send order in rejected date or note
    
        if ($rejectUser != null) { 
      
            flash(app()->getLocale() == 'en' ? 'This date is blocked by the administration' : 'هذا التاريخ محجوب من قبل الادارة')->error();
            return redirect()->route('new_orders');
        }


                $order = Order::create([
                    'category_id' => $request->category_id,
                    'user_id' => $request->user_id,
                    'order_shift_id' => $request->order_shift_id,
                    'employee_id' => $request->employee_id,
                    'date' => $request->date,
                    'note' => $request->note!= null ? $request->note : null,
                    'status' => 1,
                    'tax' => 1,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'real_num' =>$lastNumInOrders != null ?($lastNumInOrders->real_num != null ?($lastNumInOrders->real_num +1):1):1
                ]);
                if($allImage){
                    foreach ($allImage as $image) {

                        $order->images()->create([
                            'image' => UploadImage($image, 'order', 'uploads/orders'),
                        ]);
    
                    }
                }
          
                //   send notification to drivers
                $devicesTokens = Device::where('user_id', $order->employee_id)
                    ->get()
                    ->pluck('device_token')
                    ->toArray();

                if ($devicesTokens) {
                    if(User::find($order->employee_id)->language == 'en'){
                        sendMultiNotification( "order from  " . User::find($order->user_id)->name , " Scan for new orders", $devicesTokens);

                    }else{
                        sendMultiNotification( ' طلب من' . User::find($order->user_id)->name ,  " تفحص الطلبات الجديدة", $devicesTokens);

                    }

                }
                saveNotification($order->employee_id, 'order from' . User::find($order->user_id)->name, User::find($order->user_id)->name . "طلب  من ", 'check new orders', " تفحص الطلبات الجديدة", $order->id);


//send notifications to client 
//                         send notification to drivers
$devicesTokens = Device::where('user_id', $order->user_id)
->get()
->pluck('device_token')
->toArray();

if ($devicesTokens) {
//                         $order->employee_id
if(User::find($order->user_id)->language == 'en'){
    sendMultiNotification( 'Your order has been sent', " Your order has been sent successfully", $devicesTokens);
}else{
    sendMultiNotification( 'تم ارسال طلبك بنجاح', " تم ارسال طلبك", $devicesTokens);
}

}
saveNotification($order->user_id,'your order send successfully',   'تم ارسال طلبك بنجاح', 'see your new orders', " تفحص الطلبات الجديدة", $order->id);

                flash(app()->getLocale() == 'en' ? 'created successfully' : 'تم حفظ الطلب بنجاح')->success();
                return back();
            }
        }
        flash(app()->getLocale() == 'en' ? 'You cannot register an order, this employee does not provide this service' : 'لا يمكنك تسجيل الطلب ,هذا الموظف لا يقدم هذه الخدمة')->error();
        return back();

    }

    public function editOrderPage(Request $request, $id)
    {
        $order = Order::find($id);
        if ($order->status == 1) {
            return view('admin.orders.edit', compact('order'));

        } else {
            flash(app()->getLocale() == 'en' ? 'You cannot enter this page' : 'لا يمكن دخول هذه الصفحه')->error();
            return back();
        }
    }


    public function editWaitedOrderPage(Request $request, $id)
    {
        $order = Order::find($id);
        if ($order->status == 5) {
            return view('admin.orders.edit_waited_order', compact('order'));

        } else {
            flash(app()->getLocale() == 'en' ? 'You cannot enter this page' : 'لا يمكن دخول هذه الصفحه')->error();
            return back();
        }
    }


    public function updateWaitedOrderPage(Request $request, $id)
    {
        $this->validate($request, [
//            'branch_id' => 'exists:branches,id',
//            'category_id' => 'exists:categories,id',
            // 'user_id' => 'exists:users,id',
            'order_shift_id' => 'exists:order_shifts,id',
            // 'employee_id' => 'exists:users,id',
            // 'date' => 'date_format:Y-m-d',
//            'note' => 'string',

            "image" => 'nullable',
            "image*" => 'required|mimes:jpeg,bmp,png,jpg,gif,ico,psd,webp,tif,tiff|max:5000',
        ]);
        $allImage = $request->image;
        if ($allImage) {
            foreach ($allImage as $image) {
                if ($image->getClientOriginalExtension() == "jfif") {
                    flash(app()->getLocale() == 'en' ? 'This image format is not supported' : 'صيغه هذه الصوره غير  مدعومه')->error();
                    return back();
                }

            }
        }


        $order = Order::find($id);
$employee = $order->employee_id;
        $categories = User::find($employee)->categories()->get();
        foreach ($categories as $category) {
            $cat = $request->category_id != null ?$request->category_id:$order->category_id;
            if ($category->id == $cat) {
// $order->update($request->all());

               $order->update([
                   'category_id' => $cat,
                //    'user_id' => $request->user_id,
                //    'order_shift_id' => $request->order_shift_id,
                //    'employee_id' => $request->employee_id,
                //    'date' => $request->date,
                   'note' => $request->note,
                   'status' => $order->status,
                   'latitude' => $request->latitude,
                   'longitude' => $request->longitude,
               ]);

               $lastRreschedule = $order->timeOrders()->orderBy('id','desc')->first();
               if($request->date){
              
                   $lastRreschedule->date =  $request->date;
                   $lastRreschedule->save();
               }
               if( $request->order_shift_id){
              
                $lastRreschedule->order_shift_id =  $request->order_shift_id;
                $lastRreschedule->save();
            }

                if ($allImage) {
                    foreach ($allImage as $image) {

                        $order->images()->create([
                            'image' => UploadImage($image, 'order', 'uploads/orders'),
                        ]);

                    }
                }

if($request->date || $request->order_shift_id){
//                         send notification to drivers employee
$devicesTokens = Device::where('user_id', $order->employee_id)
->get()
->pluck('device_token')
->toArray();

if ($devicesTokens) {
//                         $order->employee_id
if(User::find($order->employee_id)->language == 'en'){
    sendMultiNotification( 'The order has been modified','The order has been modified', $devicesTokens);
}else{
    sendMultiNotification( 'تم التعديل علي الطلب','تم التعديل علي الطلب', $devicesTokens);
}

}
saveNotification($order->employee_id, 'check order','تفحص الطلب ',  ' check order ','تفحص الطلب ',  $order->id);

// send notification to user
$devicesTokens = Device::where('user_id', $order->user_id)
->get()
->pluck('device_token')
->toArray();

if ($devicesTokens) {
//                         $order->employee_id
if(User::find($order->user_id)->language == 'en'){
    sendMultiNotification( 'The order has been modified','The order has been modified', $devicesTokens);
}else{
    sendMultiNotification( 'تم التعديل علي الطلب','تم التعديل علي الطلب', $devicesTokens);
}


}
saveNotification($order->user_id, 'check order','تفحص الطلب ',  ' check order ','تفحص الطلب ',  $order->id);

}
                flash(app()->getLocale() == 'en' ? 'updated successfully' : 'تم تعديل الطلب بنجاح')->success();
                return back();


            }
        }
        flash(app()->getLocale() == 'en' ? 'You cannot register an order, this employee does not provide this service' : 'لا يمكنك تسجيل الطلب ,هذا الموظف لا يقدم هذه الخدمة')->error();
        return back();

    }


    public function updateOrderPage(Request $request, $id)
    {
        $this->validate($request, [
//            'branch_id' => 'exists:branches,id',
//            'category_id' => 'exists:categories,id',
            // 'user_id' => 'exists:users,id',
            'order_shift_id' => 'exists:order_shifts,id',
            // 'employee_id' => 'exists:users,id',
            // 'date' => 'date_format:Y-m-d',
//            'note' => 'string',

            "image" => 'nullable',
            "image*" => 'required|mimes:jpeg,bmp,png,jpg,gif,ico,psd,webp,tif,tiff|max:5000',
        ]);
        $allImage = $request->image;
        if ($allImage) {
            foreach ($allImage as $image) {
                if ($image->getClientOriginalExtension() == "jfif") {
                    flash(app()->getLocale() == 'en' ? 'This image format is not supported' : 'صيغه هذه الصوره غير  مدعومه')->error();
                    return back();
                }

            }
        }


        $order = Order::find($id);
$employee = $order->employee_id;
        $categories = User::find($employee)->categories()->get();
        foreach ($categories as $category) {
            $cat = $request->category_id != null ?$request->category_id:$order->category_id;
            if ($category->id == $cat) {
// $order->update($request->all());

               $order->update([
                   'category_id' => $cat,
                //    'user_id' => $request->user_id,
                   'order_shift_id' => $request->order_shift_id,
                //    'employee_id' => $request->employee_id,
                   'date' => $request->date,
                   'note' => $request->note,
                   'status' => $order->status,
                   'latitude' => $request->latitude,
                   'longitude' => $request->longitude,
               ]);

                if ($allImage) {
                    foreach ($allImage as $image) {

                        $order->images()->create([
                            'image' => UploadImage($image, 'order', 'uploads/orders'),
                        ]);

                    }
                }

if($request->date || $request->order_shift_id){
//                         send notification to drivers employee
$devicesTokens = Device::where('user_id', $order->employee_id)
->get()
->pluck('device_token')
->toArray();

if ($devicesTokens) {
//                         $order->employee_id
if(User::find($order->employee_id)->language == 'en'){
    sendMultiNotification( 'The order has been modified','The order has been modified', $devicesTokens);
}else{
    sendMultiNotification( 'تم التعديل علي الطلب','تم التعديل علي الطلب', $devicesTokens);
}

}
saveNotification($order->employee_id, 'check order','تفحص الطلب ',  ' check order ','تفحص الطلب ',  $order->id);

// send notification to user
$devicesTokens = Device::where('user_id', $order->user_id)
->get()
->pluck('device_token')
->toArray();

if ($devicesTokens) {
//                         $order->employee_id
if(User::find($order->user_id)->language == 'en'){
    sendMultiNotification( 'The order has been modified','The order has been modified', $devicesTokens);
}else{
    sendMultiNotification( 'تم التعديل علي الطلب','تم التعديل علي الطلب', $devicesTokens);
}


}
saveNotification($order->user_id, 'check order','تفحص الطلب ',  ' check order ','تفحص الطلب ',  $order->id);

}
                flash(app()->getLocale() == 'en' ? 'updated successfully' : 'تم تعديل الطلب بنجاح')->success();
                return back();


            }
        }
        flash(app()->getLocale() == 'en' ? 'You cannot register an order, this employee does not provide this service' : 'لا يمكنك تسجيل الطلب ,هذا الموظف لا يقدم هذه الخدمة')->error();
        return back();

    }


    public function imageRemove($id)
    {

        $deleted = Image::where('id', $id)->delete();
        if ($deleted) {
            $v = '{"message":"done"}';
            return response()->json($v);
        }

    }

    public function showOrderPage($id)
    {
        $order = Order::find($id);
        return view('admin.orders.show', compact('order'));
    }

    public function showBillPage($id)
    {
        $order = Order::find($id);
        if($order->is_paid == 0){
            return view('admin.orders.billPage', compact('order'));

        }else{
            flash(app()->getLocale() == 'en'?'you cannot enter this page':'لا يمكنك دخول هذه الصفحه')->error();
            return redirect()->back();
        }
    }

    public function showBillCompletedOrder($id)
    {
        $order = Order::find($id);
        if ($order->is_paid == 1|| $order->is_paid == 2) {
            return view('admin.orders.billPageCompletedOrder', compact('order'));

        } else {
            flash(app()->getLocale() == 'en' ? 'you cannot enter this page' : 'لا يمكنك دخول هذه الصفحه')->error();
            return redirect()->back();
        }
    }



    public function editBill(Request $request, $id)
    {
        $order = Order::find($id);
        $this->validate($request, [
            'is_paid' => 'nullable|in:0,1,2',
            'employee_note' => 'nullable|string',
            'from' => 'nullable',
            'to' => 'nullable',
            'material_cost' => 'nullable|numeric',
            'price' => 'nullable|numeric',
        ]);

        $order->update([
            'is_paid' =>$request->is_paid,
            'employee_note' =>$request->employee_note,
            'from' =>$request->from,
            'to' =>$request->to,
            'material_cost' =>$request->material_cost,
            'price' =>$request->price,
        ]);
        $order->total = $order->price + $order->material_cost + Setting::find(1)->tax;
        $order->save();
flash(app()->getLocale() == 'en'?'edited successfully':'تم التعديل بنجاح')->success();
return redirect()->route('completed_ordersNotPaid');
    }


    public function redirectOrderToEmployeePage($id)
    {
        $order = Order::find($id);
        return view('admin.orders.redirect_order_to_employee', compact('order'));
    }

    public function redirectOrderToEmployee(Request $request, $id)
    {
        $order = Order::find($id);

        $this->validate($request, [
            'employee_id' => 'exists:users,id',
        ]);
        $employee = User::find($request->employee_id);

        $categories = $employee->categories()->get();
//        foreach ($categories as $category) {
//            if ($category->id == $order->category_id) {

                $order->employee_id = $request->employee_id;
                $order->save();

                // send notification to drivers
//                $devicesTokens = Device::where('user_id', $order->user_id)
//                    ->get()
//                    ->pluck('device_token')
//                    ->toArray();
//
//                if ($devicesTokens) {
                //$order->employee_id
//                    sendMultiNotification($request->user()->name."طلب  من  ", " تفحص الطلبات الجديدة"   ,$devicesTokens);
//                }
                saveNotification($order->employee_id, 'order from' . User::find($order->user_id)->name, User::find($order->user_id)->name . "طلب  من ", 'see your new orders', " تفحص الطلبات الجديدة", $order->id);

                flash(app()->getLocale() == 'en' ? 'The employee has been successfully changed' : 'تم تغير الموظف بنجاح ')->success();
                return redirect()->back();
//            }
//            else {
//                flash(app()->getLocale() == 'en' ? 'This employee does not provide this service' : 'هذا الموظف لا يقدم هذه الخدمة ')->error();
//                return redirect()->back();
//            }
//        }
    }


    public function showImagesOfOrder($id)
    {
        $order = Order::find($id);
        return view('admin.orders.show_order_photo', compact('order'));
    }

    public function showVedioOfOrder($id)
    {
        $order = Order::find($id);
        return view('admin.orders.show_order_vedio', compact('order'));
    }
}
