<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Scorecard extends Model
{
    protected $hidden = ['updated_at'];

    public function details(){
        return $this->hasMany('App\ScorecardDetails', 'scorecard_id');
    }
    public function agent() {
    	return $this->belongsTo('App\User', 'agent_id');
    }
    public function evaluator() {
    	return $this->belongsTo('App\User', 'admin_id');
    }
}
