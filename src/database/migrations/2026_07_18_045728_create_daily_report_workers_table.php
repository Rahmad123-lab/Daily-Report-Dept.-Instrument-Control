<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('daily_report_workers', function (Blueprint $table) {

        $table->id();

        $table->foreignId('daily_report_item_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->foreignId('user_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->timestamps();

    });
}

public function down(): void
{
    Schema::dropIfExists('daily_report_workers');
}
};
