<?php

namespace App\Http\Controllers\AdminController;

use App\City;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = DB::table('users')->where('type',2)->count();
        $clients = DB::table('users')->where('type',1)->count();
        $orders = DB::table('orders')->count();
        $order = Order::all();
        $count = 0;
        foreach ($order as $test){
     if(   Carbon::parse($test->date) == Carbon::today() ) {
         $count +=1;
     }
        }
        $todayOrders = DB::table('orders')->where('date',Carbon::now())->count();
        $admins = DB::table('admins')->count();
        $categories = DB::table('categories')->count();
        $branches = DB::table('branches')->count();
        $complaints = DB::table('complaints')->count();

        return view('admin.home' , compact('users','admins','clients','orders','categories','branches','complaints','count'));
    }
    public function get_regions($id)
    {
        $regions = City::where('parent_id',$id)->select('id','name')->get();
        $data['regions']= $regions;
        return json_encode($data);
    }
}
