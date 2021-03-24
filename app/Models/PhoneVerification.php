<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhoneVerification extends Model
{
    //
    protected $table = 'phone_verifications';
    public $timestamps = true;
    protected $fillable = array('code', 'phone');
}
