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
        Schema::create('fabric_rolls', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number', 100)->unique();
            $table->foreignId('packinglist_id')->constrained('packinglists');
            $table->string('roll_number', 150);
            $table->double('kgs')->nullable();
            $table->double('lbs')->nullable();
            $table->double('yds');
            $table->double('width')->nullable();
            $table->datetime('racked_at')->nullable();
            $table->foreignId('racked_by')->nullable()->constrained('users');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fabric_rolls');
    }
};
