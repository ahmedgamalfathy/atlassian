<?php
namespace App\Services\Reservation;
use Illuminate\Support\Facades\DB;
use App\Models\Reservations\Reservation;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\Reservation\ReservationFilterDate;
class ReservationService {
public function allReservation()
{
    $reservations= QueryBuilder::for(Reservation::class)
    ->allowedFilters([
        AllowedFilter::custom('date', new ReservationFilterDate),
    ])
    ->get();
    return $reservations;
}
public function editReservation (int $reservationId)
{
    $reservation= Reservation::with('emails','phones')->find($reservationId);
    return $reservation;
}
public function createReservation(array $data)
{
    $reservation= Reservation::create([
        "client_id"=>$data["clientId"],
        "service_id"=>$data["serviceId"],
        "date"=>$data["date"],
        "notes"=> $data["notes"]??null
    ]);
    if($data["clientPhonesId"]){
        $reservation->phones()->attach($data["clientPhonesId"]);
    }
    if($data["clientEmailsId"]){
        $reservation->emails()->attach($data["clientEmailsId"]);
    }
    return $reservation;
}
public function updateReservation( array $data)
{
    $reservation = Reservation::findOrFail($data["reservationId"]);
    $reservation->update([
        "client_id" => $data["clientId"],
        "service_id" => $data["serviceId"],
        "date" => $data["date"],
        "notes" => $data["notes"] ?? null
    ]);
    if ($data["clientPhonesId"]) {
        $reservation->phones()->sync($data["clientPhonesId"]);
    }
    if ($data["clientEmailsId"]) {
       $reservation->emails()->sync($data["clientEmailsId"]);
    }
    return $reservation;
}
public function deleteReservation(int $reservationId){
    $reservation = Reservation::find($reservationId);
    $reservation->delete();
}
}
