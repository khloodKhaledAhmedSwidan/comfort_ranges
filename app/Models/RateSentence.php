<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RateSentence extends Model
{
    //
    protected $table = 'rate_sentence';
    public $timestamps = true;
    protected $fillable = array('rate_id', 'sentence_id');


}
