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
        Schema::table('outgoing_mails', function (Blueprint $table) {
            $table->string('attachment', 100)->nullable(); // lampiran
            $table->dropColumn('priority');
            $table->dropColumn('expected_actions');
            $table->dropForeign(['department_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('outgoing_mails', function (Blueprint $table) {
            $table->enum('priority', ['very urgent', 'urgent', 'confidential'])->nullable();
            $table->json('expected_actions')->nullable();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
        });
    }
};
