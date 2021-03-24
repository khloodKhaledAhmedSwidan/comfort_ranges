<?php

namespace App\Http\Controllers\AdminController;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $roles = Role::get();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $permission = Permission::all();
//      return $permission;
        if ($permission) {
            return view('admin.roles.create', compact('permission'));
        } else {
            return view('admin.roles.create');
        }
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
        $request->validate([
            'name' => 'required|unique:roles,name',
            'name_ar' => 'required|unique:roles,name_ar',
            'display_name' => 'required',
            'display_name_ar' => 'required',
            'description' => 'required',
            'description_ar' => 'required',
            'permission_list.*' => 'required|exists:permissions,id',

        ]);
        $records = Role::create([
            'name' => $request->name,
            'name_ar' => $request->name_ar,
            'display_name' => $request->display_name,
            'display_name_ar' => $request->display_name_ar,
            'description' => $request->description,
            'description_ar' => $request->description_ar,
        ]);

        $records->permissions()->attach($request->permission_list);

//        $notification = array(
//            'message' => 'role created successfully!',
//            'alert-type' => 'success'
//        );
        flash(app()->getLocale() == 'en'?'added successfully':'تم الاضافه بنجاح')->success();
        return redirect()->route('roles.index');
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
        $role = Role::find($id);

        $permission = $role->permissions()->get();
  
        return view('admin.roles.edit', compact('role', 'permission'));
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
        $records = Role::find($id);
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'name_ar' => 'required|unique:roles,name_ar,' . $id,
            'display_name' => 'required',
            'description' => 'required',
            'permission_list.*' => 'required|exists:permissions,id',
            'display_name_ar' => 'required',
            'description_ar' => 'required',

        ]);

        $records->update($request->all());
        $records->permissions()->sync($request->permission_list);
//        $notification = array(
//            'message' => 'role updated successfully!',
//            'alert-type' => 'info'
//        );
        flash(app()->getLocale()== 'en'?'updated successfully': 'تم التعديل بنجاح')->success();
        return redirect()->route('roles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
//        dd($id);
        $role = Role::find($id);
        if (!$role || $role->admins()->count()>0) {
            return back()->with('error',app()->getLocale() == 'en'?'You cannot delete this authority':'لا يمكنك مسح هذه الصلاحية');
        }
        $role->delete();
        return back()->with('error', app()->getLocale() == 'en'?'deleted successfully':'تم الحذف بنجاح');
    }
}
