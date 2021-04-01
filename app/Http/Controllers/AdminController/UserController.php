<?php

namespace App\Http\Controllers\AdminController;

use App\City;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;
use Auth;

use Image;
use Illuminate\Support\Facades\Storage;
use function GuzzleHttp\Promise\all;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('type', 2)->orderBy('id', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        dd($request->all());
        $this->validate($request, [
            'phone' => 'required|numeric',
            'name' => 'required|max:255',
            'image' => 'nullable|mimes:jpeg,bmp,png,jpg,gif,ico,psd,webp,tif,tiff|max:5000',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|same:password',
            'branch_id' => 'required|exists:branches,id',
            'category_id' => 'required|array',
            'category_id.*' => 'exists:categories,id', // check each item in the array
            'active' => 'required|in:0,1',
            'available_orders' => 'required|numeric'
        ]);
        if ($request->image) {
            if ($request->image->getClientOriginalExtension() == "jfif") {
                flash(app()->getLocale() == 'en'?'This image format is not supported':'صيغه هذه الصوره غير  مدعومه')->error();
                return back();
            }
        }


        $check =  User::where('phone', $request->phone)
        ->where('type' , 2)
        ->first();
if($check ==  null )
{
        $user = User::create([
            'phone' => $request->phone,
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'image' => $request->file('image') == null ? 'default.jpg' : UploadImage($request->file('image'), 'image', '/uploads/users'),
            'branch_id' => $request->branch_id,
            'type' => 2,
            'active' => $request->active,
            'available_orders' => $request->available_orders,
        ]);
        $user->categories()->sync($request->category_id);
        $user->save();
      flash(app()->getLocale() == 'en'?'User created successfully':  'تم أنشاء  المستخدم  بنجاح')->success();
        return redirect('admin/users');
}else{
    flash(app()->getLocale() == 'en'?'this phone number is used before':'هذا الرقم مستخدم من قبل')->error();
    return redirect('admin/users');
}
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrfail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'phone' => 'required|numeric',
            'name' => 'required|max:255',
            'image' => 'nullable|mimes:jpeg,bmp,png,jpg,gif,ico,psd,webp,tif,tiff|max:5000',
            'password' => 'nullable|string|min:6',
            'password_confirmation' => 'nullable|same:password',
            'branch_id' => 'required|exists:branches,id',
            'category_id' => 'required|array',
            'category_id.*' => 'exists:categories,id', // check each item in the array
            'active' => 'nullable|in:0,1',
            'available_orders' => 'nullable|numeric'


        ]);
        $user = User::findOrFail($id);

        $phoneCheck = User::where('phone',$request->phone)->where('type',2)->whereNotIn('id',[$user->id])->first();
        if($phoneCheck)
        {
            flash(app()->getLocale() == 'en'?'this phone number is used before':'هذا الرقم مستخدم من قبل')->error();
            
            return redirect('admin/users');
        }


    
        $user->update([
            'phone' => $request->phone == null ? $user->phone : $request->phone,
            'name' => $request->name == null ? $user->name : $request->name,
            'branch_id' => $request->branch_id == null ? $user->branch_id : $request->branch_id,
            'active' => $request->active == null ? $user->active : $request->active,
            'available_orders' => $request->available_orders,
            'password' => $request->password == null ? $user->password : Hash::make($request->password),
            'image' => $request->file('image') == null ? $user->image : UploadImage($request->file('image'), 'image', '/uploads/users'),
        ]);

if($request->category_id){
    $orders = $user->employeeOrders()->whereIn('category_id',[$request->category_id])->get();
if($orders->count() == 0){
    flash(app()->getLocale() == 'en'?'The service that contains a order cannot be deleted by this employee':'لا يمكن حذف الخدمه التي تحتوي علي طلب لدي هذا الموظف')->error();
    return redirect('admin/users');
}
CategoryUser::where('user_id',$user->id)->delete();

    foreach($request->category_id as $cat){
        CategoryUser::create([
            'user_id'=>$user->id,
            'category_id' =>$cat,
        ]);
    }
}
     
        
        $user->save();
        flash(app()->getLocale() == 'en'?'updated successfully':'تم تعديل بيانات  المستخدم  بنجاح')->success();
        return redirect('admin/users');

    }

    public function update_pass(Request $request, $id)
    {
        //
        $this->validate($request, [
            'password' => 'required|string|min:6|confirmed',

        ]);
        $users = User::findOrfail($id);
        $users->password = Hash::make($request->password);

        $users->save();

        return redirect()->back()->with('information', app()->getLocale() == 'en'?'updated successfully':'تم تعديل كلمة المرور المستخدم');
    }

    public function update_privacy(Request $request, $id)
    {
        //
        $this->validate($request, [
            'active' => 'required',

        ]);
        $users = User::findOrfail($id);
        $users->active = $request->active;

        $users->save();

        return redirect()->back()->with('information',app()->getLocale() == 'en'?'updated successfully': 'تم تعديل اعدادات المستخدم');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $users = User::find($id);
        if($users->employeeOrders()->get()){
            flash(app()->getLocale() == 'en'?'you cannot delete this employee':'لا يمكن حذف هذا الموظف')->error();

            return back();
        }
        $users->delete();
        flash(app()->getLocale() == 'en'?'deleted successfully':'تم حذف  بيانات  المستخدم  بنجاح')->success();

        return back();
    }


    public function get_categories($id)
    {
        if(app()->getLocale() == 'en'){
            $categories = Category::whereBranch_id($id)
            ->get(['name', 'id']);
        }else{
            $categories = Category::whereBranch_id($id)
            ->get(['name_ar', 'id']);
        }


        return $categories;
    }

}
