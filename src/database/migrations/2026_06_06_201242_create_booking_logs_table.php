<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('booking_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 50);
            $table->text('description');
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            
            $table->index('booking_id');
            $table->index('action');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking_logs');
    }
};