<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Api\ApiController;
use App\Models\Branch;
use App\Models\Order;
use App\Models\OrderShift;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use phpDocumentor\Reflection\Location;
use Validator;

class GeneralController extends Controller
{
    //
    public function allCategory(Request $request)
    {


        $rules = [
            'location_id' => 'exists:locations,id',
            'language' =>'nullable|in:en,ar',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $user = $request->user();

if($request->language){
$user->language = $request->language;
$user->save();
}
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
        if ($branches->count() > 0) {
            $allCategory = [];
            foreach ($branches as $branch) {


                $categories = $branch->categories()->get();
                if ($categories) {
                    foreach ($categories as $category) {
                        array_push($allCategory, [
                            'id' => intval($category->id),
                            'name' => $request->header('X-localization') == 'en' ? $category->name : $category->name_ar,
                            'image' => $category->image != null ? asset('/uploads/categories/' . $category->image) : null,
                            'branch_id' => intval($category->branch_id),
                            'arranging' => intval($category->arranging),
                              'branch' => $category->branch_id != null ? ($request->header('X-localization') == 'en' ? Branch::find($category->branch_id)->name : Branch::find($category->branch_id)->name) : null,
                            'active' => intVal($category->active),
                        ]);
                    }


                } else {
                    $errors = ['key' => 'errorUser',
                        'value' => $request->header('X-localization') == 'en' ? 'There are no categories  near your current location' : 'لا يوجد خدمات بالقرب من موقعك الحالي'
                    ];
                    return ApiController::respondWithErrorClient(array($errors));
                }
            }
            return ApiController::respondWithSuccess([
             'all_categories'  =>  $allCategory,
                'latitude' => $lat,
                'longitude' =>$lon,
                'id' => $request->location_id != null ? $location->id:$mainLocation->id,
                'nameOfLocation' => $request->location_id != null ? $location->name:$mainLocation->name,
            ]);

        } else {
            $errors = ['key' => 'errorUser',
                'value' => $request->header('') == 'en' ? 'There are no branches near your current location' : 'لا يوجد فروع قريبه من موقعك الحالي'
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }


    }

// all category depend on location except order category
    public function anotherServicesChooseListOfThem(Request $request)
    {


        $rules = [
            'location_id' => 'exists:locations,id',
//            'order_id' => 'required|exists:orders,id',
            'category_id' => 'required|exists:categories,id',
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
//        $order = Order::find($request->order_id);

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
                        if($request->category_id  != $category->id){
                            array_push($allCategory, [
                                'id' => intval($category->id),
                                'name' => $request->header('X-localization') == 'en' ? $category->name : $category->name_ar,
                                'image' => $category->image != null ? asset('/uploads/categories/' . $category->image) : null,
                                'branch_id' => intval($category->branch_id),
                                'arranging' => intval($category->arranging),
                                'branch' => $category->branch_id != null ? ($request->header('X-localization') == 'en' ? Branch::find($category->branch_id)->name : Branch::find($category->branch_id)->name) : null
                            ]);
                        }

                    }


                } else {
                    $errors = ['key' => 'errorUser',
                        'value' => $request->header('X-localization') == 'en' ? 'There are no categories  near your current location' : 'لا يوجد خدمات بالقرب من موقعك الحالي'
                    ];
                    return ApiController::respondWithErrorClient(array($errors));
                }
            }
            return ApiController::respondWithSuccess([
                'all_categories'  =>  $allCategory,
                'latitude' => $lat,
                'longitude' =>$lon,
                'id' => $request->location_id != null ? $location->id:$mainLocation->id,
                'nameOfLocation' => $request->location_id != null ? $location->name:$mainLocation->name,
            ]);

        } else {
            $errors = ['key' => 'errorUser',
                'value' => $request->header('') == 'en' ? 'There are no branches near your current location' : 'لا يوجد فروع قريبه من موقعك الحالي'
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }


    }



    public function allCategoryDependOnPreviousOrder(Request $request)
    {

        $rules = [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));


        $distance = Setting::find(1)->search_range;
        $lat = $request->latitude;
        $lon = $request->longitude;

        $branches = Branch::selectRaw('*, ( 6367 * acos( cos( radians( ? ) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians( ? ) ) + sin( radians( ? ) ) * sin( radians( latitude) ) ) ) AS distance', [$lat, $lon, $lat])
            ->having('distance', '<=', $distance)
            ->orderBy('distance')
            ->get();
        if ($branches) {
            $allCategory = [];
            foreach ($branches as $branch) {

                $user = $request->user();
                $orderCategoriesIds = $user->userOrders()->where([
                    ['status', 1]
                ])->pluck('category_id')->all();
                $categories = $branch->categories()->where('active',1)->get()->except($orderCategoriesIds);

                if ($categories) {
                    foreach ($categories as $category) {
                        array_push($allCategory, [
                            'id' => intval($category->id),
                            'name' => $request->header('X-localization') == 'en' ? $category->name : $category->name_ar,
                            'image' => $category->image != null ? asset('/uploads/categories/' . $category->image) : null,
                            'branch_id' => intval($category->branch_id),
                            'arranging' => intval($category->arranging),
                            'branch' => $category->branch_id != null ? ($request->header('X-localization') == 'en' ? Branch::find($category->branch_id)->name : Branch::find($category->branch_id)->name) : null
                        ]);
                    }


                } else {
                    $errors = ['key' => 'errorUser',
                        'value' => $request->header('X-localization') == 'en' ? 'There are no categories  near your current location' : 'لا يوجد خدمات بالقرب من موقعك الحالي'
                    ];
                    return ApiController::respondWithErrorClient(array($errors));
                }
            }
            return ApiController::respondWithSuccess($allCategory);

        } else {
            $errors = ['key' => 'errorUser',
                'value' => $request->header('') == 'en' ? 'There are no branches near your current location' : 'لا يوجد فروع قريبه من موقعك الحالي'
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }


    }





    public function availablePeriods(Request $request)
    {
        $availablePeriods = OrderShift::get();
        if ($availablePeriods) {
            $data = [];
            foreach ($availablePeriods as $availablePeriod) {
                array_push($data, [
                    'id' => intval($availablePeriod->id),
                    'from' => Carbon::parse($availablePeriod->from)->format('H:i'),
                    'to' => Carbon::parse($availablePeriod->to)->format('H:i'),
                ]);


            }

            return ApiController::respondWithSuccess($data);


        } else {
            $errors = ['key' => 'errorUser',
                'value' => $request->header('X-localization') == 'en' ? 'there are no periods ' : 'لا توجد فترات '
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }
    }
}
