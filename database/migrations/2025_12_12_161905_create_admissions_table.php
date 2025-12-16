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
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();

            // Academic Information
            $table->foreignId('institution_id')->constrained()->onDelete('cascade');
            $table->string('institution_code');
            $table->date('admission_date')->nullable();
            $table->string('admission_number', 50)->nullable()->unique();
            $table->string('roll_number', 50)->nullable();
            $table->foreignId('class_id')->nullable()->constrained('classes')->onDelete('set null');
            $table->string('pen_no', 50)->nullable();

            // Previous Academic Information
            $table->string('previous_school_name', 255)->nullable();
            $table->text('previous_school_address')->nullable();
            $table->foreignId('previous_school_class')->nullable()->constrained('classes')->onDelete('set null');
            $table->string('previous_school_result', 255)->nullable();

            // Student Information
            $table->string('email', 255)->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable(); // Current address
            $table->string('pincode', 10)->nullable();
            $table->string('district', 100)->nullable();
            $table->text('permanent_address')->nullable();
            $table->string('permanent_pincode', 10)->nullable();
            $table->string('permanent_district', 100)->nullable();

            // Personal Information
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->date('dob')->nullable();
            $table->string('religion', 50)->nullable();
            $table->enum('caste_tribe', ['General', 'OBC', 'SC', 'ST', 'OTHERS'])->nullable();
            $table->string('photo')->nullable(); // File path for student photo

            // Medical Record
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable();
            $table->string('height', 10)->nullable();
            $table->string('weight', 10)->nullable();

            // Parents Information
            $table->string('father_name', 255)->nullable();
            $table->string('mother_name', 255)->nullable();
            $table->string('father_occupation', 255)->nullable();
            $table->string('father_phone', 20)->nullable();

            // Parents Documents
            $table->string('parent_aadhaar_front')->nullable(); // File path
            $table->string('parent_aadhaar_back')->nullable(); // File path

            // Guardian Information
            $table->string('guardian_name', 255)->nullable();
            $table->string('guardian_relation_text', 100)->nullable();
            $table->string('guardian_phone', 20)->nullable();
            $table->text('guardian_address')->nullable();

            // Guardian Documents
            $table->string('guardian_aadhaar_front')->nullable(); // File path
            $table->string('guardian_aadhaar_back')->nullable(); // File path

            // Student Aadhaar Card Information
            $table->string('aadhaar_no', 12)->nullable();
            $table->string('aadhaar_front')->nullable(); // File path
            $table->string('aadhaar_back')->nullable(); // File path

            // Other Documents
            $table->string('document_01_title', 255)->nullable();
            $table->string('document_01_file')->nullable(); // File path
            $table->string('document_02_title', 255)->nullable();
            $table->string('document_02_file')->nullable(); // File path
            $table->string('document_03_title', 255)->nullable();
            $table->string('document_03_file')->nullable(); // File path
            $table->string('document_04_title', 255)->nullable();
            $table->string('document_04_file')->nullable(); // File path

            // Payment Information
            $table->decimal('admission_fee_amount', 10, 2)->nullable();
            $table->enum('admission_payment_method', ['online', 'cash'])->nullable();
            $table->decimal('tuition_fee_amount', 10, 2)->nullable();
            $table->enum('tuition_payment_method', ['online', 'cash'])->nullable();
            $table->decimal('hostel_admission_fee_amount', 10, 2)->nullable();
            $table->enum('hostel_admission_payment_method', ['online', 'cash'])->nullable();
            $table->decimal('hostel_tuition_fee_amount', 10, 2)->nullable();
            $table->enum('hostel_tuition_payment_method', ['online', 'cash'])->nullable();

            // Status and Metadata
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('admission_status', ['pending', 'approved', 'rejected', 'admitted'])->default('pending');
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admissions');
    }
};
