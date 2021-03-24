<?php

namespace App\Http\Controllers\AdminController;

use App\Models\Branch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $branches = Branch::get();
        return  view('admin.branches.index',compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return  view('admin.branches.create');
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
        $this->validate($request, [
            'name'                 => 'required|max:255',
            'name_ar'                  => 'required|max:255',
            'latitude'              => 'required',
            'longitude' => 'required',
        ]);
        $branch = Branch::create([
            'name'          => $request->name,
            'name_ar'           => $request->name_ar,
            'latitude'         => $request->latitude,
            'longitude'    =>$request->longitude,

        ]);
        flash(app()->getLocale() == 'en'?'user created successfully':'تم أنشاء  المستخدم  بنجاح')->success();
        return redirect('admin/branches');
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
        $branch = Branch::find($id);
        return view('admin.branches.edit',compact('branch'));
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
            'name' => 'max:255',
            'name_ar' => 'max:255',

        ]);

        $branch = Branch::findOrFail($id);
 $branch->update($request->all());

        flash(app()->getLocale() == 'en'?'updated successfully':'تم التعديل بنجاح')->success();
        return redirect('admin/branches');
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
        $branch = Branch::find($id);
        if($branch->categories()->count() >0){
            flash(app()->getLocale()=='en'?'Cannot delete this branch, it contains services':'لا يمكن مسح هذا الفرع,يحتوي علي خدمات')->error();
            return redirect('admin/branches');
        }else{
            $branch->delete();
            flash(app()->getLocale() =='en'?'deleted successfully':'تم الحذف بنجاح')->success();
            return redirect('admin/branches');
        }
    }
}
