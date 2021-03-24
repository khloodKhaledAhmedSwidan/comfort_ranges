<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model {

	protected $table = 'rates';
	public $timestamps = true;
	protected $fillable = array('rate', 'user_id', 'order_id','comment');


	/*
	 *  rate : has values (1,2,3)
	 *         1 ->bad
	 *         2 -> good
	 *         3 -> excellent
	 */



	public function user()
	{
		return $this->belongsTo('App\User', 'user_id');
	}

	public function order()
	{
		return $this->belongsTo('App\Models\Order', 'order_id');
	}
    public function sentences()
    {
        return $this->belongsToMany('App\Models\Sentence');
    }

}
