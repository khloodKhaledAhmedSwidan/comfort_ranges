<?php

namespace App\Http\Controllers\AdminController;

use App\Models\Complaint;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GeneralController extends Controller
{
    //


    public function allComplaint(){
        $complaints = Complaint::orderBy('id', 'DESC')->get();
        return view('admin.complaints.index',compact('complaints'));
    }




}
