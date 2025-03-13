<?php

namespace App\Http\Resources\Client\ClientContact;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllClientEmailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            __('messages.words.clientEmailId') => $this->id,
            __('messages.words.email') => $this->email??""
        ];
    }
}
