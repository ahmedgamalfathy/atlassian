<?php

namespace App\Models\clients;

use Illuminate\Database\Eloquent\Model;
use App\Models\Reservations\Reservation;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientEmail extends Model
{
    use HasFactory;
    protected $guarded =[];
    protected $table = 'emails';
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function reservation()
    {
        return $this->belongsToMany(Reservation::class,"reservation_email","reservation_id","email_id");
    }
}
