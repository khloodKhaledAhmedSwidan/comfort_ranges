<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model {

	protected $table = 'notifications';
	public $timestamps = true;
	protected $fillable = array('title', 'description', 'user_id', 'order_id', 'is_read', 'bill_id','description_ar','title_ar');

	public function order()
	{
		return $this->belongsTo('App\Models\Order', 'order_id');
	}

	public function user()
	{
		return $this->belongsTo('App\User', 'user_id');
	}

	public function bill()
	{
		return $this->belongsTo('App\Models\Bill', 'bill_id');
	}

}
