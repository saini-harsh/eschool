<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('exam_marks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('institution_id');
            $table->unsignedBigInteger('exam_id')->nullable();
            $table->unsignedBigInteger('class_id')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('teacher_id');
            $table->decimal('marks_obtained', 5, 2)->default(0);
            $table->decimal('total_marks', 5, 2)->default(100);
            $table->decimal('pass_marks', 5, 2)->default(33);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_marks');
    }
};

