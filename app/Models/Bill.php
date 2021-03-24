<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model {

	protected $table = 'bills';
	public $timestamps = true;
	protected $fillable = array('title', 'description', 'price', 'order_id', 'is_pay', 'status');

	public function notifications()
	{
		return $this->hasMany('App\Models\Notification');
	}

	public function order()
	{
		return $this->belongsTo('App\Models\Order', 'order_id');
	}

}
