<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employee_competency_assessments', function (Blueprint $table) {
            $table->string('assessor_name', 150)
                ->nullable()
                ->after('assessed_by');
        });
    }

    public function down(): void
    {
        Schema::table('employee_competency_assessments', function (Blueprint $table) {
            $table->dropColumn('assessor_name');
        });
    }
};