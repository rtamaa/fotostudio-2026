<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('studio_blocks', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('reason', 255);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->boolean('is_recurring')->default(false);
            $table->timestamps();
            
            $table->unique(['date', 'start_time', 'end_time']);
            $table->index(['date', 'start_time', 'end_time']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('studio_blocks');
    }
};