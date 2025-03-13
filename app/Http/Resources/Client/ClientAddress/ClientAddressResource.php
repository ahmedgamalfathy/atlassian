<?php

namespace App\Http\Resources\Client\ClientAddress;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientAddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //dd($this->countries->toArray());
        return [
            __('messages.words.clientAddressId') => $this->id,
            __('messages.words.address') => $this->title,
            __('messages.words.clientId')=> $this->client_id
        ];

    }
}
