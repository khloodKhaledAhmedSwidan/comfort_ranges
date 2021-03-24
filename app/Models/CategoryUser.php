<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryUser extends Model
{
    protected $table = 'category_user';
    public $timestamps = true;
    protected $fillable = array('user_id', 'category_id');
    //
}
