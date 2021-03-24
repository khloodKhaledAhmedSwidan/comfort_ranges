<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model {

	protected $table = 'branches';
//	public $timestamps = true;
	protected $fillable = array('name', 'latitude', 'longitude', 'name_ar');

	public function users()
	{
		return $this->hasMany('App\User');
	}	public function categories()
	{
		return $this->hasMany('App\Models\Category');
	}

}
