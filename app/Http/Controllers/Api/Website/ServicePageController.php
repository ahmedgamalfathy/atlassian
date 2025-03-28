<?php

namespace App\Http\Controllers\Api\Website;

use Illuminate\Http\Request;
use App\Models\Services\Service;
use App\Utils\PaginateCollection;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\Service\ServiceEditResource;
use App\Http\Resources\Service\ServiceResourceCollection;

class ServicePageController extends Controller
{
    public function index(Request $request)
    {
        $services = QueryBuilder::for(Service::class)
        ->allowedFilters(["title","color"])
        ->where('is_active',1)->whereNotNull('schedule_id')
        ->get();
        return response()->json([
            "data"=> new ServiceResourceCollection( PaginateCollection::paginate($services,$request->pageSize?$request->pageSize:10)),
        ]);
    }
    public function edit(Request $request)
    {
        try {
            $service = Service::findOrFail( $request->serviceId);
            return response()->json([
                "data"=>new ServiceEditResource($service)
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "message"=>__("messages.error.not_found"),
            ]);
        }
    }
}
