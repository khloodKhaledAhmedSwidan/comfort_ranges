<?php

namespace App\Http\Controllers\AdminController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;

class CalculationController extends Controller
{
    //
    public function index(){
        return view('admin.orders.cal');
    }


    function fetch_data($from = null , $to = null)
    {

      if($from != '' && $to != '')
      {
   
    //    $orders = Order::
    //     //  whereBetween('date', [$from, $to])
    //    get();
       $orders= Order::
       where('date','>=',$from)->where('date','<=',$to)->get();
      
         $total = 0;
         foreach($orders as $order){
$total += $order->total;
         }
    
         $data = $total;
      }
      else
      {
       $data = '';
      }
  return $data;

    }



    function fetch_Price($from = null , $to = null)
    {

      if($from != '' && $to != '')
      {
   

       $orders= Order::
       where('date','>=',$from)->where('date','<=',$to)->get();
      
         $total = 0;
         foreach($orders as $order){
$total += $order->price;
         }
    
         $data = $total;
      }
      else
      {
       $data = '';
      }
  return $data;

    }


    public function materialCost($from = null , $to = null){
      if($from != '' && $to != '')
      {
   

       $orders= Order::
       where('date','>=',$from)->where('date','<=',$to)->get();
      
         $total = 0;
         foreach($orders as $order){
$total += $order->material_cost;
         }
    
         $data = $total;
      }
      else
      {
       $data = '';
      }
  return $data;
    }


    public function orderCount($from = null , $to = null){
      if($from != '' && $to != '')
      {
   

       $orders= Order::
       where('date','>=',$from)->where('date','<=',$to)->count();
      

    
         $data = $orders;
      }
      else
      {
       $data = '';
      }
  return $data;
}
}