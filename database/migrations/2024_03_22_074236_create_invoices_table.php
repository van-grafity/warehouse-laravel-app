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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 100);
            $table->string('container_number', 50)->nullable();
            $table->date('incoming_date')->nullable();
            $table->date('offloaded_date')->nullable();
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->enum('flag_opened',['Y','N'])->default('N');
            $table->enum('flag_offloaded',['Y','N'])->default('N');
            $table->datetime('received_at')->nullable();
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
        Schema::dropIfExists('invoices');
    }
};
