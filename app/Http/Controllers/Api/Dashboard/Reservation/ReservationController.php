<?php

namespace App\Http\Controllers\Api\Dashboard\Reservation;

use App\Services\Reservation\ReservationService;
use Illuminate\Http\Request;
use App\Utils\PaginateCollection;
use Illuminate\Support\Facades\DB;
use App\Models\Reservations\Reservation;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\CreateReservationRequest;
use App\Http\Requests\Reservation\UpdateReservationRequest;
use App\Http\Resources\Reservation\ReservationResource;
use App\Http\Resources\Reservation\ReservationEditResource;
use App\Http\Resources\Reservation\AllReservationResourceCollection;
use App\Http\Resources\Client\ClientReservation\ClientReservationResource;

class ReservationController extends Controller
{

    private $reservationService;
    public function __construct( ReservationService $reservationService)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:all_reservation', ['only' => ['index']]);
        $this->middleware('permission:create_reservation', ['only' => ['create']]);
        $this->middleware('permission:edit_reservation', ['only' => ['edit']]);
        $this->middleware('permission:update_reservation', ['only' => ['update']]);
        $this->middleware('permission:delete_reservation', ['only' => ['delete']]);
         $this->reservationService=$reservationService;
    }
    public function index(Request $request){

        $reservations= $this->reservationService->allReservation();

        return response()->json([
            "data"=>  new AllReservationResourceCollection( PaginateCollection::paginate($reservations,$request->pageSize?$request->pageSize:10)),
        ]);
    }
    public function create(CreateReservationRequest $createReservationRequest){
        DB::beginTransaction();
        try{
        $data=$createReservationRequest->validated();
        $reservation= $this->reservationService->createReservation($data);
        DB::commit();
        return response()->json(["message"=>__("messages.success.created")]);
    }catch(\Exception $e){
        DB::rollBack();
        return response()->json(["message"=>__("messages.error")]);
    }
    }
    public function edit(Request $request){
      $reservation=$this->reservationService->editReservation($request->reservationId);
      if(!$reservation){
        return response()->json(["message"=>__("messages.error.not_found")]);
      }
      return response()->json(["data"=>new ClientReservationResource($reservation) ]);
    }
    public function update(UpdateReservationRequest $updateReservationRequest){
        DB::beginTransaction();
        try {
            $data = $updateReservationRequest->validated();
            $this->reservationService->updateReservation($data);
            DB::commit();
            return response()->json(["message" => __("messages.success.updated")]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(["message" => __("messages.error.update_failed"), "error" => $e->getMessage()], 500);
        }
    }
    public function delete(Request $request){
       $reservation = $this->reservationService->deleteReservation($request->reservationId);
       if(!$reservation){
         return response()->json(["message"=> __("messages.error.not_found")]);
       }
       $reservation->delete();
       return response()->json(["message"=> __("messages.success.deleted")]);
    }
}
