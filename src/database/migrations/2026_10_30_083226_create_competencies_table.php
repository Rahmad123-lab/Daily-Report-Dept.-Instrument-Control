<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competencies', function (Blueprint $table) {
            $table->id();

            // Unique competency identifier
            $table->string('code', 50)->unique();

            // Competency information
            $table->string('name', 150);
            $table->string('category', 100);
            $table->text('description')->nullable();

            // Competency availability
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['category', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competencies');
    }
};