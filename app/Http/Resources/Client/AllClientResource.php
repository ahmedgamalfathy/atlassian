<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Client\ClientAddress\AllClientAddressResource;

class AllClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
          __("messages.words.clientId")=>$this->id,
          __("messages.words.name") => $this->name,
          "email" => $this->emails->first()->email??"",
          "phone" => $this->phones->first()->phone??"",
          __("messages.words.description") => $this->description??null,
          __('messages.words.addresses') => AllClientAddressResource::collection($this->whenLoaded('addresses')),
        ];
    }
}
