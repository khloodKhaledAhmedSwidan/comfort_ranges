<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithOrder extends Model
{
    //
    protected $table = 'with_orders';
    public $timestamps = true;
    protected $fillable = array('main_order_id', 'order_id');

    public function mainOrder()
    {
        return $this->belongsTo('App\Models\Order', 'main_order_id');
    }

    public function Order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id');
    }


}
