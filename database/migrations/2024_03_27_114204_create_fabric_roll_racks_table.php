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
        Schema::create('fabric_roll_racks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fabric_roll_id')->constrained('fabric_rolls');
            $table->foreignId('rack_id')->constrained('racks');
            $table->datetime('stock_in_at');
            $table->foreignId('stock_in_by')->nullable()->constrained('users');
            $table->datetime('stock_out_at')->nullable();
            $table->foreignId('stock_out_by')->nullable()->constrained('users');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fabric_roll_racks');
    }
};
