<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    //
    protected $table = 'sliders';
    public $timestamps = true;
    protected $fillable = array('image','link','category_id');


    public function category(){
        return $this->belongsTo('App\Models\Category', 'category_id');

    }

}
