<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketUpdate extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'ticket_id' => $this->ticket_id,
            'status' => $this->ticketStatus,
            'action_taken' => $this->action_taken,
            'resolution' => $this->resolution,
            'resolvedBy' => $this->resolvedBy ? $this->resolver : null,
            'closedBy' => $this->closedBy ? $this->resolver : null,
            'timeElapsed' => $this->timeElapsed,
            'created_at' => date('M, d Y G:i:s', strtotime($this->created_at)),
        ];
    }
}
