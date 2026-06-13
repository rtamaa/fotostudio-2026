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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();

            // =========================
            // USER ID (optional kalau login)
            // =========================
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained()
                  ->onDelete('set null');

            // =========================
            // DATA FEEDBACK
            // =========================
            $table->string('name')->nullable();
            $table->text('message');

            // =========================
            // STATUS (untuk admin panel nanti)
            // =========================
            $table->enum('status', ['new', 'read', 'archived'])
                  ->default('new');

            // =========================
            // NEW: PRIORITY SYSTEM (PRO FEATURE)
            // =========================
            $table->enum('priority', ['low', 'medium', 'high'])
                  ->default('low');

            // =========================
            // NEW: ADMIN RESPONSE (PRO FEATURE)
            // =========================
            $table->text('admin_reply')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};