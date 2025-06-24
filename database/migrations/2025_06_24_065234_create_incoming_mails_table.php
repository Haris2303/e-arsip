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
        Schema::create('incoming_mails', function (Blueprint $table) {
            $table->id();
            $table->string('mail_number');
            $table->date('mail_date');
            $table->date('received_date');
            $table->string('sender');
            $table->string('agenda_number')->nullable();
            $table->string('subject');
            $table->enum('priority', ['very urgent', 'urgent', 'confidential'])->nullable();
            $table->text('notes')->nullable();
            $table->string('file_path')->nullable();
            $table->enum('status', ['active', 'archived'])->default('active');
            $table->timestamps();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoming_mails');
    }
};
