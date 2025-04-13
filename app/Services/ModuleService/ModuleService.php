<?php
namespace App\Services\ModuleService;

use App\Models\Services\Service;
use App\Enums\Services\ServiceActive;
use Spatie\QueryBuilder\QueryBuilder;
class ModuleService{

public function allService()
{
        $services = QueryBuilder::for(Service::class)
        ->allowedFilters(["title","color"])
        ->get();
        return $services;
}

public function createService(array $data)
{
    $service=Service::create([
        "title"=>$data["title"],
        "color"=>$data['color'],
        "is_active"=>ServiceActive::from($data['isActive'])->value,
        "description"=>$data["description"] ??null,
    ]);
    return $service;
}
public function editService(int  $serviceId )
{
    $service = Service::findOrFail( $serviceId);
    return $service;
}
public function updateService(array $data)
{
    $service =Service::findOrFail($data['serviceId']);
    $service->update([
        "title"=>$data['title'],
        "color"=>$data['color'],
        "is_active"=>ServiceActive::from($data['isActive'])->value,
        "description"=>$data['description']??null,
    ]);
    return $service;
}

public function deleteService(int $serviceId )
{
    $service = Service::findOrFail( $serviceId);
    $service->delete();
    return "done";

}


}

