<?php
namespace App\Services\Reservation;

use Carbon\Carbon;
use App\Models\Services\Service;
use App\Models\Reservations\Reservation;

class FreeReservationServices {
    public function checkAvailability($servicId,$date,$time)
    {
        $service = Service::findOrFail($servicId);
        $schedule = $service->schedule;

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
            'available_slots' => array_diff($slots, $reservedTimes), // Return available slots
        ]);
    }
}



?>

