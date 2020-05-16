<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasRoles,HasApiTokens,Notifiable;
    protected $guard_name ='api';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    

    public function getChef()
    {
        return $this->belongsTo('App\User',"chef_id");
    }

    public function getEquipe()
    {
        return $this->hasMany('App\User',"chef_id");
    }

    public function getTasks()
    {
        return $this->hasMany('App\Task',"user_id");
    }

    public static function getChiefs(){
        return $this->whereHas("roles", function($q){ $q->where("name", "Team Chief"); })->get();
    }
}
