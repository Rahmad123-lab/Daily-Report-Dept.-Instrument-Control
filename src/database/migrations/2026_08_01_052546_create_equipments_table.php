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
        Schema::create('equipments', function (Blueprint $table) {

            $table->id();

            // Informasi Utama
            $table->string('equipment_code')->unique();
            $table->string('equipment_name');
            $table->string('tag_number')->unique();

            // Kategori
            $table->enum('category', [
                'Instrument',
                'Valve',
                'Electrical',
                'Analyzer',
                'PLC',
                'DCS',
                'Motor',
                'Pump',
                'Tank',
                'Utility',
            ]);

            // Lokasi
            $table->string('location');

            // Vendor
            $table->string('manufacturer')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();

            // Instalasi
            $table->date('installation_date')->nullable();

            // Status Equipment
            $table->enum('status', [
                'Active',
                'Standby',
                'Maintenance',
                'Breakdown',
                'Decommission',
            ])->default('Active');

            // Keterangan
            $table->text('description')->nullable();

            $table->timestamps();

            // Index
            $table->index('equipment_code');
            $table->index('tag_number');
            $table->index('category');
            $table->index('location');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipments');
    }
};