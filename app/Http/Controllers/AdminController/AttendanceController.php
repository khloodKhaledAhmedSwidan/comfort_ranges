<?php

namespace App\Http\Controllers\AdminController;

use App\Models\Shift;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AttendanceController extends Controller
{
    //
    public function index(Request $request)
    {
        $users = User::where('type', 2)->get();
        return view('admin.attendance.index', compact('users'));
    }
    public function showUserAttendance($id){
        $user = User::find($id);
        $shifts = $user->shifts()->orderBy('id','desc')->get();
        return view('admin.attendance.show_user_attendance', compact('shifts','user'));
    }
}
