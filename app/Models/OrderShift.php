<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderShift extends Model
{
    protected $table = 'order_shifts';
    public $timestamps = true;
    protected $fillable = array('from', 'to' ,'name');
//    protected $dates = ['from', 'to'];


    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }
    //
}
