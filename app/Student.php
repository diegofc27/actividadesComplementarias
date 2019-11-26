<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'id', 'name', 'maternal_surname', 'paternal_surname', 'period', 'grade', 'id_group', 'id_q8', 'id_user', 'condition'
    ];

    public function users(){
        return $this->belongsTo('App\User','id_user');
    }
    
    public function groups(){
        return $this->hasOne('App\Group');
    }

    public function q8(){
        return $this->hasOne('App\Q8');
    }
}
