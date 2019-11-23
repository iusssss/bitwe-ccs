<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EndorsedTicket extends Model
{
    protected $hidden = ['updated_at'];
    protected $fillable = ['ticket_id'];
}
