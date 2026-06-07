<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\BookingStatus;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_id')->constrained()->onDelete('restrict');
            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->text('special_request')->nullable();
            $table->enum('booking_status', BookingStatus::values())->default(BookingStatus::DRAFT->value);
            $table->string('snap_token')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['booking_date', 'start_time', 'end_time']);
            $table->index('booking_status');
            $table->index('user_id');
            $table->index(['booking_date', 'start_time', 'booking_status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};