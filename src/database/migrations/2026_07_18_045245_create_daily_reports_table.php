<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_reports', function (Blueprint $table) {

            $table->id();

            $table->string('report_number')->unique();

            $table->date('report_date');

            $table->enum('shift', [
                'Non Shift',
                'Piket Malam',
            ]);

            $table->string('division')
                ->default('Instrument & Control System');

            /*
            |--------------------------------------------------------------------------
            | Attendance Summary
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('total_employee')->default(0);

            $table->unsignedInteger('present_employee')->default(0);

            $table->unsignedInteger('leave_employee')->default(0);

            $table->unsignedInteger('sick_employee')->default(0);

            $table->unsignedInteger('permission_employee')->default(0);

            /*
            |--------------------------------------------------------------------------
            | Night Duty
            |--------------------------------------------------------------------------
            */

            $table->foreignId('night_duty_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('notes')->nullable();

            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->enum('status',[
                'Draft',
                'Published'
            ])->default('Draft');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};