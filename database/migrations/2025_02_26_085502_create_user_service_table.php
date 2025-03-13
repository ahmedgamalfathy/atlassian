<?php

use App\Models\User;
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
    {
        Schema::create('user_service', function (Blueprint $table) {
                $table->id();
                $table->foreignIdFor(User::class)->constrained()->cascadeOnUpdate();
                $table->foreignIdFor(Service::class)->constrained()->cascadeOnUpdate();
                // $table->foreignId("user_id")->references("id")->on("users")->onUpdate("cascade")->onDelete("set null");
                // $table->foreignId("service_id")->references("id")->on("services")->onUpdate("cascade")->onDelete("set null");
                // $this->CreatedUpdatedByRelationship($table);
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_service');
    }
};
