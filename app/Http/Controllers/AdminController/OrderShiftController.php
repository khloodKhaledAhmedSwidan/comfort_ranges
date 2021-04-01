<?php

namespace App\Http\Controllers\AdminController;

use App\Models\OrderShift;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $orderShifts = OrderShift::get();
        return  view('admin.orderShifts.index',compact('orderShifts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        return  view('admin.orderShifts.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        // $orderShifts =OrderShift::count();
        // //add 2 order shifts
        // if($orderShifts > 3) {
            $this->validate($request, [
                'from'                 => 'required|date_format:H:i',
                'to'                  => 'required|date_format:H:i',
//            'name'              => 'required|max:255',

            ]);
            $orderShift = OrderShift::create([
                'from'          => $request->from,
                'to'           => $request->to,
//            'name'         => $request->name,

            ]);
            flash(app()->getLocale()=='en'?'The period has been created successfully':'تم أنشاء  الفترة  بنجاح')->success();
            return redirect('admin/orderShifts');
        // }else{
        //     flash(app()->getLocale() == 'en'?'It is not possible to add more than two periods':'لا يمكن اضافه اكتر من فترتين')->error();
        //     return redirect('admin/orderShifts');
        // }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $orderShift = OrderShift::find($id);
        return view('admin.orderShifts.edit',compact('orderShift'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->validate($request, [
//            'from' => 'nullable|date_format:H:i',
//            'to' => 'nullable|date_format:H:i',
            'from' => 'nullable',
            'to' => 'nullable',
//            'name' => 'max:255',

        ]);
        $orderShift = OrderShift::findOrFail($id);
        $orderShift->update([
            'from'          => $request->from,
            'to'           => $request->to,
        ]);

        flash(app()->getLocale() == 'en'?'updated successfully':'تم التعديل بنجاح')->success();
        return redirect('admin/orderShifts');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $orderShift = OrderShift::find($id);
        if($orderShift->orders()->count() >0 || $orderShift->CategoryOrderShifts()->count() > 0){
            flash( app()->getLocale() == 'en'?'This period cannot be cleared, it contains requests':'لا يمكن مسح هذا الفترة,تحتوي علي طلبات')->error();
            return redirect('admin/orderShifts');
        }else{
            $orderShift->delete();
            flash(app()->getLocale() == 'en'?'deleted successfully':'تم الحذف بنجاح')->success();
            return redirect('admin/orderShifts');
        }
    }
}
