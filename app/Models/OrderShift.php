<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderShift extends Model
{
    protected $table = 'order_shifts';
    public $timestamps = true;
    protected $fillable = array('from', 'to' ,'name');
//    protected $dates = ['from', 'to'];

public function rejectedUsers()
{
    return $this->hasMany('App\Models\RejectedUser', 'order_shift_id');
}
    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }
    public function CategoryOrderShifts()
    {
        return $this->hasMany('App\Models\CategoryOrderShift','order_shift_id');
    }
    //
}
