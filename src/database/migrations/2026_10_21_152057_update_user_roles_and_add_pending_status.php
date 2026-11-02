<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
    if (DB::getDriverName() === 'sqlite') {
    Schema::table('users', function (Blueprint $table) {
        $table->string('role')
            ->default('teknisi')
            ->change();

        $table->string('status')
            ->default('active')
            ->change();
    });

    return;
}

        DB::statement("
            ALTER TABLE users
            MODIFY COLUMN role
            ENUM(
                'admin',
                'teknisi',
                'karyawan',
                'koordinator',
                'leader',
                'supervisor',
                'kepala_departemen',
                'kepala_divisi',
                'k3'
            ) NOT NULL DEFAULT 'teknisi'
        ");

        DB::statement("
            ALTER TABLE users
            MODIFY COLUMN status
            ENUM('pending', 'active', 'inactive')
            NOT NULL DEFAULT 'active'
        ");
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::table('users')
            ->whereIn('role', [
                'karyawan',
                'koordinator',
                'leader',
                'kepala_departemen',
                'kepala_divisi',
            ])
            ->update(['role' => 'teknisi']);

        DB::table('users')
            ->where('status', 'pending')
            ->update(['status' => 'inactive']);

        DB::statement("
            ALTER TABLE users
            MODIFY COLUMN role
            ENUM('admin', 'supervisor', 'teknisi', 'k3')
            NOT NULL DEFAULT 'teknisi'
        ");

        DB::statement("
            ALTER TABLE users
            MODIFY COLUMN status
            ENUM('active', 'inactive')
            NOT NULL DEFAULT 'active'
        ");
    }
};
