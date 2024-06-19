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
        Schema::create('api_fabric_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('fbr_id')->unique();
            $table->string('fbr_serial_number');
            $table->integer('fbr_status_print');
            $table->text('fbr_remark')->nullable();
            $table->datetime('fbr_requested_at')->nullable();
            $table->string('fbr_requested_by')->nullable();
            $table->datetime('fbr_created_at');
            $table->datetime('fbr_updated_at');
            $table->integer('fbr_laying_planning_id');
            $table->string('fbr_laying_planning_serial_number');
            $table->string('fbr_style');
            $table->string('fbr_fabric_type');
            $table->string('fbr_fabric_po');
            $table->integer('fbr_laying_planning_detail_id');
            $table->string('fbr_gl_number', 50);
            $table->string('fbr_color');
            $table->integer('fbr_table_number');
            $table->double('fbr_qty_required');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_fabric_requests');
    }
};
