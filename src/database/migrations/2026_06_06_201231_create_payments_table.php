<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\PaymentStatus;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->string('order_id')->unique();
            $table->string('transaction_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->integer('gross_amount');
            $table->enum('status', PaymentStatus::values())->default(PaymentStatus::PENDING->value);
            $table->json('payload')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            
            $table->index('order_id');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};