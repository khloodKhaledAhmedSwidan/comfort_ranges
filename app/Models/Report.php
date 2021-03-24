<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model {

	protected $table = 'reports';
	public $timestamps = true;
	protected $fillable = array('title', 'description', 'user_id');

	public function user()
	{
		return $this->belongsTo('App\User', 'user_id');
	}

}
