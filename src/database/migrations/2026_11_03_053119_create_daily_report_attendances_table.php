<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_report_attendances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('daily_report_id')
                ->constrained('daily_reports')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->enum('attendance_status', [
                'Present',
                'Leave',
                'Sick',
                'Permission',
            ]);

            $table->timestamps();

            $table->unique(
                ['daily_report_id', 'user_id'],
                'daily_report_attendance_unique'
            );

            $table->index(
                ['daily_report_id', 'attendance_status'],
                'daily_report_attendance_status_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_report_attendances');
    }
};
