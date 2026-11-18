<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_competency_assessments', function (Blueprint $table) {
            $table->id();

            // References to current employee competency and master data.
            // Historical rows remain when source records are deleted.
            $table->foreignId('employee_competency_id')
                ->nullable()
                ->constrained('employee_competencies')
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('competency_id')
                ->nullable()
                ->constrained('competencies')
                ->nullOnDelete();

            $table->foreignId('assessed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Snapshots preserve historical identity and competency details.
            $table->string('employee_name', 150);
            $table->string('competency_code', 50);
            $table->string('competency_name', 150);
            $table->string('competency_category', 100)->nullable();

            // Assessment result snapshot.
            $table->unsignedTinyInteger('current_level');
            $table->unsignedTinyInteger('target_level');

            $table->date('assessed_at');

            // Assessment evidence and remarks.
            $table->text('evidence')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(
                ['user_id', 'competency_id', 'assessed_at'],
                'employee_comp_assessment_history_index'
            );

            $table->index('assessed_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_competency_assessments');
    }
};