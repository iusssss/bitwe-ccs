<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\TicketUpdate as TicketUpdateResource;

class Ticket extends JsonResource
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
            'id' => $this->ticketId,
            'type' => $this->type,
            'agent' => $this->user,
            'updates' => TicketUpdateResource::collection($this->ticketUpdates),
            'client' => $this->client,
            'client_company' => ($this->client) ? $this->client->company->name : null,
            'service' => $this->service,
            'issue' => $this->issue,
            'created_at' => date('M, d Y G:i:s', strtotime($this->created_at)),
        ];
        // return parent::toArray($request);
    }
}
