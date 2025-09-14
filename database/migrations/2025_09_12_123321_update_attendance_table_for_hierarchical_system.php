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
        Schema::table('attendances', function (Blueprint $table) {
            // Add new columns for hierarchical attendance system
            $table->unsignedBigInteger('teacher_id')->nullable()->after('section_id');
            $table->unsignedBigInteger('marked_by')->nullable()->after('remarks');
            $table->string('marked_by_role')->nullable()->after('marked_by');
            $table->boolean('is_confirmed')->default(false)->after('marked_by_role');
            $table->unsignedBigInteger('confirmed_by')->nullable()->after('is_confirmed');
            $table->timestamp('confirmed_at')->nullable()->after('confirmed_by');
            
            // Add foreign key constraints
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
            $table->foreign('marked_by')->references('id')->on('teachers')->onDelete('set null');
            $table->foreign('confirmed_by')->references('id')->on('teachers')->onDelete('set null');
            
            // Add indexes for better performance
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
        Schema::table('attendances', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['teacher_id']);
            $table->dropForeign(['marked_by']);
            $table->dropForeign(['confirmed_by']);
            
            // Drop indexes
            $table->dropIndex(['institution_id', 'class_id', 'section_id', 'date']);
            $table->dropIndex(['role', 'date']);
            $table->dropIndex(['is_confirmed', 'date']);
            
            // Drop columns
            $table->dropColumn([
                'teacher_id',
                'marked_by',
                'marked_by_role',
                'is_confirmed',
                'confirmed_by',
                'confirmed_at'
            ]);
        });
    }
};
