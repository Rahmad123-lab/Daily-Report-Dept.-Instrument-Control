<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competency_training', function (Blueprint $table) {
            $table->id();

            // Competency reference
            $table->foreignId('competency_id')
                ->constrained('competencies')
                ->cascadeOnDelete();

            // Training reference
            $table->foreignId('training_id')
                ->constrained('trainings')
                ->cascadeOnDelete();

            $table->timestamps();

            // Prevent duplicate competency-training mapping
            $table->unique(
                ['competency_id', 'training_id'],
                'competency_training_unique'
            );

            // Optimize reverse lookup from training
            $table->index('training_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competency_training');
    }
};
