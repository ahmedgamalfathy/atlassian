<?php

namespace App\Http\Resources\Reservation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         //"ReservationId","date","notes","serviceId", "clientId"
         //
        return [
            __("messages.words.clientId")=>$this->client_id,
            __("messages.words.ReservationId")=>$this->id,
            __("messages.words.date")=>$this->date,
            __("messages.words.notes")=>$this->notes??null,
            __("messages.words.serviceId")=>$this->service_id,
        ];
    }
}
