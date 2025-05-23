<?php

use Illuminate\Http\Request;
use App\Models\Widget\Widget;
use App\Mail\ForgotPasswordSendCode;
use Illuminate\Support\Facades\Route;
use App\Models\Reservations\Reservation;
use App\Http\Controllers\FrontPages\DynamicPageController;
use App\Http\Controllers\Api\Dashboard\Auth\AuthController;
use App\Http\Controllers\Api\Dashboard\User\UserController;
use App\Http\Controllers\Api\Website\ServicePageController;
use App\Http\Controllers\Api\Dashboard\Email\EmailController;
use App\Http\Controllers\Api\Dashboard\Phone\PhoneController;
use App\Http\Controllers\Api\Dashboard\Client\ClientController;
use App\Http\Controllers\Api\Dashboard\Select\SelectController;
use App\Http\Controllers\Api\Dashboard\Widget\WidgetController;
use App\Http\Controllers\Api\Website\AppointmentPageController;
use App\Http\Controllers\Api\Dashboard\Services\ServiceController;
use App\Http\Controllers\Api\Dashboard\Schedule\ScheduleController;
use App\Http\Controllers\Api\Dashboard\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Dashboard\Client\ClientAddressController;
use App\Http\Controllers\Api\Dashboard\Reservation\ReservationController;
use App\Http\Controllers\Api\Website\FreeReservationScheduleWebsiteController;
use App\Http\Controllers\Api\Dashboard\Reservation\FreeReservationScheduleController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix("v1/{lang}/admin")->group(function (){
    Route::get('/widgets', [WidgetController::class,'widget']);
    Route::controller(AuthController::class)->group(function(){
         Route::post("login", "login");
         Route::post("logout", "logout");
    });
    Route::controller(ForgotPasswordController::class)->prefix('/forgotPassword')->group(function(){
        Route::post("sendCode","sendCodeEmail");
        Route::post('verifyCode','verifyCodeEmail');
        Route::post('resendCode','resendCode');
        Route::post('newPassword','newPassword');

    });
    Route::controller(ServiceController::class)->prefix("/services")->group(function(){
        Route::get("", "index");
        Route::post("create", "create");
        Route::get("edit", "edit");
        Route::put("update", "update");
        Route::delete("delete", "delete");
    });
    Route::controller(ScheduleController::class)->prefix("/schedules")->group(function(){
             Route::get("","index");
             Route::post("create", "create");
             Route::get("edit", "edit");
             Route::put("update", "update");
             Route::delete("delete", "delete");
    });
    Route::controller(UserController::class)->prefix("/users")->group(function(){
            Route::get("","index");
            Route::post("create", "create");
            Route::get("edit", "edit");
            Route::put("update", "update");
            Route::delete("delete", "delete");
            Route::post("change-status", "changeStatus");
    });
    Route::controller(ClientController::class)->prefix("/clients")->group(function(){
        Route::get("","index");
        Route::post("create","create");
        Route::get("edit","edit");
        Route::put("update", "update");
        Route::delete("delete", "delete");
    });
    Route::controller(ReservationController::class)->prefix("/reservations")->group(function(){
        Route::get("","index");
        Route::post("create","create");
        Route::get("edit","edit");
        Route::put("update", "update");
        Route::delete("delete", "delete");
    });
    Route::controller(EmailController::class)->prefix("/emails")->group(function(){
        Route::get("","index");
        Route::post("create","create");
        Route::get("edit","edit");
        Route::put("update", "update");
        Route::delete("delete", "delete");
    });
    Route::controller(ClientAddressController::class)->prefix("/address")->group(function(){
        Route::get("","index");
        Route::post("create","create");
        Route::get("edit","edit");
        Route::put("update", "update");
        Route::delete("delete", "delete");
    });
    Route::controller(PhoneController::class)->prefix("/phones")->group(function(){
        Route::get("","index");
        Route::post("create","create");
        Route::get("edit","edit");
        Route::put("update", "update");
        Route::delete("delete", "delete");
    });
    Route::controller(SelectController::class)->prefix('/selects')->group(function(){
        Route::get('', 'getSelects');
    });
    Route::controller(FreeReservationScheduleController::class)->group(function(){
        Route::get('/free-schedules','index');
        Route::get('free-schedules/check-availability','checkAvailability');
    });



});

Route::prefix("v1/{lang}/website")->group(function(){

    Route::post('appointment', [AppointmentPageController::class,'create']);
    Route::get('services',[ServicePageController::class,'index']);
    Route::controller(ServicePageController::class)->prefix('/services')->group(function(){
        Route::get('','index');
        Route::get('edit','edit');
    });
    Route::controller(FreeReservationScheduleWebsiteController::class)->group(function(){
        Route::get('/free-schedules','index');
        Route::get('free-schedules/check-availability','checkAvailability');
        Route::get('free-schedules/availability','getAvailableTimes');
    });
});

