<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_participants', function (Blueprint $table) {
            $table->id();

            // Relasi ke pelatihan
            $table->foreignId('training_id')
                ->constrained('trainings')
                ->cascadeOnDelete();

            // Relasi ke akun karyawan DRICS
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // registered, approved, rejected, cancelled
            $table->enum('registration_status', [
                'registered',
                'approved',
                'rejected',
                'cancelled',
            ])->default('registered');

            // not_marked, present, absent
            $table->enum('attendance_status', [
                'not_marked',
                'present',
                'absent',
            ])->default('not_marked');

            $table->text('notes')->nullable();

            $table->timestamps();

            // Satu akun hanya dapat terdaftar sekali pada pelatihan yang sama
            $table->unique(['training_id', 'user_id']);
            $table->index('registration_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_participants');
    }
};