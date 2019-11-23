<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerSatisfaction extends Model
{
	protected $hidden = ['updated_at'];
    protected $fillable = ['user_id', 'score'];
    public function user() {
    	return $this->belongsTo('App\User');
    }
}
