<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RejectedUser extends Model
{
    //
    protected $table = 'rejected_users';
    public $timestamps = true;
    protected $fillable = array( 'rejected_date_id','order_shift_id');


	public function rejecteds()
	{
		return $this->hasMany('App\Models\Rejected', 'rejected_user_id');
	}
    public function rejectedDate()
	{
		return $this->belongsTo('App\Models\RejectedDate', 'rejected_date_id');
	}
    public function orderShift()
	{
		return $this->belongsTo('App\Models\OrderShift', 'order_shift_id');
	}
}
