<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Hapus view jika ada
        DB::statement('DROP VIEW IF EXISTS combined_mails');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            CREATE VIEW combined_mails AS
            SELECT
                'Masuk' AS type,
                id,
                mail_number,
                subject,
                priority,
                created_at
            FROM incoming_mails
            WHERE status = 'archived'
            UNION ALL
            SELECT
                'Keluar' AS type,
                id,
                mail_number,
                subject,
                priority,
                created_at
            FROM outgoing_mails
            WHERE status = 'archived'
        ");
    }
};
