<?php

namespace App\Models\Clients;

use Illuminate\Database\Eloquent\Model;
use App\Models\Reservations\Reservation;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientPhone extends Model
{
    use HasFactory;
    protected $table ="phones";
    protected $guarded = [];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function reservation()
    {
        return $this->belongsToMany(Reservation::class,"reservation_phone","reservation_id","phone_id");
    }
}
