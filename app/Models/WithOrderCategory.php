<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithOrderCategory extends Model
{
    //
    protected $table = 'with_order_categories';
    public $timestamps = true;
    protected $fillable = array('order_id', 'category_id');

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }
}
