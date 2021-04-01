<?php

namespace App\Http\Controllers\AdminController;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RejectedDate;
use App\Models\RejectedUser;

class RejectedUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $rejectesUsers = RejectedUser::orderBy('id','desc')->get();
        return  view('admin.rejected_users.index',compact('rejectesUsers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return  view('admin.rejected_users.create');
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
            'user_id' =>'required|array',
            'user_id.*' =>'exists:users,id',
            'rejected_date_id' =>'required|exists:rejected_dates,id',
            'order_shift_id' =>'required|exists:order_shifts,id',

        ]);

        $rejectedUser = RejectedUser::create([
            'rejected_date_id' =>$request->rejected_date_id,
            'order_shift_id' =>$request->order_shift_id,
        ]);

if($request->user_id){
    foreach($request->user_id as $user){
   
        $rejectedUser->rejecteds()->create([
            'user_id' =>$user
        ]);
    }

    flash(app()->getLocale() == 'en' ?'added successfully':'تم الاضافه بنجاح')->success();
    return redirect()->route('rejected_users.index');
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
        $rejectUser = RejectedUser::find($id);
        return  view('admin.rejected_users.edit',compact('rejectUser'));
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
        $rejectedUser = RejectedUser::find($id);
        $this->validate($request,[
            'user_id' =>'nullable|array',
            'user_id.*' =>'exists:users,id',
            'rejected_date_id' =>'nullable|exists:rejected_dates,id',
            'order_shift_id' =>'nullable|exists:order_shifts,id',
        ]);

   
            $rejectedUser->update([
                'rejected_date_id' => $request->rejected_date_id != null ?$request->rejected_date_id: $rejectedUser->rejected_date_id,
                'order_shift_id' => $request->order_shift_id != null ?$request->order_shift_id: $rejectedUser->order_shift_id,
            ]);

            if($request->user_id){
                $rejectedUser->rejecteds()->delete();
                foreach($request->user_id as $user){
                    $rejectedUser->rejecteds()->create([
                        'user_id' =>$user
                    ]);
                }
            
            }
            flash(app()->getLocale() == 'en' ?'edited successfully':'تم التعديل بنجاح')->success();
            return redirect()->route('rejected_users.index');
    
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
        $rejectUser = RejectedUser::find($id);
        $rejectUser->delete();
        flash(app()->getLocale() == 'en'?'deleted successfully':'تم الحذف بنجاح')->success();
        return redirect()->route('rejected_users.index');

    }
}
