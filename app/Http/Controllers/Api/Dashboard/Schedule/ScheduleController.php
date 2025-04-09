<?php

namespace App\Http\Controllers\Api\Dashboard\Schedule;

use App\Http\Resources\Schedule\ScheduleEditResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Utils\PaginateCollection;
use App\Http\Controllers\Controller;
use App\Http\Requests\Schedule\CreateScheduleRequest;
use App\Http\Requests\Schedule\UpdateScheduleRequest;
use App\Http\Resources\Schedule\ScheduleResourceCollection;
use App\Services\Schedule\ScheduleService;

class ScheduleController extends Controller
{
    public $scheduleService;
    public function __construct(ScheduleService $scheduleService)
    {
        //all_scheule,create_scheule,edit_scheule,update_scheule,delete_scheule
        $this->middleware('auth:api');
        $this->middleware('permission:all_scheule', ['only' => ['index']]);
        $this->middleware('permission:create_scheule', ['only' => ['create']]);
        $this->middleware('permission:edit_scheule', ['only' => ['edit']]);
        $this->middleware('permission:update_scheule', ['only' => ['update']]);
        $this->middleware('permission:delete_scheule', ['only' => ['delete']]);
        $this->scheduleService=$scheduleService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $schedules =$this->scheduleService->allSchedule();
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
            $data= $createScheduleRequest->validated();
            $this->scheduleService->createSchedule($data);
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
            $schedule=$this->scheduleService->editSchedule($request->scheduleId);
            return response()->json([
            new ScheduleEditResource($schedule)
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
            $data=$updateScheduleRequest->validated();
            $this->scheduleService->updateSchedule($data);
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
            $this->scheduleService->deleteSchedule($request->scheduleId);
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
