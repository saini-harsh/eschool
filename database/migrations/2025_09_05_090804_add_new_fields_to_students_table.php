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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'admission_date', 'admission_number', 'roll_number', 'group',
                'religion', 'blood_group', 'category', 'height', 'weight', 'permanent_address',
                'father_name', 'father_occupation', 'father_phone', 'father_photo',
                'mother_name', 'mother_occupation', 'mother_phone', 'mother_photo',
                'guardian_name', 'guardian_relation', 'guardian_relation_text', 'guardian_email',
                'guardian_phone', 'guardian_occupation', 'guardian_address', 'guardian_photo',
                'national_id', 'birth_certificate_number', 'bank_name', 'bank_account_number',
                'ifsc_code', 'additional_notes', 'document_01_title', 'document_02_title',
                'document_03_title', 'document_04_title'
            ]);
        });
    }
};
