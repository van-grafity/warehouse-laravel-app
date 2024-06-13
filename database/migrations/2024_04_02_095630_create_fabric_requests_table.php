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
            $table->integer('fbr_id')->unique();
            $table->string('fbr_serial_number');
            $table->integer('fbr_status_print');
            $table->text('fbr_remark')->nullable();
            $table->datetime('fbr_created_at');
            $table->datetime('fbr_updated_at');
            $table->integer('laying_planning_id');
            $table->string('laying_planning_serial_number');
            $table->string('style');
            $table->string('fabric_type');
            $table->string('fabric_po');
            $table->integer('laying_planning_detail_id');
            $table->string('gl_number', 50);
            $table->string('color');
            $table->integer('table_number');
            $table->double('qty_required');
            $table->foreignId('last_sync_by')->nullable()->constrained('users');
            $table->datetime('last_sync_at')->nullable();
            $table->datetime('issued_at')->nullable();

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
