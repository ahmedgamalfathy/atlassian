<?php

namespace App\Models\Reservations;

use App\Models\Clients\Client;
use App\Models\Services\Service;
use App\Models\clients\ClientEmail;
use App\Models\Clients\ClientPhone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function emails()
    {
        return $this->belongsToMany(ClientEmail::class,'reservation_email','reservation_id','email_id');
    }
    public function phones()
    {
        return $this->belongsToMany(ClientPhone::class,"reservation_phone",'reservation_id','phone_id');
    }
}
