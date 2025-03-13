<?php
namespace App\Services\Select;
use App\Models\Services\Service;

class ServiceSelectService{
    public function getServices(){
        $service = Service::all(['id as value', 'title as label']);
        return $service;
    }

}
?>
