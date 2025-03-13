<?php

namespace App\Http\Resources\Client\ClientReservation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientReservationPhone extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            __("messages.words.reservationPhoneId")=>$this->id,
            __("messages.words.reservationPhone")=>$this->phone,
        ];
    }
}
