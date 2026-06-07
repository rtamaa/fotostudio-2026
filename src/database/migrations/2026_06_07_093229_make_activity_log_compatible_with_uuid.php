<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Cek apakah tabel activity_log ada
        if (Schema::hasTable('activity_log')) {
            Schema::table('activity_log', function (Blueprint $table) {
                // Ubah subject_id dari integer menjadi string (UUID compatible)
                $table->string('subject_id', 255)->nullable()->change();
                // Ubah causer_id dari integer menjadi string (UUID compatible)
                $table->string('causer_id', 255)->nullable()->change();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('activity_log')) {
            Schema::table('activity_log', function (Blueprint $table) {
                // Kembalikan ke integer (warning: data bisa error)
                $table->integer('subject_id')->nullable()->change();
                $table->integer('causer_id')->nullable()->change();
            });
        }
    }
};