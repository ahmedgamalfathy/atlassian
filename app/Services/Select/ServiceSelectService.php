<?php
namespace App\Services\Select;
use App\Models\Services\Service;

class ServiceSelectService{
    public function getServices( $scheduleId = null){
        $query = Service::where('is_active', 1);
        if ($scheduleId) {
            $query->where(function($q) use ($scheduleId) {
                $q->where('schedule_id', $scheduleId)
                  ->orWhereNull('schedule_id');
            });
        } else {
            $query->whereNull('schedule_id');
        }

        return $query->get(['id as value', 'title as label']);
    }

}
?>
