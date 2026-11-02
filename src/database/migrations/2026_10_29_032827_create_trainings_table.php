<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();

            // Informasi pelatihan
            $table->string('title', 150);
            $table->string('category', 100)->nullable();
            $table->string('organizer', 150)->nullable();
            $table->string('instructor', 150)->nullable();
            $table->string('location')->nullable();

            // Jadwal pelatihan
            $table->date('start_date');
            $table->date('end_date');

            // Kuota peserta; null berarti tidak ditentukan
            $table->unsignedSmallInteger('quota')->nullable();

            // planned, in_progress, completed, cancelled
            $table->enum('status', [
                'planned',
                'in_progress',
                'completed',
                'cancelled',
            ])->default('planned');

            $table->text('description')->nullable();

            // Pengguna yang membuat data pelatihan
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['status', 'start_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};