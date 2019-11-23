<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EndorsedTicket extends Model
{
    protected $hidden = ['updated_at'];
    protected $fillable = ['ticket_id', 'user_id'];

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }
}
