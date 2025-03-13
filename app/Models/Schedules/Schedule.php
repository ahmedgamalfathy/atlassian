<?php

namespace App\Models\Schedules;

use App\Models\Services\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;
    protected $casts = [
        'times' => 'array',
    ];
    protected $guarded=[];
    public function  services()
    {
        return $this->hasMany(Service::class);
    }
}
