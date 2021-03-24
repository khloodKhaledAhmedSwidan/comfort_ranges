<?php

namespace App\Http\Controllers\Api\Employee;

use App\Models\Note;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiController;
use Validator;

class NoteController extends Controller
{
    //
    public function allNote(Request $request)
    {
        $user = $request->user();
        $notes = $user->notes()->orderBy('id', 'DESC')->get();

        $allNotes = [];
        if ($notes) {
            foreach ($notes as $note) {
                array_push($allNotes, [
                    'id' => intval($note->id),
                    'message' => $note->message,
                    'user_id' => intval($note->user_id),
                    'user' => User::find($note->user_id)->name,
                    'created_at' => $note->created_at->format('Y-m-d'),
                    'updated_at' => $note->created_at->format('Y-m-d'),
                ]);
            }
        }
        return $notes
            ? ApiController::respondWithSuccess($allNotes)
            : ApiController::respondWithServerErrorObject();
    }

    public function addNote(Request $request)
    {
        $user = $request->user();
        if ($user->type == 2) {
            $rules = [
                'message' => 'required|string',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails())
                return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

            $note = Note::create([
                'message' => $request->message,
                'user_id' => $request->user()->id,
            ]);


            return $note
                ? ApiController::respondWithSuccess([
                    'notification' => trans('messages.add_location'),
                    'id' => intval($note->id),
                    'message' => $note->message,
                    'user_id' => intval($note->user_id),
                    'user' => User::find($note->user_id)->name,
                    'created_at' => $note->created_at->format('Y-m-d'),
                    'updated_at' => $note->created_at->format('Y-m-d'),
                ])
                : ApiController::respondWithServerErrorObject();
        } else {
            $errors = ['key' => 'errorUser',
                'value' => trans('messages.wrong_user')
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }

    }

    public function editNote(Request $request, $id)
    {
        $note = Note::find($id);
        if($note){
            $rules = [
                'message' => 'string',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
                return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

            $user = $request->user();
            if ($user->type == 2) {

                $note->update([
                    'message' => $request->message,
                ]);

                return $note
                    ? ApiController::respondWithSuccess([
                        'notification' => trans('messages.edit_location'),
                        'id' => intval($note->id),
                        'message' => $note->message,
                        'user_id' => intval($note->user_id),
                        'user' => User::find($note->user_id)->name,
                        'created_at' => $note->created_at->format('Y-m-d'),
                        'updated_at' => $note->created_at->format('Y-m-d'),
                    ])
                    : ApiController::respondWithServerErrorObject();
            } else {
                $errors = ['key' => 'errorUser',
                    'value' => trans('messages.wrong_user')
                ];
                return ApiController::respondWithErrorClient(array($errors));
            }
        }else{
            $errors = ['key' => 'error',
                'value' => $request->header('X-localization') == 'en' ?'no data':'لا يوجد بيانات',
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }


    }

    public function deleteNote(Request $request)
    {
        $rules = [

            'note_id' => 'exists:notes,id',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));


        $note = Note::find($request->note_id);

        if (auth()->user()->type == 2) {
            $note->delete();
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
