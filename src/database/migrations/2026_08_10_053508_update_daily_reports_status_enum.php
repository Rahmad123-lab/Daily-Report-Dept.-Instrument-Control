<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
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
        DB::statement("
            ALTER TABLE daily_reports
            MODIFY status ENUM(
                'Draft',
                'Published'
            ) DEFAULT 'Draft'
        ");
    }
};