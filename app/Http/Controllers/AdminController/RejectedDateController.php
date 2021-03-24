<?php

namespace App\Http\Controllers\AdminController;

use App\Models\RejectedDate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RejectedDateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $rejectesDates = RejectedDate::get();
        return  view('admin.rejected_dates.index',compact('rejectesDates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return  view('admin.rejected_dates.create');
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
        $this->validate($request,[
            'date' =>'required|date_format:Y-m-d',
            'reason' =>'required|max:225',
            'reason_en' =>'required|max:225',

        ]);

        $today = Carbon::today();

if($request->date >= $today->format('Y-m-d'))
{
    $rejectedDate = RejectedDate::create([
        'reject_date' => $request->date,
        'reason' =>$request->reason,
        'reason_en' =>$request->reason_en,
    ]);
    flash(app()->getLocale() == 'en' ?'added successfully':'تم الاضافه بنجاح')->success();
    return redirect()->route('rejected_dates.index');
}else{
    flash(app()->getLocale() == 'en' ?'You cannot add a previous date':'لا يمكنك اضافه تاريخ سابق')->error();
    return redirect()->route('rejected_dates.index');
}

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
        $rejectDate = RejectedDate::find($id);
        return  view('admin.rejected_dates.edit',compact('rejectDate'));
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
        //
        $rejectedDate =RejectedDate::find($id);
        $this->validate($request,[
            'date' =>'required|date_format:Y-m-d',
            'reason' =>'nullable',
            'reason_en' =>'nullable',
        ]);

        $today = Carbon::today();

        if($request->date >= $today->format('Y-m-d'))
        {
            $rejectedDate->update([
                'reject_date' => $request->date,
                'reason' =>$request->reason,
                'reason_en' =>$request->reason_en,
            ]);
            flash(app()->getLocale() == 'en' ?'edited successfully':'تم التعديل بنجاح')->success();
            return redirect()->route('rejected_dates.index');
        }else{
            flash(app()->getLocale() == 'en' ?'You cannot add a previous date':'لا يمكنك اضافه تاريخ سابق')->error();
            return redirect()->route('rejected_dates.index');
        }
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
        $rejectDate = RejectedDate::find($id);
        $rejectDate->delete();
        flash(app()->getLocale() == 'en'?'deleted successfully':'تم الحذف بنجاح')->success();
        return redirect()->route('rejected_dates.index');

    }
}
