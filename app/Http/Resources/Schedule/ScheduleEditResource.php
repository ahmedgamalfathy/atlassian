<?php

namespace App\Http\Resources\Schedule;

use Illuminate\Http\Request;
use App\Http\Resources\Service\ServiceResource;
use Illuminate\Http\Resources\Json\JsonResource;


class ScheduleEditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {//'scheduleId','title','times'
        return [
            __('messages.words.scheduleId') => $this->id,
           __('messages.words.title')=> $this->title,
           __('messages.words.times')=> $this->times,
           __('messages.words.servicesId')=> $this->services->pluck('id')->toArray()??"",
           "description"=>$this->description??""
        ];
    }
}
