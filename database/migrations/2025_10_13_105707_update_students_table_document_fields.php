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
        Schema::table('students', function (Blueprint $table) {
            // Add new document fields only if they don't exist
            if (!Schema::hasColumn('students', 'aadhaar_no')) {
                $table->string('aadhaar_no', 12)->nullable()->after('birth_certificate_number');
            }
            if (!Schema::hasColumn('students', 'aadhaar_front')) {
                $table->string('aadhaar_front')->nullable()->after('aadhaar_no');
            }
            if (!Schema::hasColumn('students', 'aadhaar_back')) {
                $table->string('aadhaar_back')->nullable()->after('aadhaar_front');
            }
            if (!Schema::hasColumn('students', 'pan_no')) {
                $table->string('pan_no', 10)->nullable()->after('aadhaar_back');
            }
            if (!Schema::hasColumn('students', 'pan_front')) {
                $table->string('pan_front')->nullable()->after('pan_no');
            }
            if (!Schema::hasColumn('students', 'pan_back')) {
                $table->string('pan_back')->nullable()->after('pan_front');
            }
            if (!Schema::hasColumn('students', 'pen_no')) {
                $table->string('pen_no', 50)->nullable()->after('pan_back');
            }
        });

        // Generate student_id for existing students if student_id column exists
        if (Schema::hasColumn('students', 'student_id')) {
            $students = \App\Models\Student::whereNull('student_id')->get();
            foreach ($students as $student) {
                $student->student_id = \App\Models\Student::generateStudentId();
                $student->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Add back national_id field
            $table->string('national_id', 50)->nullable();
            
            // Remove new document fields
            $table->dropColumn([
                'student_id',
                'aadhaar_no',
                'aadhaar_front',
                'aadhaar_back',
                'pan_no',
                'pan_front',
                'pan_back',
                'pen_no'
            ]);
        });
    }
};
