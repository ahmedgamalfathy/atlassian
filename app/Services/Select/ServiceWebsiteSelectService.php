<?php
namespace App\Services\Select;
use App\Models\Services\Service;

class ServiceWebsiteSelectService{
    public function getWebsiteServices(){
        $service = Service::where('is_active',1)->whereNotNull('schedule_id')->get(['id as value', 'title as label']);
        return $service;
    }

}
?>
