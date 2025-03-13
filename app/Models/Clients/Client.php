<?php

namespace App\Models\Clients;


use App\Models\Clients\ClientEmail;
use App\Models\Clients\ClientPhone;
use App\Models\Clients\ClientAddress;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reservations\Reservation;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
    public function emails()
    {
         return $this->hasMany(ClientEmail::class);
    }
    public function phones()
    {
        return $this->hasMany(ClientPhone::class);
    }
    public function addresses()
    {
        return $this->hasMany(ClientAddress::class);
    }

}
