<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Scorecard extends Model
{
    protected $hidden = ['updated_at'];

    public function details(){
        return $this->hasMany('App\ScorecardDetails', 'scorecard_id');
    }
}
