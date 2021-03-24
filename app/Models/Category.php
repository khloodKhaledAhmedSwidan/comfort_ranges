<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $table = 'categories';
    public $timestamps = true;
    protected $fillable = array('name', 'name_ar', 'image', 'branch_id','arranging','active');


//    public function users()
//    {
//        return $this->hasMany('App\User');
//    }


    public function sliders()
    {
        return $this->hasMany('App\Models\Slider','category_id');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }
    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function branch()
    {
        return $this->belongsTo('App\Models\Branch', 'branch_id');
    }

}
