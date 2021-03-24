<?php

namespace App\Models;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    //
    protected $fillable = [
        'name', 'display_name', 'description','group_id','name_ar','description_ar','display_name_ar'
    ];
    public  function roles(){
        return $this->belongsToMany('App\Models\Role');
    }
}
