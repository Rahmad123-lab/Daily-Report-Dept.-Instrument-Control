<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite tidak mendukung sintaks MODIFY ENUM milik MySQL.
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("
            ALTER TABLE daily_reports
            MODIFY status ENUM(
                'Draft',
                'Submitted',
                'Approved',
                'Rejected'
            ) DEFAULT 'Draft'
        ");
    }

    public function down(): void
    {
        // Tidak perlu mengubah skema SQLite untuk migration ini.
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("
            ALTER TABLE daily_reports
            MODIFY status ENUM(
                'Draft',
                'Published'
            ) DEFAULT 'Draft'
        ");
    }
};