<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScorecardDetails extends Model
{
    protected $hidden = ['created_at', 'updated_at'];

    public function info(){
        return $this->hasOne('App\Scorecard', 'id', 'scorecard_id');
    }
}
