<?php

namespace App\Http\Controllers\AdminController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
class RecordController extends Controller
{
    //
    public function allClientOrders(){
        $clients = User::where('active',1)->where('type',1)->get();
        return view('admin.records.all-client-records',compact('clients'));
    }
    public function showClientOrders($id){
        $client = User::find($id);
        $orders = $client->userOrders()->orderBy('id','desc')->get();
        return view('admin.records.client-records',compact('client','orders'));
    }

    public function allEmployeeOrders(){
        $users = User::where('type',2)->get();
        return view('admin.records.all-employee-records',compact('users'));
    }
    public function showEmployeeOrders($id){
        $user = User::find($id);
        $orders = $user->employeeOrders()->where('status',2)->orWhere('status',5)->orderBy('id','desc')->get();
        return view('admin.records.employee-records',compact('user','orders'));
    }

}
