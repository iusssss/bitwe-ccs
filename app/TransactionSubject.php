<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionSubject extends Model
{
	protected $hidden = ['created_at', 'updated_at'];

    public function transactions(){
        return $this->hasMany('App\Transaction', 'service_id');
    }
}
