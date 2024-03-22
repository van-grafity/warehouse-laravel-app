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
        Schema::create('packinglists', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number', 150)->unique();
            $table->foreignId('invoice_id')->constrained('invoices');
            $table->string('buyer');
            $table->string('gl_number', 20);
            $table->string('po_number', 50);
            $table->foreignId('color_id')->constrained('colors');
            $table->string('batch_number', 50);
            $table->string('style')->nullable();
            $table->string('fabric_content')->nullable();
            $table->text('remark')->nullable();
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
        Schema::dropIfExists('packinglists');
    }
};
