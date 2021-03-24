<?php

namespace App\Http\Controllers\AdminController;

use App\Admin;
use App\AdminEmail;
use App\Models\Setting;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Redirect;
use Image;
use Auth;
use App\Permission;

class SettingController extends Controller
{
    //
    public function index()
    {
        $settings = settings();
        return view('admin.settings.index', compact('settings'));
    }

 public function is_active(Request $request, $id)
    {

        if ($request->ajax()) {
            $setting = Setting::findOrfail($id);
            if ($setting->tax_for_completed_order_active == 1) {
                $setting->tax_for_completed_order_active = 0;
                $setting->save();

            } else {
                $setting->tax_for_completed_order_active = 1;
                $setting->save();
            }

            return 'true';
        }


    }

    public function companyInformation(Request $request){
        $this->validate($request, [
            'latitude' => 'required',
            'longitude' => 'required',
            'company_name' => 'required|string|max:225',
            'route_name' => 'required|string|max:225',
            'city_name' => 'required|string|max:225',
            'country_name' => 'required|string|max:225',
            'company_name_en' => 'required|string|max:225',
            'route_name_en' => 'required|string|max:225',
            'city_name_en' => 'required|string|max:225',
            'country_name_en' => 'required|string|max:225',


        ]);
        $setting = \App\Models\Setting::where('id', 1)->first();


        $setting->update([
            'latitude'=>$request->latitude,
            'longitude'=>$request->longitude,
            'company_name'=>$request->company_name,
            'route_name'=>$request->route_name,
            'city_name'=>$request->city_name,
            'country_name'=>$request->country_name,
            'company_name_en'=>$request->company_name_en,
            'route_name_en'=>$request->route_name_en,
            'city_name_en'=>$request->city_name_en,
            'country_name_en'=>$request->country_name_en,
        ]);
        if($request->country_name_en){
            $setting->country_name_en = $request->country_name_en;
            $setting->save();
        }
        return Redirect::back()->with('success', app()->getLocale() == 'en' ? 'saved successfully' : 'تم حفظ البيانات بنجاح');

    }
    public function store(Request $request)
    {
//        dd($request->all());
        $this->validate($request, [
            'phone' => 'required|numeric',
            'whatsapp' => 'required|numeric',
            'tax' => 'required|numeric',
            'tax_for_completed_order' => 'required|numeric',
            'search_range' => 'required|numeric',
            'shift_range' => 'required|numeric',
            'count_of_order_in_period' => 'required|numeric',
            'about_us_ar' => 'nullable|string',
            'about_us' => 'nullable|string',
            'accept_tax' =>'required|string|max:225',
            'cancel_order' => 'required|numeric',

        ]);
        $setting = \App\Models\Setting::where('id', 1)->first();
if($request->phone){

    if (substr($request->phone, 0, 2) === '05') {
        $setting->phone = $request->phone;
        $setting->save();
    }else{
       flash(app()->getLocale() =='en'?'The phone number must begin with 05':'يجب ان يبدأ رقم الهاتف ب05')->error();
        return Redirect::back();
    }
}if($request->whatsapp){

    if (substr($request->whatsapp, 0, 2) === '05') {
        $setting->whatsapp = $request->whatsapp;
        $setting->save();

    }else{
       flash(app()->getLocale() =='en'?'The whatsapp number must begin with 05':'يجب ان يبدأ رقم الواتساب ب05')->error();
        return Redirect::back();
    }
}



       $setting->update($request->all());
       if($request->cancel_order > 0 ){
        $setting->cancel_order = $request->cancel_order;
        $setting->save();
       }else{
        $setting->cancel_order = 1;
        $setting->save();
       }
        return Redirect::back()->with('success', app()->getLocale() == 'en' ? 'saved successfully' : 'تم حفظ البيانات بنجاح');

    }

    public function storeTermsAndConditions(Request $request)
    {
        $this->validate($request, [

            'terms_ar' => 'required|string',
            'term' => 'required|string',
            'condition_ar' => 'required|string',
            'condition' => 'required|string',


        ]);

        \App\Models\Setting::where('id', 1)->first()->update($request->all());
        return Redirect::back()->with('success', app()->getLocale() == 'en' ? 'saved successfully' : 'تم حفظ البيانات بنجاح');

    }

    public function storeAboutUs(Request $request)
    {
//        dd($request->all());
        $this->validate($request, [

            'about_us_ar' => 'required|string',
            'about_us' => 'required|string',


        ]);

        \App\Models\Setting::where('id', 1)->first()->update($request->all());
        return Redirect::back()->with('success', app()->getLocale() == 'en' ? 'saved successfully' : 'تم حفظ البيانات بنجاح');

    }

    public function parteners()
    {
        $users = User::where('subscription', '1')->get();
        return view('admin.parteners.index', compact('users'));
    }

    public function edit()
    {
        $email = AdminEmail::find(1);
        return view('admin.settings.admin_email', compact('email'));
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email'
        ]);
        $email = AdminEmail::find($id);
        $email->update($request->all());

        flash(app()->getLocale() == 'en' ? 'updated successfully' : 'تم  تعديل  البريد  الالكتروني بنجاح')->success();
        return back();
    }


    public function changeLogo()
    {

        $image = Setting::find(1)->image;
        return view('admin/admins/change-logo', compact('image'));
    }

    public function LogoImage(Request $request)
    {
        $setting = Setting::find(1);
        $this->validate($request, [

            "image" => 'required|mimes:jpeg,bmp,png,jpg,gif,ico,psd,webp,tif,tiff|max:5000',
        ]);

        if ($request->image) {
            if ($request->image->getClientOriginalExtension() == "jfif") {
                flash(app()->getLocale() == 'en' ? 'This image format is not supported' : 'صيغه هذه الصوره غير  مدعومه')->error();
                return back();
            }
        }
        $setting->update([
            'image' => $request->image == null ? $setting->image : UploadImageEdit($request->image, 'logo', 'img', $setting->image)
        ]);
        flash(app()->getLocale() == 'en' ? 'updated successfully' : 'تم تعديل الصورة بنجاح');
        return back();
    }


}
