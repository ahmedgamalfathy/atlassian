<?php

namespace App\Http\Controllers\Api\Dashboard\Services;

use Illuminate\Http\Request;
use App\Models\Services\Service;
use App\Utils\PaginateCollection;
use App\Http\Controllers\Controller;
use App\Enums\Services\ServiceActive;
use App\Filters\Service\ServiceFilterDate;
use App\Http\Requests\Service\CreateServiceRequest;
use App\Http\Requests\Service\UpdateServiceRequest;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Resources\Service\ServiceEditResource;
use App\Http\Resources\Service\ServiceResourceCollection;
use Symfony\Component\HttpKernel\DependencyInjection\ServicesResetter;

class ServiceController extends Controller
{
    public function __construct()
    {
        //all_service,create_service,edit_service,update_service,delete_service
        $this->middleware('auth:api');
        $this->middleware('permission:all_service', ['only' => ['index']]);
        $this->middleware('permission:create_service', ['only' => ['create']]);
        $this->middleware('permission:edit_service', ['only' => ['edit']]);
        $this->middleware('permission:update_service', ['only' => ['update']]);
        $this->middleware('permission:delete_service', ['only' => ['delete']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $services = QueryBuilder::for(Service::class)
        ->allowedFilters(["title","color"])
        ->get();
        return response()->json([
            "data"=> new ServiceResourceCollection( PaginateCollection::paginate($services,$request->pageSize?$request->pageSize:10)),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(CreateServiceRequest $createServiceRequest)
    {
    try {
        $data= $createServiceRequest->validated();
        Service::create([
            "title"=>$data["title"],
            "color"=>$data['color'],
            "is_active"=>ServiceActive::from($data['isActive'])->value,
            "description"=>$data["description"],
        ]);
        return response()->json([
            "message"=>__("messages.success.created"),
        ]);
    } catch (\Throwable $th) {
        return response()->json([
            "message"=>__("messages.error"),
        ]);
    }
}

    /**
     * Display the specified resource.
     */
    public function edit(Request $request)
    {
        try {
            $service = Service::findOrFail( $request->get("serviceId"));
            return response()->json([
                "data"=>new ServiceEditResource($service)
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "message"=>__("messages.error.not_found"),
            ]);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $updateServiceRequest)
    {
    try{
        $data=$updateServiceRequest->validated();
        $service =Service::findOrFail($data['serviceId']);
        $service->update([
            "title"=>$data['title'],
            "color"=>$data['color'],
            "is_active"=>ServiceActive::from($data['isActive'])->value,
            "description"=>$data['description'],
        ]);
        return response()->json([
            "message"=>__("messages.success.updated"),
        ]);
    }catch(\Throwable $th) {
       return response()->json([
           "message"=>__("messages.error.not_found"),
       ]);
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {
        try {
            $service = Service::findOrFail( $request->get("serviceId"));
            $service->delete();
            return response()->json([
                "message"=>__("messages.success.deleted"),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "message"=>__("messages.error.not_found"),
            ]);
        }
    }
}
