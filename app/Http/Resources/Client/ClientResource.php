<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Client\ClientContact\ClientContactResource;
use App\Http\Resources\Client\ClientAddress\AllClientAddressResource;
use App\Http\Resources\Client\ClientContact\AllClientEmailResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            __('messages.words.clientId') => $this->id,
            __('messages.words.name') => $this->name,
            __('messages.words.addresses') => AllClientAddressResource::collection($this->whenLoaded('addresses')),
            __('messages.words.emails') => AllClientEmailResource::collection($this->whenLoaded('emails')),
            __('messages.words.phones') => ClientContactResource::collection($this->whenLoaded('phones')),
            __('messages.words.description') => $this->description??null
        ];
    }
}
