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
        Schema::table('racks', function (Blueprint $table) {
            $table->foreignId('location_id')->constrained('locations');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema:: table('racks', function (Blueprint $table) {
            $table -> dropForeign(['location_id']);
            $table -> dropColumn(['location_id']);
        });
    }
};
