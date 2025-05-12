<?php

namespace App\Http\Controllers\Api\Website;

use Illuminate\Http\Request;
use App\Models\Clients\Client;
use Illuminate\Support\Facades\DB;
use App\Models\clients\ClientEmail;
use App\Models\Clients\ClientPhone;
use App\Http\Controllers\Controller;
use App\Services\Client\ClientService;
use App\Models\Reservations\Reservation;
use App\Http\Requests\Reservation\website\CreateReservationRequest;
use App\Models\Services\Service;


class AppointmentPageController extends Controller
{
    protected $clientService;
    public function __construct(ClientService $clientService){
        $this->clientService = $clientService;
    }
    public function create(CreateReservationRequest $createReservationRequest){
        DB::beginTransaction();
        try{
        $data=$createReservationRequest->validated();

        // Check if service exists and has valid schedule
        $service = Service::findOrFail($data["serviceId"]);
        if(!$service->schedule_id) {
            return response()->json([
                "message" => __("messages.error.no_schedule")
            ], 400);
        }

        $client = Client::create([
            "name"=> $data["name"],
            "description"=> $data["description"]?? null,
        ]);

        // Create email if exists
        if(isset($data['email'])) {
            $clientEmail = ClientEmail::create([
                "email" => $data['email'],
                "client_id" => $client->id
            ]);
        }

        // Create phone
        $clientPhone = ClientPhone::create([
            "phone" => $data['phone'],
            "client_id" => $client->id
        ]);

        $reservation = Reservation::create([
            'title'=>$data['title'] ??null,
            'date_to'=>$data["dateTo"] ??null,
            "client_id" => $client->id,
            "service_id" => $data["serviceId"],
            "date" => $data["date"],
            "notes" => $data["notes"] ?? null
        ]);

        // Attach the newly created phone
        $reservation->phones()->attach($clientPhone->id);

        // Attach email if it was created
        if(isset($clientEmail)) {
            $reservation->emails()->attach($clientEmail->id);
        }

        DB::commit();
        return response()->json(["message" => __("messages.success.created")]);
    }catch(\Exception $e){
        DB::rollBack();
        return response()->json(["message" => __("messages.error")]);
    }
    }
}
