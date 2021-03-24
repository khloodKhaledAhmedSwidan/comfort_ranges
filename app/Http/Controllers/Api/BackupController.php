<?php

namespace App\Http\Controllers\Api;

use App\Attendence;
use App\Backup;
use App\ClassNote;
use App\Degree;
use App\Follow;
use App\Student;
use App\StudentClass;
use App\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class BackupController extends Controller
{
    /**
     *  Create Backup
     * @create_backup
     */
    public function create_backup(Request $request)
    {
        $rules = [
            'backup_date' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        // create backup
        $backup = Backup::create([
            'back_up_date'  => $request->backup_date,
            'user_id'       => $request->user()->id
        ]);

        $success = [];
        array_push($success , [
            'id'         => intval($backup->id),
            'backup_date'=> $backup->back_up_date->format('Y-m-d'),
            'created_at' => $backup->created_at->format('Y-m-d'),
        ]);
        return $backup
            ? ApiController::respondWithSuccess($success)
            : ApiController::respondWithServerErrorObject();
    }

    public function get_backups(Request $request)
    {
        $backups = Backup::orderBy('id' , 'desc')
            ->where('user_id' , $request->user()->id)
            ->get();
        $success = [];
        if ($backups->count() > 0)
        {
            foreach ($backups as $backup)
            {
                array_push($success , [
                    'id'         => intval($backup->id),
                    'backup_date'=> $backup->back_up_date->format('Y-m-d'),
                    'created_at' => $backup->created_at->format('Y-m-d'),
                ]);
            }
            return $backups
                ? ApiController::respondWithSuccess($success)
                : ApiController::respondWithServerErrorObject();
        }else{
            $errors = ['key'=>'get_backups',
                'value'=> trans('لا يوجد ')
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }

    }

    /**
     * @store backup data
     * @store_backup_data
     */
    public function store_backup_data(Request $request , $id)
    {
        $rules = [
            'class_id' => 'required',
            'class_name' => 'required',
            'student_id' => 'required',
            'student_name' => 'required',
            'phone' => 'nullable',
            'image' => 'nullable',
            'subject_id' => 'required',
            'subject_name' => 'required',
            'note_id' => 'required',
            'note' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $backup = Backup::find($id);
        if ($backup)
        {
            // 1- create class
            $check = StudentClass::whereClass_id($request->class_id)->first();
            if ($check == null)
            {
                StudentClass::create([
                    'back_up_id' => $id,
                    'class_id'   => $request->class_id,
                    'class_name' => $request->class_name,
                ]);
            }

            // 2- create class students
            $class = StudentClass::where('back_up_id' , $id)
                ->where('class_id' , $request->class_id)
                ->first();
            $student = Student::create([
                'class_id'   => $class->id,
                'student_id' => $request->student_id,
                'user_id'    => $request->user()->id,
                'name'       => $request->student_name,
                'phone'      => $request->phone,
                'image'      => $request->image,
            ]);
            // 3- create subjects
            $subject = Subject::create([
                'class_id'   => $class->id,
                'subject_id' => $request->subject_id,
                'user_id'    => $request->user()->id,
                'name'       => $request->subject_name,
            ]);
            // create notes
            $note = ClassNote::create([
                'class_id'   => $class->id,
                'note_id'     => $request->note_id,
                'student_id'  => $student->id,
                'subject_id' => $subject->id,
                'note'       => $request->note,
            ]);
            $data = [];
            $students = [];
            array_push($students , [
                'class_id'   => intval($student->class_id),
                'student_id' => intval($student->student_id),
                'user_id'    => intval($student->user_id),
                'name'       => $student->name,
                'phone'      => $student->phone,
                'image'      => $student->image,
                'created_at' => $student->created_at->format('Y-m-d'),
            ]);
            $subjects = [];
            array_push($subjects , [
                'class_id'   => intval($subject->class_id),
                'subject_id' => intval($subject->subject_id),
                'user_id'    => intval($subject->user_id),
                'name'       => $subject->name,
                'created_at' => $subject->created_at->format('Y-m-d'),
            ]);
            $notes = [];
            array_push($notes , [
                'note_id'     => intval($note->note_id),
                'student_id'  => intval($note->student_id),
                'subject_id ' => intval($note->subject_id),
                'note '       => $note->note,
                'created_at'  => $note->created_at->format('Y-m-d'),
            ]);
            array_push($data , [
                'back_up_id' => intval($class->back_up_id),
                'class_id'   => intval($class->class_id),
                'class_name' => $class->class_name,
                'created_at' => $class->created_at->format('Y-m-d'),
                'students'   => $students,
                'subjects'   => $subjects,
                'notes'      => $notes,
            ]);
            return $backup
                ? ApiController::respondWithSuccess($data)
                : ApiController::respondWithServerErrorObject();
        }else{
            $errors = ['key'=>'store_backup_data',
                'value'=> 'backup id  not found'
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }
    }
    public function create_class(Request $request , $backup_id)
    {
        $rules = [
            'class_id' => 'required',
            'class_name' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $backup = Backup::find($backup_id);
        if ($backup)
        {
            // 1- create class
            $check = StudentClass::whereClass_id($request->class_id)
                ->where('user_id' , $request->user()->id)
                ->where('back_up_id' , $backup_id)
                ->first();
            $data = [];
            if ($check == null)
            {
                StudentClass::create([
                    'back_up_id' => $backup_id,
                    'class_id'   => $request->class_id,
                    'user_id'    => $request->user()->id,
                    'class_name' => $request->class_name,
                ]);
                $class = StudentClass::where('back_up_id' , $backup_id)
                    ->where('class_id' , $request->class_id)
                    ->first();
                array_push($data , [
                    'back_up_id' => intval($class->back_up_id),
                    'class_id'   => intval($class->class_id),
                    'class_name' => $class->class_name,
                    'created_at' => $class->created_at->format('Y-m-d'),
                ]);
                return $backup
                    ? ApiController::respondWithSuccess($data)
                    : ApiController::respondWithServerErrorObject();
            }else{
                $check->update([
                    'back_up_id' => $backup_id,
                    'class_id'   => $request->class_id,
                    'user_id'    => $request->user()->id,
                    'class_name' => $request->class_name,
                ]);
                $class = StudentClass::where('back_up_id' , $backup_id)
                    ->where('class_id' , $request->class_id)
                    ->first();
                array_push($data , [
                    'back_up_id' => intval($class->back_up_id),
                    'class_id'   => intval($class->class_id),
                    'class_name' => $class->class_name,
                    'created_at' => $class->created_at->format('Y-m-d'),
                ]);
                return $backup
                    ? ApiController::respondWithSuccess($data)
                    : ApiController::respondWithServerErrorObject();
            }
        }else{
            $errors = ['key'=>'create_class',
                'value'=> 'backup id  not found'
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }
    public function create_student(Request $request , $class_id)
    {
        $rules = [
            'student_id' => 'required',
            'backup_id' => 'required',
            'student_name' => 'required',
            'phone' => 'nullable',
            'image' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));
        $class = StudentClass::whereClass_id($class_id)
            ->where('user_id' , $request->user()->id)
            ->where('back_up_id' , $request->backup_id)
            ->first();
        $students = [];
        if ($class)
        {
            $student = Student::create([
                'class_id'   => $class->id,
                'student_id' => $request->student_id,
                'backup_id' => $request->backup_id,
                'user_id'    => $request->user()->id,
                'name'       => $request->student_name,
                'phone'      => $request->phone,
                'image'      => $request->image,
            ]);
            array_push($students , [
                'class_id'   => intval($student->class->class_id),
                'backup_id' => intval($student->backup_id),
                'student_id' => intval($student->student_id),
                'user_id'    => intval($student->user_id),
                'name'       => $student->name,
                'phone'      => $student->phone,
                'image'      => $student->image,
                'created_at' => $student->created_at->format('Y-m-d'),
            ]);
            return $class
                ? ApiController::respondWithSuccess($students)
                : ApiController::respondWithServerErrorObject();
//            $check = Student::whereClass_id($class->id)
//                ->where('student_id' , $request->student_id)
//                ->where('user_id' , $request->user()->id)
//                ->first();
//            $students = [];
//            if ($check == null)
//            {
//                $student = Student::create([
//                    'class_id'   => $class->id,
//                    'student_id' => $request->student_id,
//                    'user_id'    => $request->user()->id,
//                    'name'       => $request->student_name,
//                    'phone'      => $request->phone,
//                    'image'      => $request->image,
//                ]);
//                array_push($students , [
//                    'class_id'   => intval($student->class->class_id),
//                    'student_id' => intval($student->student_id),
//                    'user_id'    => intval($student->user_id),
//                    'name'       => $student->name,
//                    'phone'      => $student->phone,
//                    'image'      => $student->image,
//                    'created_at' => $student->created_at->format('Y-m-d'),
//                ]);
//                return $class
//                    ? ApiController::respondWithSuccess($students)
//                    : ApiController::respondWithServerErrorObject();
//            }else{
//                $check->update([
//                    'class_id'   => $class->id,
//                    'student_id' => $request->student_id,
//                    'user_id'    => $request->user()->id,
//                    'name'       => $request->student_name,
//                    'phone'      => $request->phone,
//                    'image'      => $request->image,
//                ]);
//                array_push($students , [
//                    'class_id'   => intval($check->class->class_id),
//                    'student_id' => intval($check->student_id),
//                    'user_id'    => intval($check->user_id),
//                    'name'       => $check->name,
//                    'phone'      => $check->phone,
//                    'image'      => $check->image,
//                    'created_at' => $check->created_at->format('Y-m-d'),
//                ]);
//                return $class
//                    ? ApiController::respondWithSuccess($students)
//                    : ApiController::respondWithServerErrorObject();
//            }

        }else{
            $errors = ['key'=>'create_student',
                'value'=> 'class id  not found'
            ];
            return ApiController::respondWithErrorClient($errors);
        }

    }
    public function create_subject(Request $request , $class_id)
    {
        $rules = [
            'backup_id' => 'required',
            'subject_id' => 'required',
            'subject_name' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $class = StudentClass::whereClass_id($class_id)
            ->where('back_up_id' , $request->backup_id)
            ->where('user_id' , $request->user()->id)
            ->first();
        if ($class)
        {
            $subjects = [];
            $subject = Subject::create([
                'class_id'   => $class->id,
                'subject_id' => $request->subject_id,
                'backup_id' => $request->backup_id,
                'user_id'    => $request->user()->id,
                'name'       => $request->subject_name,
            ]);
            array_push($subjects , [
                'class_id'   => intval($subject->class->class_id),
                'backup_id' => intval($subject->backup_id),
                'subject_id' => intval($subject->subject_id),
                'user_id'    => intval($subject->user_id),
                'name'       => $subject->name,
                'created_at' => $subject->created_at->format('Y-m-d'),
            ]);
            return $class
                ? ApiController::respondWithSuccess($subjects)
                : ApiController::respondWithServerErrorObject();
//            $check = Subject::whereClass_id($class->id)
//                ->where('subject_id' , $request->subject_id)
//                ->where('user_id' , $request->user()->id)
//                ->first();
//            if ($check == null)
//            {
////                $subject = Subject::create([
////                    'class_id'   => $class->id,
////                    'subject_id' => $request->subject_id,
////                    'user_id'    => $request->user()->id,
////                    'name'       => $request->subject_name,
////                ]);
////                array_push($subjects , [
////                    'class_id'   => intval($subject->class->class_id),
////                    'subject_id' => intval($subject->subject_id),
////                    'user_id'    => intval($subject->user_id),
////                    'name'       => $subject->name,
////                    'created_at' => $subject->created_at->format('Y-m-d'),
////                ]);
////                return $class
////                    ? ApiController::respondWithSuccess($subjects)
////                    : ApiController::respondWithServerErrorObject();
////            }else{
////                $check->update([
////                    'class_id'   => $class->id,
////                    'subject_id' => $request->subject_id,
////                    'user_id'    => $request->user()->id,
////                    'name'       => $request->subject_name,
////                ]);
////                array_push($subjects , [
////                    'class_id'   => intval($check->class->class_id),
////                    'subject_id' => intval($check->subject_id),
////                    'user_id'    => intval($check->user_id),
////                    'name'       => $check->name,
////                    'created_at' => $check->created_at->format('Y-m-d'),
////                ]);
////                return $class
////                    ? ApiController::respondWithSuccess($subjects)
////                    : ApiController::respondWithServerErrorObject();
//
//            }
        }else{
            $errors = ['key'=>'create_subject',
                'value'=> 'class id  not found'
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }
    public function create_student_degrees(Request $request ,  $student_id,$subject_id)
    {
        $rules = [
            'degree1'    => 'required',
            'degree2'    => 'required',
            'degree3'    => 'required',
            'degree4'    => 'required',
            'backup_id'  => 'required|exists:backups,id',

//            'degree_id'  => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $student = Student::whereStudent_id($student_id)
            ->where('backup_id' , $request->backup_id)
            ->first();
        $subject = Subject::whereSubject_id($subject_id)
            ->where('backup_id' , $request->backup_id)
            ->first();
        $degrees = [];
        if ($student != null && $subject != null)
        {
            $degree = Degree::create([
                'backup_id'     => $request->backup_id,
                'student_id'    => $student->id,
                'subject_id'    => $subject->id,
                'user_id'       => $request->user()->id,
                'degree1'       => $request->degree1,
                'degree2'       => $request->degree2,
                'degree3'       => $request->degree3,
                'degree4'       => $request->degree4,
//                    'degree_id'     => $request->degree_id,
            ]);
            array_push($degrees , [
                'backup_id'   => intval($degree->backup_id),
                'student_id'   => intval($degree->student->student_id),
                'subject_id' => intval($degree->subject->subject_id),
                'user_id'    => intval($degree->user_id),
                'degree1'       => doubleval($degree->degree1),
                'degree2'       => doubleval($degree->degree2),
                'degree3'       => doubleval($degree->degree3),
                'degree4'       => doubleval($degree->degree4),
//                    'degree_id'     => intval($degree->degree_id),
                'created_at' => $degree->created_at->format('Y-m-d'),
            ]);
            return $degree
                ? ApiController::respondWithSuccess($degrees)
                : ApiController::respondWithServerErrorObject();
        }else{
            $errors = ['key'=>'create_student_degrees',
                'value'=> 'student  id or subject id not found'
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }
    public function create_student_attendees(Request $request , $student_id,$subject_id )
    {
        $rules = [
            'attendee'   => 'required',
            'absent'     => 'required',
            'permission' => 'required',
            'backup_id'  => 'required|exists:backups,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $student = Student::whereStudent_id($student_id)
            ->where('backup_id' , $request->backup_id)
            ->first();
        $subject = Subject::whereSubject_id($subject_id)
            ->where('backup_id' , $request->backup_id)
            ->first();
        if ($student != null && $subject != null)
        {
            $attendees = [];
            $attendee = Attendence::create([
                'backup_id'     => $request->backup_id,
                'student_id'    => $student->id,
                'subject_id'    => $subject->id,
                'user_id'       => $request->user()->id,
                'attendence'    => $request->attendee,
                'absent'        => $request->absent,
                'leave'         => $request->permission,
            ]);
            array_push($attendees , [
                'backup_id'   => intval($attendee->backup_id),
                'student_id'   => intval($attendee->student->student_id),
                'subject_id' => intval($attendee->subject->subject_id),
                'user_id'    => intval($attendee->user_id),
                'attendee'    => intval($attendee->attendence),
                'absent'       => intval($attendee->absent),
                'permission'   => intval($attendee->leave),
                'created_at'   => $attendee->created_at->format('Y-m-d'),
            ]);
            return $attendee
                ? ApiController::respondWithSuccess($attendees)
                : ApiController::respondWithServerErrorObject();
        }else{
            $errors = ['key'=>'create_student_attendees',
                'value'=> 'student  id or subject id not found'
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }
    public function create_student_following(Request $request , $student_id,$subject_id)
    {
        $rules = [
            'rate'       => 'required',
            'photo'      => 'required',
            'backup_id'  => 'required|exists:backups,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $student = Student::whereStudent_id($student_id)
            ->where('backup_id' , $request->backup_id)
            ->first();
        $subject = Subject::whereSubject_id($subject_id)
            ->where('backup_id' , $request->backup_id)
            ->first();
        $followings = [];
        $follow = Follow::create([
            'backup_id'     => $request->backup_id,
            'student_id'    => $student->id,
            'subject_id'    => $subject->id,
            'user_id'       => $request->user()->id,
            'rate'          => $request->rate,
            'photo'         => $request->photo,
        ]);
        array_push($followings , [
            'backup_id'  => intval($follow->backup_id),
            'student_id' => intval($follow->student->student_id),
            'subject_id' => intval($follow->subject->subject_id),
            'user_id'    => intval($follow->user_id),
            'rate'       => doubleval($follow->rate),
            'photo'      => $follow->photo,
            'created_at' => $follow->created_at->format('Y-m-d'),
        ]);
        return $follow
            ? ApiController::respondWithSuccess($followings)
            : ApiController::respondWithServerErrorObject();
//        if ($student != null && $subject != null)
//        {
//            $followings = [];
//            $check = Follow::whereStudent_id($student->id)
//                ->where('subject_id' , $subject->id)
//                ->where('user_id' , $request->user()->id)
//                ->first();
//            if ($check == null)
//            {
//                $follow = Follow::create([
//                    'student_id'    => $student->id,
//                    'subject_id'    => $subject->id,
//                    'user_id'       => $request->user()->id,
//                    'rate'          => $request->rate,
//                    'photo'         => $request->photo,
//                ]);
//                array_push($followings , [
//                    'class_id'   => intval($follow->student->student_id),
//                    'subject_id' => intval($follow->subject->subject_id),
//                    'user_id'    => intval($follow->user_id),
//                    'rate'       => $follow->rate,
//                    'photo'      => $follow->photo,
//                    'created_at' => $follow->created_at->format('Y-m-d'),
//                ]);
//                return $follow
//                    ? ApiController::respondWithSuccess($followings)
//                    : ApiController::respondWithServerErrorObject();
//            }else{
//                $check->update([
//                    'student_id'    => $student->id,
//                    'subject_id'    => $subject->id,
//                    'user_id'       => $request->user()->id,
//                    'rate'          => doubleval($request->rate),
//                    'photo'         => $request->photo,
//                ]);
//                array_push($followings , [
//                    'class_id'   => intval($check->student->student_id),
//                    'subject_id' => intval($check->subject->subject_id),
//                    'user_id'    => intval($check->user_id),
//                    'rate'       => doubleval($check->rate),
//                    'photo'      => $check->photo,
//                    'created_at' => $check->created_at->format('Y-m-d'),
//                ]);
//                return $check
//                    ? ApiController::respondWithSuccess($followings)
//                    : ApiController::respondWithServerErrorObject();
//            }
//        }else{
//            $errors = ['key'=>'create_student_following',
//                'value'=> 'student  id or subject id not found'
//            ];
//            return ApiController::respondWithErrorClient($errors);
//        }
    }
    public function delete_student_following($student_id , $subject_id , Request $request)
    {
        $student = Student::whereStudent_id($student_id)->first();
        $subject = Subject::whereSubject_id($subject_id)->first();
        if ($student != null && $subject != null)
        {
            $followings = Follow::whereStudent_id($student->id)
                ->where('subject_id' , $subject->id)
                ->where('user_id' , $request->user()->id)
                ->get();
            if ($followings->count() > 0)
            {
                foreach ($followings as $following) {
                    $following->delete();
                }
            }
            $data = ['key' => 'delete_student_following' , 'value' => 'all student following data are cleared'];
            return $student
                    ? ApiController::respondWithSuccess($data)
                    : ApiController::respondWithServerErrorObject();

        }
    }
    public function get_backup_data(Request $request , $id){
        $backup = Backup::find($id);
        if ($backup)
        {
            $classes = StudentClass::where('back_up_id' , $id)
                ->where('user_id' , $request->user()->id)
                ->orderBy('id' , 'desc')
                ->get();

            $notes_arr = [];
            $data = [];
            if ($classes->count() > 0)
            {

                foreach ($classes as $class)
                {
                    $students_arr = [];
                    $subjects_arr = [];
                    $students = Student::whereClass_id($class->id)->get();
                    foreach ($students as $student)
                    {

                        $followings = [];
                        $attendees = [];
                        $degrees = [];
                        $follows = Follow::whereStudent_id($student->id)
                            ->where('backup_id' , $id)
                            ->where('user_id' , $request->user()->id)
                            ->get();
                        if ($follows->count() > 0)
                        {
                            foreach ($follows as $follow)
                            {
                                array_push($followings , [
                                    'student_id'   => intval($follow->student->student_id),
                                    'subject_id' => intval($follow->subject->subject_id),
                                    'user_id'    => intval($follow->user_id),
                                    'rate'       => doubleval($follow->rate),
                                    'photo'      => $follow->photo,
                                    'created_at' => $follow->created_at->format('Y-m-d'),
                                ]);
                            }
                        }
                        $degres = Degree::whereStudent_id($student->id)
                            ->where('backup_id' , $id)
                            ->where('user_id' , $request->user()->id)
                            ->get();
                        if ($degres->count() > 0)
                        {
                            foreach ($degres as $degre)
                            {
                                array_push($degrees , [
                                    'student_id'      => intval($degre->student->student_id),
                                    'subject_id'    => intval($degre->subject->subject_id),
                                    'user_id'       => intval($degre->user_id),
                                    'degree1'       => doubleval($degre->degree1),
                                    'degree2'       => doubleval($degre->degree2),
                                    'degree3'       => doubleval($degre->degree3),
                                    'degree4'       => doubleval($degre->degree4),
//                                    'degree_id'     => intval($degre->degree_id),
                                    'created_at'    => $degre->created_at->format('Y-m-d'),
                                ]);
                            }
                        }
                        $attendes = Attendence::whereStudent_id($student->id)
                            ->where('user_id' , $request->user()->id)
                            ->where('backup_id' , $id)
                            ->get();
                        if ($attendes->count() > 0)
                        {
                            foreach ($attendes as $attend)
                            {
                                array_push($attendees , [
                                    'student_id'   => intval($attend->student->student_id),
                                    'subject_id' => intval($attend->subject->subject_id),
                                    'user_id'    => intval($attend->user_id),
                                    'attendee'    => intval($attend->attendence),
                                    'absent'       => intval($attend->absent),
                                    'permission'   => intval($attend->leave),
                                    'created_at'   => $attend->created_at->format('Y-m-d'),
                                ]);
                            }
                        }
                        array_push($students_arr , [
                            'class_id'   => intval($student->class->class_id),
                            'student_id' => intval($student->student_id),
                            'user_id'    => intval($student->user_id),
                            'name'       => $student->name,
                            'phone'      => $student->phone,
                            'image'      => $student->image,
                            'attendees'  => $attendees,
                            'follows'    => $followings,
                            'degrees'    => $degrees,
                            'created_at' => $student->created_at->format('Y-m-d'),
                        ]);
                    }
                    $subjects = Subject::whereClass_id($class->id)->get();
                    foreach ($subjects as $subject)
                    {
                        array_push($subjects_arr , [
                            'class_id'   => intval($subject->class->class_id),
                            'subject_id' => intval($subject->subject_id),
                            'user_id'    => intval($subject->user_id),
                            'name'       => $subject->name,
                            'created_at' => $subject->created_at->format('Y-m-d'),
                        ]);
                    }
//                    $notes = ClassNote::whereClass_id($class->id)->get();
//                    foreach ($notes as $note)
//                    {
//                        array_push($notes_arr , [
//                            'note_id'     => intval($note->note_id),
//                            'student_id'  => intval($note->student_id),
//                            'subject_id ' => intval($note->subject_id),
//                            'note '       => $note->note,
//                            'created_at'  => $note->created_at->format('Y-m-d'),
//                        ]);
//                    }
                    array_push($data , [
                        'back_up_id' => intval($class->back_up_id),
                        'class_id'   => intval($class->class_id),
                        'class_name' => $class->class_name,
                        'created_at' => $class->created_at->format('Y-m-d'),
                        'students'   => $students->count() > 0 ? $students_arr : null,
                        'subjects'   => $subjects->count() > 0 ? $subjects_arr : null,
//                        'notes'      => $notes_arr,
                    ]);
                }

                return $backup
                    ? ApiController::respondWithSuccess($data)
                    : ApiController::respondWithServerErrorObject();
            }else{
                $errors = ['key'=>'get_backup_data',
                    'value'=> 'No Classes Found'
                ];
                return ApiController::respondWithErrorClient(array($errors));
            }
        }else{
            $errors = ['key'=>'get_backup_data',
                'value'=> 'backup id  not found'
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }
    }
}
