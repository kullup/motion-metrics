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
        Schema::create('workouts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('user_id');
            $table->string('attachment');
            $table->string('mimetype');
            $table->float('distance')->nullable();
            $table->integer('duration')->nullable();
            $table->float('pace')->nullable();
            $table->integer('heart_rate')->nullable();
            $table->float('elevation_gain')->nullable();
            $table->date('date')->nullable();
            $table->json('trackpoints_heart_rate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workouts');
    }
};
