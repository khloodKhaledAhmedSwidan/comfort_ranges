<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model {


    // main default = 0 ""all location stored by client "" if main = 1 main location stored when register

	protected $table = 'locations';
	public $timestamps = true;
	protected $fillable = array('name', 'longitude', 'latitude', 'user_id','main');

	public function user()
	{
		return $this->belongsTo('App\User', 'user_id');
	}

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

}
