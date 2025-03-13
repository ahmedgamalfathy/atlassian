<?php

namespace App\Http\Controllers\Api\Website;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Reservations\Reservation;

class AppointmentPageController extends Controller
{
    public function create(Request $request){
        DB::beginTransaction();
        try{
        $data=$request->all();
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
