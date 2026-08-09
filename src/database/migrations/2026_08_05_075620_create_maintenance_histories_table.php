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
        Schema::create('maintenance_histories', function (Blueprint $table) {

            $table->id();

            // Equipment
            $table->foreignId('equipment_id')
                ->constrained('equipments')
                ->cascadeOnDelete();

            // Work Information
            $table->string('work_order_number')->nullable();

            $table->enum('maintenance_type', [
                'Preventive',
                'Corrective',
                'Calibration',
                'Inspection',
                'Breakdown',
            ]);

            $table->date('maintenance_date');

            // Technician
            $table->string('technician')->nullable();

            // Duration (minutes)
            $table->integer('duration')->default(0);

            // Result
            $table->enum('result', [
                'Completed',
                'Pending',
                'Failed',
            ])->default('Pending');

            // Notes
            $table->text('notes')->nullable();

            // Creator
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            // Index
            $table->index('maintenance_date');
            $table->index('maintenance_type');
            $table->index('result');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_histories');
    }
};