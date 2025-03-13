<?php

namespace App\Http\Controllers\Api\Dashboard\Reservation;

use Illuminate\Http\Request;
use App\Utils\PaginateCollection;
use Illuminate\Support\Facades\DB;
use App\Models\clients\ClientEmail;
use App\Models\Clients\ClientPhone;
use App\Http\Controllers\Controller;
use App\Http\Resources\Client\ClientReservation\ClientReservationResource;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\Reservations\Reservation;
use App\Models\Reservations\ReservationEmail;
use App\Models\Reservations\ReservationPhone;
use App\Services\Reservation\FreeReservationServices;
use App\Http\Resources\Reservation\ReservationResource;
use App\Http\Resources\Reservation\ReservationEditResource;
use App\Http\Resources\Reservation\AllReservationResourceCollection;

class ReservationController extends Controller
{

    public $FreeReservation;
    public function __construct(FreeReservationServices $freeReservationServices)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:all_reservation', ['only' => ['index']]);
        $this->middleware('permission:create_reservation', ['only' => ['create']]);
        $this->middleware('permission:edit_reservation', ['only' => ['edit']]);
        $this->middleware('permission:update_reservation', ['only' => ['update']]);
        $this->middleware('permission:delete_reservation', ['only' => ['delete']]);
         $this->FreeReservation=$freeReservationServices;
    }
    public function index(Request $request){
        $reservations=QueryBuilder::for(Reservation::class)
        ->allowedFilters(['date'])
        ->get();
        return response()->json([
            "data"=>  new AllReservationResourceCollection( PaginateCollection::paginate($reservations,$request->pageSize?$request->pageSize:10)),
        ]);
    }
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
    public function edit(Request $request){
      $reservation=Reservation::with('emails','phones')->find($request->reservationId);
      if(!$reservation){
        return response()->json(["message"=>__("messages.error.not_found")]);
      }
      return response()->json(["data"=>new ClientReservationResource($reservation) ]);
    }
    public function update(Request $request){
        DB::beginTransaction();
        try {
            $data = $request->all();
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
            DB::commit();
            return response()->json(["message" => __("messages.success.updated")]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(["message" => __("messages.error.update_failed"), "error" => $e->getMessage()], 500);
        }
    }
    public function delete(Request $request){
       $reservation = Reservation::find($request->reservationId);
       if(!$reservation){
         return response()->json(["message"=> __("messages.error.not_found")]);
       }
       $reservation->delete();
       return response()->json(["message"=> __("messages.success.deleted")]);
    }
}
