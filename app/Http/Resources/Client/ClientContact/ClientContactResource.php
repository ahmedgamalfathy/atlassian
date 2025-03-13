<?php

namespace App\Http\Resources\Client\ClientContact;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientContactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//'clientId' 'name' 'addresses' 'emails' 'phones' 'description'
// 'clientAddressId' 'address' 'clientEmailId' 'email' 'clientPhoneId'
        return [
            __('messages.words.clientPhoneId') => $this->id,
            __('messages.words.phone') => $this->phone??""
        ];

    }
}
