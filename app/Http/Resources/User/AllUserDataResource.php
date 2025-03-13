<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Role\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AllUserDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {


        //dd($userData);

        return [
            __('messages.words.userId') => $this->id,
            __('messages.words.name') => $this->name??"",
            __('messages.words.email') => $this->email??"",
            __('messages.words.username') => $this->username??"",
            __('messages.words.phone') => $this->phone??"",
            __('messages.words.address') => $this->address??"",
            __('messages.words.status' )=> $this->status,
            __('messages.words.avatar') => $this->avatar?Storage::disk('public')->url($this->avatar):"",
            __('messages.words.roleId') => RoleResource::collection($this->whenLoaded('roles'))[0]->id,
        ];
    }
}
