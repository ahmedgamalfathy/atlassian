<?php

namespace App\Models\Services;

use App\Models\User;
use App\Models\Schedules\Schedule;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reservations\Reservation;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
