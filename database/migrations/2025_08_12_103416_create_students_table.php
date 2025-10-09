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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('photo')->nullable();
            $table->string('email')->unique();
            $table->string('phone', 20);
            $table->date('dob');
            $table->string('gender');
            $table->string('address');
            $table->string('pincode', 10);
            $table->string('caste_tribe')->nullable();
            $table->string('district');
            $table->string('institution_code');
            $table->foreignId('teacher_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('institution_id')->constrained()->onDelete('cascade');
            $table->string('class_id')->nullable();
            $table->string('section_id')->nullable();
            $table->string('admin_id')->constrained()->onDelete('cascade');
            $table->string('password');
            $table->string('decrypt_pw');
            $table->boolean('status')->default(1);
            
            // Academic Information
            $table->date('admission_date')->nullable();
            $table->string('admission_number', 50)->nullable();
            $table->string('roll_number', 50)->nullable();
            $table->string('group', 50)->nullable();
            
            // Personal Information
            $table->string('religion', 50)->nullable();
            $table->string('blood_group', 10)->nullable();
            $table->string('category', 50)->nullable();
            $table->string('height', 10)->nullable();
            $table->string('weight', 10)->nullable();
            $table->text('permanent_address')->nullable();
            
            // Parents Information
            $table->string('father_name', 255)->nullable();
            $table->string('father_occupation', 255)->nullable();
            $table->string('father_phone', 20)->nullable();
            $table->string('father_photo')->nullable();
            $table->string('mother_name', 255)->nullable();
            $table->string('mother_occupation', 255)->nullable();
            $table->string('mother_phone', 20)->nullable();
            $table->string('mother_photo')->nullable();
            
            // Guardian Information
            $table->string('guardian_name', 255)->nullable();
            $table->string('guardian_relation', 50)->nullable();
            $table->string('guardian_relation_text', 50)->nullable();
            $table->string('guardian_email', 255)->nullable();
            $table->string('guardian_phone', 20)->nullable();
            $table->string('guardian_occupation', 255)->nullable();
            $table->text('guardian_address')->nullable();
            $table->string('guardian_photo')->nullable();
            
            // Document Information
            $table->string('national_id', 50)->nullable();
            $table->string('birth_certificate_number', 50)->nullable();
            $table->string('bank_name', 255)->nullable();
            $table->string('bank_account_number', 50)->nullable();
            $table->string('ifsc_code', 20)->nullable();
            $table->text('additional_notes')->nullable();
            
            // Document Attachments
            $table->string('document_01_title', 255)->nullable();
            $table->string('document_02_title', 255)->nullable();
            $table->string('document_03_title', 255)->nullable();
            $table->string('document_04_title', 255)->nullable();
            $table->string('document_01_file')->nullable();
            $table->string('document_02_file')->nullable();
            $table->string('document_03_file')->nullable();
            $table->string('document_04_file')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
