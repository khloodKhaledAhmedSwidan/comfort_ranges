<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */


    /*
     * *  type: 1 -> client
     * type :2 -> employee
     */
    protected $table = 'users';
    public $timestamps = true;
    protected $fillable = [
        'phone',
        'name',
//        'category_id',
        'active',
        'type',
        'password',
        'api_token',
        'verification_code',
        'branch_id',
        'image',
        'available_orders',
           'language',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    /**
     *   protected $casts = [
     *       'email_verified_at' => 'datetime',
     *   ];
     */
//    protected $dates = ['subscription_date'];


//    public function category()
//    {
//        return $this->belongsTo('App\Models\Category', 'category_id');
//    }


    public function shifts()
    {
        return $this->hasMany('App\Models\Shift');
    }

    public function notes()
    {
        return $this->hasMany('App\Models\Note');
    }

    public function contacts()
    {
        return $this->hasMany('App\Models\Contact');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Models\Category');
    }

    public function userOrders()
    {
        return $this->hasMany('App\Models\Order', 'user_id');
    }

    public function employeeOrders()
    {
        return $this->hasMany('App\Models\Order', 'employee_id');
    }

    public function notifications()
    {
        return $this->hasMany('App\Models\Notification');
    }

    public function rates()
    {
        return $this->hasMany('App\Models\Rate');
    }

    public function locations()
    {
        return $this->hasMany('App\Models\Location');
    }

    public function devices()
    {
        return $this->hasMany('App\Models\Device');
    }

    public function complaints()
    {
        return $this->hasMany('App\Models\Complaint');
    }

    public function reports()
    {
        return $this->hasMany('App\Models\Report');
    }

    public function branch()
    {
        return $this->belongsTo('App\Models\Branch', 'branch_id');
    }


}
