<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_competencies', function (Blueprint $table) {
            $table->id();

            // Employee reference
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Competency reference
            $table->foreignId('competency_id')
                ->constrained('competencies')
                ->restrictOnDelete();

            // Actual competency level (1-5)
            // NULL means not yet assessed.
            $table->unsignedTinyInteger('current_level')->nullable();

            // Expected competency level (1-5)
            $table->unsignedTinyInteger('target_level');

            // Assessment information
            $table->foreignId('assessed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->date('assessed_at')->nullable();

            // Evidence and assessment notes
            $table->text('evidence')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            // One current competency record per employee and competency
            $table->unique(
                ['user_id', 'competency_id'],
                'employee_competencies_user_competency_unique'
            );

            $table->index(['competency_id', 'current_level']);
            $table->index('assessed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_competencies');
    }
};