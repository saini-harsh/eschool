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
        Schema::table('payments', function (Blueprint $table) {
            // Make student_id nullable to allow payments during admission
            $table->foreignId('student_id')->nullable()->change();

            // Add admission_id to link payments made during admission
            $table->foreignId('admission_id')->nullable()->after('student_id')->constrained('admissions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Remove admission_id
            $table->dropForeign(['admission_id']);
            $table->dropColumn('admission_id');

            // Revert student_id to not nullable (if needed)
            // Note: This might fail if there are null values
            // $table->foreignId('student_id')->nullable(false)->change();
        });
    }
};
