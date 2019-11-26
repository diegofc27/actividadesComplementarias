<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = ['id_student', 'groupName', 'groupQ8Name', 'period', 'career'];

    public function students(){
        return $this->hasMany('App\Student');
    }
    
}
