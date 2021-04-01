<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RejectedDate extends Model
{
    //
    protected $table = 'rejected_dates';
    public $timestamps = true;
    protected $fillable = array('reject_date', 'reason','reason_en');

    public function rejectedUsers()
	{
		return $this->hasMany('App\Models\RejectedUser', 'rejected_date_id');
	}

}
