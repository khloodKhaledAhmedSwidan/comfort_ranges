<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryOrderShift extends Model
{
    //
    protected $table = 'category_order_shifts';
    public $timestamps = true;
    protected $fillable = array('category_id', 'order_shift_id');
    public function category()
{
    return $this->belongsTo('App\Models\Category','category_id');
}

public function orderShift()
{
    return $this->belongsTo('App\Models\OrderShift','order_shift_id');
}
}
