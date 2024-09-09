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
        Schema::table('workouts', function (Blueprint $table) {
            $table->integer('ride_time')->nullable();
            $table->integer('total_time')->nullable();
            $table->integer('avg_speed')->nullable();
            $table->integer('max_speed')->nullable();
            $table->integer('avg_hr')->nullable();
            $table->integer('max_hr')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workouts', function (Blueprint $table) {
            $table->dropColumn('labels');
            $table->dropColumn('distance');
            $table->dropColumn('ride_time');
            $table->dropColumn('total_time');
            $table->dropColumn('avg_speed');
            $table->dropColumn('max_speed');
            $table->dropColumn('avg_hr');
            $table->dropColumn('max_hr');
        });
    }
};
