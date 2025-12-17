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
        // Add fields to students table
        Schema::table('students', function (Blueprint $table) {
            $table->string('barcode', 100)->nullable()->unique()->after('admission_number');
            $table->text('qr_code')->nullable()->after('barcode');
            $table->string('biometric_id', 100)->nullable()->unique()->after('qr_code');
        });

        // Add fields to teachers table
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('barcode', 100)->nullable()->unique()->after('employee_id');
            $table->text('qr_code')->nullable()->after('barcode');
            $table->string('biometric_id', 100)->nullable()->unique()->after('qr_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['barcode', 'qr_code', 'biometric_id']);
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn(['barcode', 'qr_code', 'biometric_id']);
        });
    }
};
