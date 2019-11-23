<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $hidden = ['updated_at'];
    protected $fillable = ['table', 'description', 'user_id'];

    public function user() {
    	return $this->belongsTo('App\User', 'user_id');
    }
}
