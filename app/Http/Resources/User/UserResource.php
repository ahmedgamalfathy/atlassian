<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Role\RoleResource;
use Illuminate\Http\Resources\Json\JsonResource;


class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //'userId' ,'email','phone','address','status','avatar',
        $userRoles = $this->getRoleNames();
        $role = Role::findByName($userRoles[0]);
        return [
            __('messages.words.userId') => $this->id,
            __('messages.words.name') => $this->name?$this->name:"",
            __('messages.words.email') => $this->email??"",
            __('messages.words.phone')=> $this->phone?$this->phone:"",
            __('messages.words.address')=> $this->address?$this->address:"",
            __('messages.words.status') => $this->status,
            __('messages.words.avatar') => $this->avatar?Storage::disk('public')->url($this->avatar):"",
            __('messages.words.roleId') => new RoleResource($role),
        ];
    }
}
