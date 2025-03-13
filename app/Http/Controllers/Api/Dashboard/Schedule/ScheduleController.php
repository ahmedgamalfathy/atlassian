<?php

namespace App\Http\Controllers\Api\Dashboard\Schedule;

use App\Http\Resources\Schedule\ScheduleEditResource;
use Illuminate\Http\Request;
use App\Models\Services\Service;
use App\Utils\PaginateCollection;
use App\Models\Schedules\Schedule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\Schedule\CreateScheduleRequest;
use App\Http\Requests\Schedule\UpdateScheduleRequest;
use App\Http\Resources\Schedule\ScheduleResourceCollection;

class ScheduleController extends Controller
{
    public function __construct()
    {
        //all_scheule,create_scheule,edit_scheule,update_scheule,delete_scheule
        $this->middleware('auth:api');
        $this->middleware('permission:all_scheule', ['only' => ['index']]);
        $this->middleware('permission:create_scheule', ['only' => ['create']]);
        $this->middleware('permission:edit_scheule', ['only' => ['edit']]);
        $this->middleware('permission:update_scheule', ['only' => ['update']]);
        $this->middleware('permission:delete_scheule', ['only' => ['delete']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $schedules = QueryBuilder::for(Schedule::class)
        ->allowedFilters('title')
        ->get();
      return response()->json([
        "data"=>new ScheduleResourceCollection(PaginateCollection::paginate($schedules, $request->pageSize?$request->pageSize:10))
      ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(CreateScheduleRequest $createScheduleRequest)
    {
        try {
            DB::beginTransaction();
            $schedule= Schedule::create([
                "title"=>$createScheduleRequest->post('title'),
                "times"=>$createScheduleRequest->times
                // "times"=>json_encode($createScheduleRequest->post('times'))
            ]);
            $servicesId = $createScheduleRequest->post('servicesId');
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
            DB::commit();
           return response()->json([
            "message"=>__('messages.success.created'),
           ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
               'message'=>__('messages.error'),
            ]);//throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function edit(Request $request)
    {
        try {
            $schedule=Schedule::with('services')->find($request->get('scheduleId'));
            return response()->json([
              "data"=> new ScheduleEditResource($schedule)
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
    public function update(UpdateScheduleRequest $updateScheduleRequest)
    {
        try {
            DB::beginTransaction();
            $schedule= Schedule::findOrFail($updateScheduleRequest->post('scheduleId'));
            $schedule->update([
                "title"=>$updateScheduleRequest->post('title'),
                "times"=>json_encode($updateScheduleRequest->post('times'))
            ]);
            $servicesId = $updateScheduleRequest->post('servicesId');
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
            DB::commit();
           return response()->json([
            "message"=>__('messages.success.updated'),
           ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
               'message'=>__('messages.error'),
            ]);//throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {
        try {
            $schedule=Schedule::findOrFail($request->get("scheduleId"));
            $schedule->delete();
            return response()->json([
             "message"=>__('messages.success.deleted')
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "message"=>__("messages.error.not_found"),
            ]);
        }

    }
}
