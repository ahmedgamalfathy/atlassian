<?php

use App\Models\Clients\Client;
use Illuminate\Support\Facades\Schema;
use App\Models\Reservations\Reservation;
use App\Traits\CreatedUpdatedByMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    use CreatedUpdatedByMigration;
    /**
     * Run the migrations.
     */
     //email ,client_id
    public function up(): void
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->foreignIdFor(Client::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $this->CreatedUpdatedByRelationship($table);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emails');
    }
};
