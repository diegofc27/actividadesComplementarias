<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    //
    protected $fillable = [
        'id', 'name', 'lastName1', 'lastName2', 'period', 'id_user', 'condition'
    ];

    public function users(){
        return $this->hasOne('App\User');
    }
    public function groups(){
        return $this->belongsTo('App\Group');
    }
}
