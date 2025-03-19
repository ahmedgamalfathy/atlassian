<?php

namespace App\Http\Controllers\Api\Dashboard\Services;

use Illuminate\Http\Request;
use App\Models\Services\Service;
use App\Enums\Services\ServiceActive;
use Spatie\QueryBuilder\QueryBuilder;
use App\Utils\PaginateCollection;
use App\Http\Controllers\Controller;
use App\Filters\Service\ServiceFilterDate;
use App\Http\Requests\Service\CreateServiceRequest;
use App\Http\Requests\Service\UpdateServiceRequest;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Resources\Service\ServiceEditResource;
use App\Http\Resources\Service\ServiceResourceCollection;
use App\Services\ModuleService\ModuleService;
use Symfony\Component\HttpKernel\DependencyInjection\ServicesResetter;

class ServiceController extends Controller
{
    private $moduleService;
    public function __construct(ModuleService $moduleService)
    {
        //all_service,create_service,edit_service,update_service,delete_service
        $this->middleware('auth:api');
        $this->middleware('permission:all_service', ['only' => ['index']]);
        $this->middleware('permission:create_service', ['only' => ['create']]);
        $this->middleware('permission:edit_service', ['only' => ['edit']]);
        $this->middleware('permission:update_service', ['only' => ['update']]);
        $this->middleware('permission:delete_service', ['only' => ['delete']]);
        $this->moduleService =$moduleService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $services = $this->moduleService->allService();
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
        $this->moduleService->createService($data);
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
            // $service = Service::findOrFail( $request->get("serviceId"));
            $service=$this->moduleService->editService($request->get("serviceId"));
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
       $this->moduleService->updateService($data);
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
            $this->moduleService->deleteService($request->get("serviceId"));
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
