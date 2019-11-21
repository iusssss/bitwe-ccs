<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempClient extends Model
{
    public $timestamps = false;
    protected $fillable = ['ticket_id', 'company', 'fullname', 'email', 'contactNumber'];

    public function ticket() {
        return $this->hasOne('App\Ticket', 'ticketId', 'ticket_id');
    }
}
