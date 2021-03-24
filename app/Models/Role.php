<?php

namespace App\Models;


use Zizaco\Entrust\EntrustRole;
class Role extends EntrustRole
{
    //
    protected $fillable = [
        'name', 'display_name', 'description','description_ar','name_ar','display_name_ar'
    ];

    public  function permissions(){
        return $this->belongsToMany('App\Models\Permission');
    }
    public  function admins(){
        return $this->belongsToMany('App\Admin');
    }
}
