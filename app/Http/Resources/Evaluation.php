<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Evaluation extends JsonResource
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
            'agent' => $this->agent,
            'evaluator' => $this->evaluator,
            'score' => $this->score,
            'created_at' => date_format($this->created_at, 'M. d, Y'),
            'details' => $this->details,
        ];
    }
}
