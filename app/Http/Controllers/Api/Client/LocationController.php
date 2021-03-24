<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Api\ApiController;
use App\Models\Location;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class LocationController extends Controller
{
    //

    public function allLocation(Request $request)
    {
        $user = $request->user();
        $locations = $user->locations()->get();

        $allLocation = [];
        if ($locations) {
            foreach ($locations as $location) {
                array_push($allLocation, [
                    'id' => intval($location->id),
                    'name' => $location->name,
                    'longitude' => doubleval($location->longitude),
                    'latitude' => doubleval($location->latitude),
                ]);
            }
        }
        return $locations
            ? ApiController::respondWithSuccess($allLocation)
            : ApiController::respondWithServerErrorObject();
    }

    public function addLocation(Request $request)
    {
        $user = $request->user();
        if ($user->type == 1) {
            $rules = [
                'name' => 'required|min:4|max:255',
                'longitude' => 'required|numeric',
                'latitude' => 'required|numeric',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails())
                return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

            $location = Location::create([
                'name' => $request->name,
                'longitude' => $request->longitude,
                'latitude' => $request->latitude,
                'user_id' => $request->user()->id,
            ]);


            return $location
                ? ApiController::respondWithSuccess([
                    'notification' => trans('messages.add_location'),
                    'id' => intval($location->id),
                    'name' => $location->name,
                    'longitude' => doubleval($location->longitude),
                    'latitude' => doubleval($location->latitude),
                ])
                : ApiController::respondWithServerErrorObject();
        } else {
            $errors = ['key' => 'errorUser',
                'value' => trans('messages.wrong_user')
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }

    }

    public function editLocation(Request $request, $id)
    {
        $location = Location::find($id);
        $rules = [
            'name' => 'nullable|min:4|max:255',
            'longitude' => 'nullable|numeric',
            'latitude' => 'nullable|numeric',
            'user_id' => 'exists:users,id',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $user = $request->user();
        if ($user->type == 1) {

            $location->update($request->all());

            return $location
                ? ApiController::respondWithSuccess([
                    'notification' => trans('messages.edit_location'),
                    'id' => intval($location->id),
                    'name' => $location->name,
                    'longitude' => doubleval($location->longitude),
                    'latitude' => doubleval($location->latitude),
                ])
                : ApiController::respondWithServerErrorObject();
        } else {
            $errors = ['key' => 'errorUser',
                'value' => trans('messages.wrong_user')
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }

    }

    public function deleteLocation(Request $request)
    {
        $rules = [

            'location_id' => 'exists:locations,id',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));


        $location = Location::find($request->location_id);

        if (auth()->user()->type == 1) {
            $location->delete();
            $data = [
                'key' => 'success',
                'value' => trans('messages.delete_location'),
            ];
            return ApiController::respondWithSuccess($data);

        } else {
            $errors = ['key' => 'error',
                'value' => trans('messages.error_delete_location'),
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }

    }
}
