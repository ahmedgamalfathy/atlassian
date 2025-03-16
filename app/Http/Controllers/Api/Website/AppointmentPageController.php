<?php

namespace App\Http\Controllers\Api\Website;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\CreateReservationRequest;
use App\Models\Reservations\Reservation;

class AppointmentPageController extends Controller
{
    public function create(CreateReservationRequest $createReservationRequest){
        DB::beginTransaction();
        try{
        $data=$createReservationRequest->validated();
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
        DB::commit();
        return response()->json(["message"=>__("messages.success.created")]);
    }catch(\Exception $e){
        DB::rollBack();
        return response()->json(["message"=>__("messages.error")]);
    }
    }
}
