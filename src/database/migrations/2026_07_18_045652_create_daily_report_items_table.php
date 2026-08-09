<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_report_items', function (Blueprint $table) {

            $table->id();

            $table->foreignId('daily_report_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('category',[
                'Perintah Atasan',
                'Pekerjaan Rutin',
                'Buku Cacat',
                'Preventive',
                'Corrective',
                'Emergency'
            ]);

            $table->string('title');

            $table->longText('description');

            $table->string('spk_number')->nullable();

            /*
            Baru
            */

            $table->string('dtk_number')->nullable();

            $table->date('start_date')->nullable();

            $table->date('end_date')->nullable();

            $table->tinyInteger('progress')->default(0);

            $table->boolean('is_continue')->default(false);

            $table->enum('status',[
                'Open',
                'Progress',
                'Done'
            ])->default('Done');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_report_items');
    }
};