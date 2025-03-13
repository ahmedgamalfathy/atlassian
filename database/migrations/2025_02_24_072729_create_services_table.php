<?php

use App\Models\User;
use App\Models\Services\Service;
use App\Models\Schedules\Schedule;
use App\Enums\Services\ServiceActive;
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
        //schedule_id, is_active ,title ,color ,description ,image

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Schedule::class)->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->boolean('is_active')->default(ServiceActive::INACTIVE->value);
            // $table->string('image')->nullable();
            $table->string( 'title');
            $table->string('color');
            $table->text('description');
            $this->CreatedUpdatedByRelationship($table);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
