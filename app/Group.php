<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    //
    protected $fillable = [
        'id', 'name', 'id_teacher', 'condition', 'period', 'max_students', 'num_students', 'id_activity', 'schedule'
    ];

    public function teachers(){
        return $this->hasOne('App\Teacher');
    }
    public function activities(){
        return $this->hasOne('App\Activity');
    }

    public function students(){
        return $this->belongsTo('App\Student');
    }
}
