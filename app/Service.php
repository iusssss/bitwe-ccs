<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
	protected $fillable = ['name', 'description'];
	protected $hidden = ['created_at', 'updated_at'];
    public function transactions(){
        return $this->hasMany('App\Transaction');
    }
}
