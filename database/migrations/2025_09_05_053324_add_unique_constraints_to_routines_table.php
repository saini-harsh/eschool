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
        Schema::table('routines', function (Blueprint $table) {
            // Add unique constraint to prevent duplicate routines for same class, section, day, and time
            $table->unique(['class_id', 'section_id', 'day', 'start_time'], 'unique_class_section_day_time');
            
            // Add unique constraint to prevent same teacher having overlapping time slots
            $table->unique(['teacher_id', 'day', 'start_time'], 'unique_teacher_day_time');
            
            // Add unique constraint to prevent same classroom being used at same time
            $table->unique(['class_room_id', 'day', 'start_time'], 'unique_room_day_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('routines', function (Blueprint $table) {
            $table->dropUnique('unique_class_section_day_time');
            $table->dropUnique('unique_teacher_day_time');
            $table->dropUnique('unique_room_day_time');
        });
    }
};