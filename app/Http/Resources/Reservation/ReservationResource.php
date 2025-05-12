<?php

namespace App\Http\Resources\Reservation;

use Carbon\Carbon;
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
            "clientName"=>$this->client->name,
            "clientEmail"=>$this->emails->first()->email ??"",
            __("messages.words.ReservationId")=>$this->id,
            __("messages.words.date")=>$this->date,
            __("messages.words.notes")=>$this->notes??null,
            __("messages.words.serviceId")=>$this->service_id,
            "serviceColor"=>$this->service->color,
            "timeFrom"=>Carbon::parse($this->date)->format('H:i'),
            "timeTo"=>$this->date_to ?Carbon::parse($this->date_to)->format('H:i') :"",
            "title" => $this->title??""

        ];
    }
}
