<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rack_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rack_id')->constrained('racks');
            $table->foreignId('location_id')->constrained('locations');
            $table->datetime('entry_at')->nullable();
            $table->datetime('exit_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rack_locations');
    }
};
