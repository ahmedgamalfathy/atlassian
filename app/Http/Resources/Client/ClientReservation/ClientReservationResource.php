<?php

namespace App\Http\Resources\Client\ClientReservation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            __("messages.words.clientId")=>$this->client_id,
            __("messages.words.ReservationId")=>$this->id,
            __("messages.words.date")=>$this->date,
            __("messages.words.notes")=>$this->notes??null,
            __("messages.words.serviceId")=>$this->service_id,
            __("messages.words.reservationEmails")=>$this->emails? ClientReservationEmail::collection($this->emails):null,
            __("messages.words.reservationPhones")=>$this->phones?ClientReservationPhone::collection($this->phones):null
        ];
    }
}
