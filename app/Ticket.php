<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    // Primary Key
    protected $primaryKey = 'ticketId';
    public $incrementing = false;
    // Table Name
    protected $table = 'tickets';
    // Timestamps
    public $timestamps = true;

    protected $fillable = ['ticketId', 'type', 'assignedTo', 'startTime', 'touchPoint', 'issue', 'client_id', 'service_id'];

    public function user(){
        return $this->belongsTo('App\User', 'assignedTo');
    }    
    public function client(){
        return $this->belongsTo('App\Client', 'client_id');
    }
    public function service(){
        return $this->belongsTo('App\Service', 'service_id');
    }
    public function ticketUpdates() {
        return $this->hasMany('App\TicketUpdate', 'ticket_id');
    }
    public function resolved() {
        return $this->ticketUpdates()
                ->where('resolvedBy', '<>', 'null')
                ->orderBy('created_at', 'DESC');
    }
    public function updates() {
        return $this->ticketUpdates()
                ->orderBy('created_at', 'DESC');
    }
    public function tempClient() {
        return $this->hasOne('App\TempClient', 'ticket_id');
    }
    
}
