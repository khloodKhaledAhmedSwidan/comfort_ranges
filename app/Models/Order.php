<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $table = 'orders';
    public $timestamps = true;


    /*
     * status : 0 in cart
     *  status : 1 send ,new order
     * status : 2  employee start to work in this order
     * status : 3 order is complated
     * status : 4 order is canceled
     * status : 5 uncompleted order ->complete anothe day
     * status : 6 canceled order by employee
     * tax => تمن الكشفية  if order is regected
     * 0-> not paied
     * 1->paied
     *
     * complete_in_another_day = 0 order completed in same day when order complete in another day this column = 1 
     * and now get another time  belongs to this order from time_orders table
     * complete_in_another_day = 2 => order completed
     */

/*
 * completed_order_accepte_tax  = 0 completed order without tax
 * completed_order_accepte_tax  = 1 completed order including tax
 */


     /*
    *is_paid = 1 by كاش
    *is_paid = 2 by فيزا
    *is_paid = 0 لم يتم الدفع  
    */
    protected $fillable = array('category_id', 'category_name', 'category_name_ar', 'user_id', 'price', 'from', 'to', 'employee_id', 'note', 'status', 'date', 'order_shift_id', 'number_order_in_time', 'employee_note', 'tax', 'work_duration', 'latitude', 'longitude',
     'location_id','material_cost','is_paid','total','vedio','completed_order_accepte_tax','notes_on_what_was_done','complete_in_another_day'
    ,'real_num');

    public function images()
    {
        return $this->hasMany('App\Models\Image');
    }
    public function timeOrders()
    {
        return $this->hasMany('App\Models\TimeOrder');
    }
    public function withOrders()
    {
        return $this->hasMany('App\Models\WithOrder','main_order_id');
    }

    public function withOrderCategories()
    {
        return $this->hasMany('App\Models\WithOrderCategory', 'order_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function orderShift()
    {
        return $this->belongsTo('App\Models\OrderShift', 'order_shift_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function location()
    {
        return $this->belongsTo('App\Models\Location', 'location_id');
    }

    public function employee()
    {
        return $this->belongsTo('App\User', 'employee_id');
    }

    public function notifications()
    {
        return $this->hasMany('App\Models\Notification');
    }

    public function rate()
    {
        return $this->hasOne('App\Models\Rate');
    }

    public function complaint()
    {
        return $this->hasOne('App\Models\Complaint');
    }

    public function bill()
    {
        return $this->hasOne('App\Models\Bill');
    }

}
