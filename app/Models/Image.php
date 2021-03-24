<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model {

	protected $table = 'images';
	public $timestamps = true;
	protected $fillable = array('image', 'order_id');

	public function order()
	{
		return $this->belongsTo('App\Models\Order', 'order_id');
	}

}
