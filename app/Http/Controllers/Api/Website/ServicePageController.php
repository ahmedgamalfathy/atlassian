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
        ->get();
        return response()->json([
            "data"=> new ServiceResourceCollection( PaginateCollection::paginate($services,$request->pageSize?$request->pageSize:10)),
        ]);
    }
    public function show(Request $request, $slug, $singleSlug)
    {
        try {
            $service = Service::findOrFail( $singleSlug);
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
