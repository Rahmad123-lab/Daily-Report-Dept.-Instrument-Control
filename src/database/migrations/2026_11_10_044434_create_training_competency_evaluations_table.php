<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_competency_evaluations', function (Blueprint $table) {
            $table->id();

            // Training participant reference.
            // Keep evaluation history when participant deletion is attempted.
            $table->foreignId('training_participant_id')
                ->constrained('training_participants')
                ->restrictOnDelete();

            // Preserve the current competency assignment reference.
            $table->foreignId('employee_competency_id')
                ->nullable()
                ->constrained('employee_competencies')
                ->nullOnDelete();

            // References can become null while snapshots remain available.
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

            // Historical snapshots.
            $table->string('training_title', 150);
            $table->string('employee_name', 150);
            $table->string('competency_code', 50);
            $table->string('competency_name', 150);
            $table->string('competency_category', 100)->nullable();

            // Competency levels before and after training.
            $table->unsignedTinyInteger('pre_training_level')->nullable();
            $table->unsignedTinyInteger('post_training_level');
            $table->unsignedTinyInteger('target_level');

            // Evaluation details.
            $table->date('assessed_at');
            $table->text('evidence')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            // One evaluation per participant and mapped competency.
            $table->unique(
                ['training_participant_id', 'competency_id'],
                'training_competency_evaluations_participant_competency_unique'
            );

            $table->index(
                ['user_id', 'competency_id', 'assessed_at'],
                'training_competency_evaluations_employee_date_index'
            );

            $table->index('assessed_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_competency_evaluations');
    }
};
