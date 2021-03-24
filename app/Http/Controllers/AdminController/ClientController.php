<?php

namespace App\Http\Controllers\AdminController;

use App\Models\Location;
use App\User;
use http\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    //
    public function is_active(Request $request, $id)
    {

        if ($request->ajax()) {
            $user = User::findOrfail($id);
            if ($user->active == 1) {
                $user->active = 0;
                $user->save();

            } else {
                $user->active = 1;
                $user->save();
            }

            return 'true';
        }


    }
    public function index()
    {
        $clients = User::where('type', 1)->orderBy('id', 'desc')->get();
        return view('admin.clients.index', compact('clients'));
    }
    public function create()
    {
        return view('admin.clients.create');
    }


    public function store(Request $request)
    {
//        dd($request->all());
        $this->validate($request, [
            'phone' => 'required|numeric',
            'name' => 'required|max:255',
            'image' => 'nullable|mimes:jpeg,bmp,png,jpg,gif,ico,psd,webp,tif,tiff|max:5000',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|same:password',
            'active' => 'required|in:0,1',
            'longitude' => 'required',
            'latitude' => 'required',
        ]);
        if ($request->image) {
            if ($request->image->getClientOriginalExtension() == "jfif") {
                flash(app()->getLocale() == 'en'?'This image format is not supported':'صيغه هذه الصوره غير  مدعومه')->error();
                return back();
            }
        }


        $check =  User::where('phone', $request->phone)
        ->where('type' , 1)
        ->first();
if($check ==  null )
{
      
        $user = User::create([
            'phone' => $request->phone,
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'image' => $request->file('image') == null ? 'default.jpg' : UploadImage($request->file('image'), 'client', '/uploads/users'),
            'type' => 1,

        ]);

        Location::create([
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'user_id' => $user->id,
            'main' => 1,
            'name' => 'home',
        ]);
        $user->save();
        flash(app()->getLocale() == 'en'?'created successfully':'تم أنشاء  العميل  بنجاح')->success();
        return redirect('admin/clients');
}else{
    flash(app()->getLocale() == 'en'?'this phone number is used before':'هذا الرقم مستخدم من قبل')->error();
    return redirect('admin/clients');
}
    }

    public function edit($id)
    {
        $client = User::findOrfail($id);
        return view('admin.clients.edit', compact('client'));
    }
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'phone' => 'required|numeric',
            'name' => 'required|max:255',
            'image' => 'nullable|mimes:jpeg,bmp,png,jpg,gif,ico,psd,webp,tif,tiff|max:5000',
            'password' => 'nullable|string|min:6',
            'password_confirmation' => 'nullable|same:password',
            'longitude' => 'nullable',
            'latitude' => 'nullable',
            'active' => 'nullable|in:0,1'


        ]);
        $user = User::findOrFail($id);


        $phoneCheck = User::where('phone',$request->phone)->where('type',1)->whereNotIn('id',[$user->id])->first();
            if($phoneCheck)
            {
                flash(app()->getLocale() == 'en'?'this phone number is used before':'هذا الرقم مستخدم من قبل')->error();
                return redirect('admin/clients');
            }

        $user->update([
            'phone' => $request->phone == null ? $user->phone : $request->phone,
            'name' => $request->name == null ? $user->name : $request->name,
            'active' => $request->active == null ? $user->active : $request->active,
            'password' => $request->password == null ? $user->password : Hash::make($request->password),
            'image' => $request->file('image') == null ? $user->image : UploadImage($request->file('image'), 'user', '/uploads/users'),
        ]);
if($request->longitude && $request->latitude){
    $location = Location::where('user_id',$user->id)->first();
    $location->update([
        'longitude' => $request->longitude,
        'latitude' => $request->latitude,
    ]);
}
        $user->save();

        flash(app()->getLocale()=='en'?'edited successfully':'تم تعديل بيانات  العميل  بنجاح')->success();
        return redirect('admin/clients');

    }
    public function update_location(Request $request, $id)
    {
        //
        $this->validate($request, [
            'latitude' => 'required',
            'longitude' => 'required',

        ]);
        $user = User::findOrfail($id);
      $location = Location::where('user_id',$user->id)->first();
$location->latitude = $request->latitude;
$location->longitude = $request->longitude;
$location->save();

        return redirect()->back()->with('information', 'تم تعديل اعدادات المستخدم');
    }
    public function destroy($id)
    {
        $users = User::find($id);
        $users->delete();
        flash(app()->getLocale() == 'en'?'deleted successfully':'تم حذف  بيانات  المستخدم  بنجاح')->success();

        return back();
    }

}
