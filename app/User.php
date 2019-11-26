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
    protected $fillable = [
        'id', 'email', 'password', 'condition', 'id_role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    public $hidden = [
         'remember_token',
    ];

    public function role(){
        return $this->belongsTo('App\Role');
    }

    public function teacher(){
        return $this->belongsTo('App\Teacher');
    }

    public function student(){
        return $this->hasOne('App\Student');
    }
}
