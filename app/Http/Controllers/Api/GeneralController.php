<?php

namespace App\Http\Controllers\Api;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Contact;
use App\Models\RejectedDate;
use App\Models\Sentence;
use App\Models\Setting;
use App\Models\Slider;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class GeneralController extends Controller
{
    //
    
    
    public function qrcode(Request $request){

    $arr = [];

        array_push($arr, [
      
            'qrcodeEmployee' => 'https://api.qrserver.com/v1/create-qr-code/?data=https://play.google.com/store/apps/details?id=com.tqnee.easymenu.easy_menu',
            'qrcodeClient' => 'https://api.qrserver.com/v1/create-qr-code/?data=https://play.google.com/store/apps/details?id=com.tqnee.easymenu.easy_menu' ,

        ]);

    return  ApiController::respondWithSuccess($arr);
}

    public function allCategories(Request $request)
    {
        $categories = Category::all();
        $arr = [];
        foreach ($categories as $category) {
            array_push($arr, [
                'id' => intval($category->id),
                'name' => $request->header('X-localization') == 'en' ? $category->name : $category->name_ar,
                'image' => asset('/uploads/categories/' . $category->image),

            ]);
        }
        return $categories
            ? ApiController::respondWithSuccess($arr)
            : ApiController::respondWithServerErrorArray();
    }

    public function servicesImages(Request $request)
    {
        $categories = Category::get();
        $arr = [];
        foreach ($categories as $category) {
            array_push($arr, [
                'id' => intval($category->id),
                'image' => $category->image != null ? asset('/uploads/categories/' . $category->image) : null,
            ]);
        }
        return $categories
            ? ApiController::respondWithSuccess($arr)
            : ApiController::respondWithServerErrorArray();
    }

    public function allBranch(Request $request)
    {
        $branches = Branch::all();
        $arr = [];
        foreach ($branches as $branch) {
            array_push($arr, [
                'id' => intval($branch->id),
                'name' => $request->header('X-localization') == 'en' ? $branch->name : $branch->name_ar,
                'latitude' => doubleval($branch->latitude),
                'longitude' => doubleval($branch->longitude),
            ]);
        }
        return $branches
            ? ApiController::respondWithSuccess($arr)
            : ApiController::respondWithServerErrorArray();
    }

    public function branchCategories(Request $request)
    {
//dd($request->all());
        $rules = [
            'branch_id' => 'required|exists:branches,id'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $branch = Branch::find($request->branch_id);
        $categories = $branch->categories()->get();
        $arr = [];
        foreach ($categories as $category) {
            array_push($arr, [
                'id' => intval($category->id),
                'name' => $request->header('X-localization') == 'en' ? $category->name : $category->name_ar,
                'image' => $category->image != null ? asset('/uploads/categories/' . $category->image) : null,
                'branch_id' => $category->branch_id,
                'branch' => $category->branch_id != null ? ($request->header('X-localization') == 'en' ? Branch::find($category->branch_id)->name : Branch::find($category->branch_id)->name_ar) : null
            ]);
        }
        return $categories
            ? ApiController::respondWithSuccess($arr)
            : ApiController::respondWithServerErrorArray();

    }

    public function settings(Request $request)
    {
        $setting = Setting::find(1);
        $phone = null;
        $whatsapp = null;
     if (substr($setting->phone, 0, 2) === '05') {
            $result = substr($setting->phone, 1);
            $phone =  '0'.$result;
        }
        if (substr($setting->whatsapp, 0, 2) === '05') {
            $result = substr($setting->whatsapp, 1);
            $whatsapp = '966' . $result;
        }


        if ($setting) {
            $data = [];
            array_push($data, [
                'id' => intval($setting->id),
                'phone' => $phone != null ? $phone : $setting->phone,
                'whatsapp' => $whatsapp != null ? $whatsapp : $setting->whatsapp,
                'term' => $request->header('X-localization') == 'en' ? $setting->term : $setting->terms_ar,
                'condition' => $request->header('X-localization') == 'en' ? $setting->condition : $setting->condition,
                'about_us' => $request->header('X-localization') == 'en' ? $setting->about_us : $setting->about_us_ar,
                'tax' => number_format((float)$setting->tax, 2, '.', ''),
                'tax_for_completed_order' => intval($setting->tax_for_completed_order),
                'search_range' => intval($setting->search_range),
                'shift_range' => intval($setting->shift_range),
                'country_name' => $request->header('X-localization') == 'en' ? strval($setting->country_name_en) : strval($setting->country_name),
                'city_name' => $request->header('X-localization') == 'en' ? strval($setting->city_name_en) : strval($setting->city_name),
                'route_name' => $request->header('X-localization') == 'en' ? strval($setting->route_name_en) : strval($setting->route_name),
                'company_name' => $request->header('X-localization') == 'en' ? strval($setting->company_name_en) : strval($setting->company_name),
                'longitude' => $setting->longitude,
                'latitude' => $setting->latitude,
                'tax_for_completed_order' => intval($setting->tax_for_completed_order),
                'accept_tax' => $request->header('X-localization') == 'en' ? $setting->accept_tax_en : $setting->accept_tax,

                'image' => $setting->image == null ? null : asset('img/' . $setting->image),
            ]);


            return ApiController::respondWithSuccess($data);

        } else {
            $errors = ['key' => 'error',
                'value' => $request->header('X-localization') == 'en' ? 'there are no Data ' : 'لا توجد بيانات '
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }
    }

    public function rateSentences(Request $request)
    {
        $sentences = Sentence::get();
        if ($sentences) {
            $data = [];
            foreach ($sentences as $sentence) {
                array_push($data, [
                    'id' => intval($sentence->id),
                    'sentence' => $request->header('X-localization') == 'en' ? $sentence->sentence : $sentence->sentence_ar,
                ]);
            }


            return ApiController::respondWithSuccess($data);

        } else {
            $errors = ['key' => 'error',
                'value' => $request->header('X-localization') == 'en' ? 'there are no Data ' : 'لا توجد بيانات '
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }
    }

    public function slider(Request $request)
    {


        $rules = [
            'location_id' => 'exists:locations,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $user = $request->user();

        $mainLocation = $user->locations()->where('main', 1)->latest()->first();
        $distance = Setting::find(1)->search_range;
        $lat = $mainLocation->latitude;
        $lon = $mainLocation->longitude;

        if ($request->location_id) {
            $location = \App\Models\Location::where('id', $request->location_id)->first();
            $distance = Setting::find(1)->search_range;
            $lat = $location->latitude;
            $lon = $location->longitude;
        }

        $branches = Branch::selectRaw('*, ( 6367 * acos( cos( radians( ? ) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians( ? ) ) + sin( radians( ? ) ) * sin( radians( latitude) ) ) ) AS distance', [$lat, $lon, $lat])
            ->having('distance', '<=', $distance)
            ->orderBy('distance')
            ->get();
//dd($branches);
        if ($branches) {
            $allCategory = [];
            foreach ($branches as $branch) {
                $categories = $branch->categories()->get();

                if ($categories) {
                    foreach ($categories as $category) {

                        $slider = Slider::where('category_id', $category->id)->first();
                        if ($slider) {
                            array_push($allCategory, [
                                'id' => intval($slider->id),
                                'image' => asset('uploads/sliders/' . $slider->image),
                                'link' => strval($slider->link),
                                'category_id' => intval($slider->category_id),
                            ]);
                        }

                    }

                    $sliders = Slider::where('link','!=',null)->get();
                    if($sliders){
                        foreach ($sliders as $slider){
                            array_push($allCategory, [
                                'id' => intval($slider->id),
                                'image' => asset('uploads/sliders/' . $slider->image),
                                'link' => strval($slider->link),
                                'category_id' => intval($slider->category_id),
                            ]);
                        }

                    }

                    return ApiController::respondWithSuccess($allCategory);

                } else {
                    $errors = ['key' => 'errorUser',
                        'value' => $request->header('X-localization') == 'en' ? 'There are no categories  near your current location' : 'لا يوجد خدمات بالقرب من موقعك الحالي'
                    ];
                    return ApiController::respondWithErrorClient(array($errors));
                }
            }


        } else {
            $errors = ['key' => 'errorUser',
                'value' => $request->header('') == 'en' ? 'There are no branches near your current location' : 'لا يوجد فروع قريبه من موقعك الحالي'
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }


    }


    public function contactUs(Request $request)
    {
        $rules = [
            'message' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));


        $user = $request->user();

        $contact = Contact::create([
            'message' => $request->message,
            'user_id' => $user->id,
        ]);

        $data = [];
        array_push($data, [
            'id' => intval($contact->id),
            'user_id' => intval($contact->user_id),
            'user' => User::find($contact->user_id)->name,
            'message' => $contact->message,
        ]);
        return ApiController::respondWithSuccess($data);

    }

    public function rejectedDates(Request $request)
    {

        $rejectedDates = RejectedDate::get();
        if ($rejectedDates) {
            $arr = [];
            foreach ($rejectedDates as $rejectedDate) {
                array_push($arr, [
                    'id' => intval($rejectedDate->id),
                    'reject_date' => $rejectedDate->reject_date,
                    'reason' => $rejectedDate->reason,
                ]);
            }

            return ApiController::respondWithSuccess($arr);
        } else {
            $errors = ['key' => 'error',
                'value' => $request->header('X-localization') == 'en' ? 'there are no Data ' : 'لا توجد بيانات '
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }


    }


}
