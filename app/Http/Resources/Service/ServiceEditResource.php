<?php

namespace App\Http\Resources\Service;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Schedule\ScheduleResource;

class ServiceEditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            __("messages.words.serviceId")=> $this->id,
            __("messages.words.isActive")=> $this->is_active,
            __("messages.words.title")=> $this->title,
            __("messages.words.color")=> $this->color,
            __("messages.words.description")=> $this->description,
            __("messages.words.scheduleId")=> new ScheduleResource($this->schedule)
        ];
    }
}
