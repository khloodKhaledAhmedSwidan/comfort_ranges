<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model {

	protected $table = 'complaints';
	public $timestamps = true;
	protected $fillable = array('title', 'description', 'user_id', 'order_id');

	public function order()
	{
		return $this->belongsTo('App\User', 'order_id');
	}

	public function user()
	{
		return $this->belongsTo('App\User', 'user_id');
	}

}
