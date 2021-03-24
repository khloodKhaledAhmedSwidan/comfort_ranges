<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model {

	protected $table = 'shifts';
	public $timestamps = true;
	protected $fillable = array('from', 'to', 'user_id', 'type', 'date', 'title','status');


	/*
	 * status : 0,1
	 *  status:0 -> another day
	 * status : 1 -> same day
	 * type:0
	 * type :0  check-in && check-out

	 */


	public function user()
	{
		return $this->belongsTo('App\User', 'user_id');
	}

}
