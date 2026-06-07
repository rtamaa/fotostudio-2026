<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('payment_proof')->nullable();
            $table->text('payment_notes')->nullable();
            $table->timestamp('payment_confirmed_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['payment_proof', 'payment_notes', 'payment_confirmed_at']);
        });
    }
};