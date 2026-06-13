<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();

            $table->string('name', 100);
            $table->string('slug', 100)->unique();

            // Dynamic display
            $table->string('icon')->nullable();
            $table->string('card_color')->nullable();
            $table->boolean('is_popular')->default(false);

            $table->text('description');
            $table->string('short_description')->nullable();

            $table->integer('duration');
            $table->integer('price');

            $table->integer('max_people')->default(4);

            $table->json('facilities')->nullable();

            $table->boolean('is_active')->default(true);

            $table->integer('sort_order')->default(0);

            $table->timestamps();

            $table->index('is_active');
            $table->index('price');
        });
    }

    public function down()
    {
        Schema::dropIfExists('packages');
    }
};