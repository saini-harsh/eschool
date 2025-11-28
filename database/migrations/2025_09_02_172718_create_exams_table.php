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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('institution_id');
            $table->unsignedBigInteger('exam_type_id');
            $table->string('title');
            $table->string('code');
            $table->unsignedBigInteger('class_id')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedTinyInteger('month')->comment('1-12 representing exam month');
            $table->time('morning_time')->nullable();
            $table->time('evening_time')->nullable();
            $table->text('subject_dates')->nullable(); // JSON or serialized data for subjects
            $table->text('morning_subjects')->nullable(); // JSON or serialized data for subjects
            $table->text('evening_subjects')->nullable(); // JSON or serialized data for subjects
            $table->timestamps();

            // Foreign keys (optional, remove if not needed)
            $table->foreign('institution_id')->references('id')->on('institutions')->onDelete('cascade');
            // $table->foreign('exam_type_id')->references('id')->on('exam_types')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('set null');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
