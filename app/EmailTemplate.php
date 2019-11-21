<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    public function ticketStatus(){
        return $this->belongsTo('App\TicketStatus', 'ticket_status');
    }
}
