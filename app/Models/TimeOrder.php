<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeOrder extends Model
{
    //
    protected $fillable = array('start','end','date','order_id','order_shift_id');

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id');
    }
    public function orderShift()
    {
        return $this->belongsTo('App\Models\OrderShift', 'order_shift_id');
    }
}
