<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Models\Reservations\Reservation;
use App\Models\Services\Service;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Resources\Reservation\ReservationSchedule\AllReservationScheduleCollection;
use App\Models\Reservations\Reservation as ReservationsReservation;
use App\Services\Reservation\ReservationScheduleService;
use App\Utils\PaginateCollection;
use Illuminate\Support\Facades\DB;
use Vtiful\Kernel\Format;


class FreeReservationScheduleWebsiteController extends Controller
{

    public function index(Request $request)
    {
        // $clientId = $request->clientId;
        $serviceId = $request->serviceId;
        $startDate = Carbon::parse($request->startDate);
        $endDate = Carbon::parse($request->endDate);

        // Retrieve client's reservation schedule
        $serviceReservationSchedule = Service::find($serviceId);

        if (!$serviceReservationSchedule) {
            return response()->json(['error' => 'No schedule found for the client'], 404);
        }

        $schedule = $serviceReservationSchedule->schedule;
        if (!$schedule) {
            return response()->json(['error' => 'No schedule found for the service'], 404);
        }
        $result = [];

        // Iterate through each day in the date range
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $formattedDate = $date->toDateString();
            $dayOfWeek = strtolower($date->format('l')); // Example: 'monday'

            // Initialize empty array to store available times for the current date
            $freeTimes = [];

            // Process fixed schedule (e.g., Sundays)
            $fixedDaySchedule = collect($schedule->times)->first(function ($item) use ($dayOfWeek) {
                return $item['type'] === 'fixed' && $item['day'] === $dayOfWeek;
            });

            if ($fixedDaySchedule) {


                $availableTimes = $fixedDaySchedule['availableTimes'];


                $appointmentTime = (int) $fixedDaySchedule['appointmentTime'];
                $restEachTime = (int) $fixedDaySchedule['restEachTime'];
                $slots = [];



                // Generate time slots
                foreach ($availableTimes as $timeRange) {
                    [$startTime, $endTime] = explode(' - ', $timeRange);

                    $currentTime = $date->copy()->setTimeFromTimeString($startTime);
                    $endDateTime = $date->copy()->setTimeFromTimeString($endTime);

                    while ($currentTime->lt($endDateTime)) {
                        $slots[] = $currentTime->format('H:i');

                        $currentTime->addMinutes($appointmentTime + $restEachTime);
                    }
                }

                // Add generated slots to freeTimes
                $freeTimes = array_merge($freeTimes, $slots);
            }



            // Process dedicated schedule (e.g., specific date like '2024-12-23')
            $dedicatedDateSchedule = collect($schedule->times)->first(function ($item) use ($formattedDate) {
                return $item['type'] === 'dedicated' && $item['date'] === $formattedDate;
            });
            if ($dedicatedDateSchedule) {
                $availableTimes = $dedicatedDateSchedule['availableTimes'];
                $appointmentTime = (int) $dedicatedDateSchedule['appointmentTime'];
                $restEachTime = (int) $dedicatedDateSchedule['restEachTime'];
                $slots = [];

                // Generate time slots
                foreach ($availableTimes as $timeRange) {
                    [$startTime, $endTime] = explode(' - ', $timeRange);

                    $currentTime = $date->copy()->setTimeFromTimeString($startTime);
                    $endDateTime = $date->copy()->setTimeFromTimeString($endTime);

                    while ($currentTime->lt($endDateTime)) {
                        $slots[] = $currentTime->format('H:i');
                        $currentTime->addMinutes($appointmentTime + $restEachTime);
                    }
                }

                // Add generated slots to freeTimes
                $freeTimes = array_merge($freeTimes, $slots);
            }

            // If there are no available times for the day, skip this date
            if (empty($freeTimes)) {
                continue;
            }

            // Fetch reservations for this date
            $reservedTimes = Reservation::where('service_id', $serviceId)
                ->whereDate('date', $formattedDate)
                ->pluck('date')
                ->map(function ($reservationDate) {
                    return Carbon::parse($reservationDate)->format('H:i');
                })
                ->toArray();

            // Filter out reserved times
            $freeTimes = array_values(array_map(fn($time) => ['time' => $time, 'appointmentTime' =>$appointmentTime ],
                array_filter($freeTimes, fn($slot) => !in_array($slot, $reservedTimes))
            ));

            // Add to result
            $result[] = [
                'day' => $date->format('l'), // Use the actual weekday (e.g., 'monday', 'sunday')
                'date' => $formattedDate,
                'times' => $freeTimes
             ];
          }

          return response()->json($result);
        }

    public function checkAvailability(Request $request)
    {
        $servicId = $request->serviceId;
        $date = Carbon::parse($request->date);
        $time = Carbon::parse($request->time);

        $service = Service::findOrFail($servicId);
        $schedule = $service->schedule;
        if (!$schedule) {
            return response()->json(['available' => false, 'message' => 'No schedule found for this service']);
        }
        $dayOfWeek = strtolower($date->format('l'));
        $formattedDate = $date->toDateString();

        $daySchedule = collect($schedule->times)->first(function ($item) use ($dayOfWeek, $formattedDate) {
            return $item['day'] === $dayOfWeek || $item['date'] === $formattedDate;
        });


        if (!$daySchedule || empty($daySchedule['availableTimes'])) {
            return response()->json(['available' => false, 'message' => 'No schedule available for this day']);
        }

        $availableTimes = $daySchedule['availableTimes'];
        $appointmentTime = (int) $daySchedule['appointmentTime'];
        $restEachTime = (int) $daySchedule['restEachTime'];
        $slots = [];

        foreach ($availableTimes as $timeRange) {
            [$startTime, $endTime] = explode(' - ', $timeRange);

            $currentTime = $date->copy()->setTimeFromTimeString($startTime);
            $endDateTime = $date->copy()->setTimeFromTimeString($endTime);

            while ($currentTime->lt($endDateTime)) {
                $slotTime = $currentTime->format('H:i');

                $slots[] = $slotTime;
                $currentTime->addMinutes($appointmentTime + $restEachTime);
            }
        }

        $reservations = Reservation::where('service_id', $servicId)
            ->whereDate('date', $formattedDate)
            ->get();

        $reservedTimes = $reservations->map(function ($reservation) {
            return Carbon::parse($reservation->date)->format('H:i');
        })->toArray();

        $timeString = $time->format('H:i');
        $isAvailable = in_array($timeString, $slots) && !in_array($timeString, $reservedTimes);

        return response()->json([
            'available' => $isAvailable,
            'message' => $isAvailable ? 'The time is available' : 'The time is not available',
            // 'available_slots' => array_diff($slots, $reservedTimes), // Return available slots
        ]);
    }
    public function getAvailableTimes(Request $request)
    {
        $serviceId = $request->serviceId;
        $date = Carbon::parse($request->date);

        $service = Service::findOrFail($serviceId);
        $schedule = $service->schedule;

        if (!$schedule) {
            return response()->json(['error' => 'No schedule found for this service'], 404);
        }

        $dayOfWeek = strtolower($date->format('l'));
        $formattedDate = $date->toDateString();

        // Check for both fixed and dedicated schedules
        $daySchedule = collect($schedule->times)->first(function ($item) use ($dayOfWeek, $formattedDate) {
            return ($item['type'] === 'fixed' && $item['day'] === $dayOfWeek) ||
                   ($item['type'] === 'dedicated' && $item['date'] === $formattedDate);
        });

        if (!$daySchedule || empty($daySchedule['availableTimes'])) {
            return response()->json(['error' => 'No schedule available for this day'], 404);
        }

        $availableTimes = $daySchedule['availableTimes'];
        $appointmentTime = (int) $daySchedule['appointmentTime'];
        $restEachTime = (int) $daySchedule['restEachTime'];
        $slots = [];

        // Generate all possible time slots
        foreach ($availableTimes as $timeRange) {
            [$startTime, $endTime] = explode(' - ', $timeRange);

            $currentTime = $date->copy()->setTimeFromTimeString($startTime);
            $endDateTime = $date->copy()->setTimeFromTimeString($endTime);

            while ($currentTime->lt($endDateTime)) {
                $slotTime = $currentTime->format('H:i');
                $slots[] = [
                    'time' => $slotTime,
                    'appointmentTime' => $appointmentTime
                ];
                $currentTime->addMinutes($appointmentTime + $restEachTime);
            }
        }

        // Get reserved times
        $reservations = Reservation::where('service_id', $serviceId)
            ->whereDate('date', $formattedDate)
            ->get();

        $reservedTimes = $reservations->map(function ($reservation) {
            return Carbon::parse($reservation->date)->format('H:i');
        })->toArray();

        // Filter out reserved times
        $availableSlots = array_filter($slots, function($slot) use ($reservedTimes) {
            return !in_array($slot['time'], $reservedTimes);
        });

        return response()->json([
            'date' => $formattedDate,
            'available_slots' => array_values($availableSlots)
        ]);
    }
}
