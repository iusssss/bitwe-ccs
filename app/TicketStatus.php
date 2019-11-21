<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketStatus extends Model
{
	protected $hidden = ['created_at', 'updated_at'];
	
    public function emailTemplate(){
        return $this->hasOne('App\EmailTemplate');
    }
    public function ticket(){
        return $this->hasMany('App\Ticket');
    }
}
