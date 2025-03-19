<?php
namespace App\Services\Schedule;

use App\Models\Services\Service;
use App\Models\Schedules\Schedule;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class ScheduleService{
    public function allSchedule(){
        $schedules = QueryBuilder::for(Schedule::class)
        ->allowedFilters('title')
        ->get();
        return $schedules;
    }
    public function createSchedule(array $data){
        $schedule= Schedule::create([
            "title" =>$data['title'],
            "times" =>$data['times']
        ]);
        $servicesId = $data['servicesId'];
        foreach($servicesId as $serviceId)
        {
            $service= Service::findOrFail($serviceId);
            if(!$service)
            {
                return response()->json([
                    "message"=>"Service not found"
                ]);
            }
            $service->schedule_id = $schedule->id;
            $service->save();
        }
        return $schedule;
    }
    public function editSchedule(int $scheduleId){
        $schedule=Schedule::with('services')->find($scheduleId);
        return $schedule;
    }
    public function updateSchedule(array $data){
        $schedule= Schedule::findOrFail($data['scheduleId']);
        $schedule->update([
            "title"=>$data['title'],
            "times"=>$data['times']
        ]);
        $servicesId = $data['servicesId'];
        foreach($servicesId as $serviceId)
        {
            $service= Service::findOrFail($serviceId);
            if(!$service)
            {
                return response()->json([
                    "message"=>__("messages.error.not_found"),
                ]);
            }
            $service->schedule_id = $schedule->id;
            $service->save();
        }
        return $schedule;
    }
    public function deleteSchedule(int $scheduleId){
        $schedule=Schedule::findOrFail($scheduleId);
        $schedule->delete();
        return $schedule;
    }
}
?>
