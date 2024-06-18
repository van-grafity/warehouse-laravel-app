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
        Schema::create('fabric_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('api_fabric_request_id')->constrained('api_fabric_requests');
            $table->foreignId('last_sync_by')->nullable()->constrained('users');
            $table->datetime('last_sync_at')->nullable();
            $table->datetime('received_at')->nullable();
            $table->datetime('issued_at')->nullable();
            $table->datetime('relaxing_at')->nullable();
            $table->datetime('relaxed_at')->nullable();

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
        Schema::dropIfExists('fabric_requests');
    }
};
