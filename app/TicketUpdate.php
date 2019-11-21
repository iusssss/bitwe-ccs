<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketUpdate extends Model
{
    // Table Name
    protected $table = 'ticket_updates';
    protected $primaryKey = 'id';

	protected $hidden = ['id', 'updated_at'];

    protected $fillable = ['ticket_id', 'status', 'action_taken', 'resolution', 'resolvedBy', 'resolvedAt', 'closedBy', 'closedAt', 'timeElapsed'];

    public function ticket() {
    	$this->belongsTo('App\Ticket', 'ticketId');
    }
    public function resolver(){
        return $this->belongsTo('App\User', 'resolvedBy');
    }
    public function ticketStatus() {
        return $this->belongsTo('App\TicketStatus', 'status');
    }
}
