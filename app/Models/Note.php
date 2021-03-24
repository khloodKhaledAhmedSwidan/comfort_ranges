<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    //

    protected $table = 'notes';
    public $timestamps = true;
    protected $fillable = array('user_id', 'message');

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }


}
