<?php

namespace App\Http\Controllers\Api\Dashboard\Widget;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Clients\Client;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Reservations\Reservation;

class WidgetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function yearOverview(){
        $monthlyReservations = Reservation::select(
            DB::raw('COUNT(*) as totalReservations, DATE_FORMAT(date, "%m-%Y") as month')
        )
        ->where('date', '>=', Carbon::today()->subMonths(6)->startOfMonth())
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        return $monthlyReservations;
    }
    public function widget(){
        $totalClient=Client::count();
        $totalAppointment=Reservation::count();
        $todayAppointment=Reservation::whereDate('date',Carbon::today())->count();

        return response()->json([
            "totalClient"=>$totalClient,
            "totalAppointment"=>$totalAppointment,
            "todayAppointment"=>$todayAppointment,
            "yearOverview"=> $this->yearOverview()
        ]);
    }

}
