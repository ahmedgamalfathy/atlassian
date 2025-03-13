<?php

use App\Models\Clients\Client;
use App\Models\Services\Service;
use Illuminate\Support\Facades\Schema;
use App\Traits\CreatedUpdatedByMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    use CreatedUpdatedByMigration;
    /**
     * Run the migrations.
     */
    public function up(): void
    {//client_id ,service_id ,date, notes
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Client::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Service::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamp('date');
            $table->text('notes')->nullable();
            $this->CreatedUpdatedByRelationship($table);
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
