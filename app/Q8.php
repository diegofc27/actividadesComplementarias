<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Q8 extends Model
{
    protected $fillable = [
        'id','nameQ8', 'period', 'num_students', 'condition',
    ];
    protected $table = 'q8_groups';
    public $timestamps = false;

    public function students(){
        return $this->belongsTo('App\Student');
    }
}
