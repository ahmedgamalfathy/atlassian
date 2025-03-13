<?php

use App\Models\Clients\ClientPhone;
use Illuminate\Support\Facades\Schema;
use App\Models\Reservations\Reservation;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservation_phone', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Reservation::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(ClientPhone::class,"phone_id")->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_phone');
    }
};
