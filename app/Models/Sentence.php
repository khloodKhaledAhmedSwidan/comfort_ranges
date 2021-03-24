<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sentence extends Model
{
    //
    protected $table = 'sentences';
    public $timestamps = true;
    protected $fillable = array('sentence', 'sentence_ar');


    public function rates()
    {
        return $this->belongsToMany('App\Models\Rate');
    }


}
