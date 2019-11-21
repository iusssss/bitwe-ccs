<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    protected $hidden = ['created_at', 'updated_at'];
    protected $fillable = ['name', 'percentage'];

    public function questions() {
    	return $this->hasMany('App\Question', 'criteria_id');
    }
}

