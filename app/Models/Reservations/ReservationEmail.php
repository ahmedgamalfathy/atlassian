<?php

namespace App\Models\Reservations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationEmail extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $table ="reservation_email";
}
