<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rejected extends Model
{
    //
    protected $table = 'rejecteds';
    public $timestamps = true;
    protected $fillable = array('user_id', 'rejected_user_id');


    public function rejectedUser()
	{
		return $this->belongsTo('App\Models\RejectedUser', 'rejected_user_id');
	}
    public function user()
	{
		return $this->belongsTo('App\User', 'user_id');
	}
  
}
