<?php

namespace App\Http\Resources\Client\ClientReservation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientReservationEmail extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            __("messages.words.reservationEmailId")=>$this->id,
            __("messages.words.reservationEmail")=>$this->email,
        ];
    }
}
