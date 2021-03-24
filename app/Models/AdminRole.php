<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
    //
    protected $fillable = [
        'role_id', 'admin_id'
    ];
}
