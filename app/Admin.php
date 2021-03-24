<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\AdminResetPasswordNotification;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class Admin extends Authenticatable {

    use Notifiable;
    protected $guard = 'admin';
	protected $table = 'admins';
	public $timestamps = true;
	protected $fillable = array('name', 'phone', 'email', 'password');
    protected $hidden = [
        'password', 'remember_token',
    ];



    use EntrustUserTrait;

    public  function roles(){
        return $this->belongsToMany('App\Models\Role','admin_role','admin_id','role_id');
    }
    public  function getRoleIdAttribute(){
        return $this->roles()->pluck('id')->toArray();
    }
}
