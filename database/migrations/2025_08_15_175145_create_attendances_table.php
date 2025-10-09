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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('role');
            $table->unsignedBigInteger('institution_id');
            $table->foreignId('class_id')->nullable()->constrained('classes')->onDelete('cascade');
            $table->foreignId('section_id')->nullable()->constrained('sections')->onDelete('cascade');
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'late', 'excused'])->default('present');
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('marked_by')->nullable();
            $table->string('marked_by_role')->nullable();
            $table->boolean('is_confirmed')->default(false);
            $table->unsignedBigInteger('confirmed_by')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();

            // Foreign keys
            // Note: user_id can reference students, teachers, or admins (all extend Authenticatable)
            $table->foreign('institution_id')->references('id')->on('institutions')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
            $table->foreign('marked_by')->references('id')->on('teachers')->onDelete('set null');
            $table->foreign('confirmed_by')->references('id')->on('teachers')->onDelete('set null');
            
            // Indexes for better performance
            $table->index(['institution_id', 'class_id', 'section_id', 'date']);
            $table->index(['role', 'date']);
            $table->index(['is_confirmed', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
