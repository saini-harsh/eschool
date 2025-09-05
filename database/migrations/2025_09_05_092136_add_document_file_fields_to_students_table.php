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
            $table->string('document_01_file')->nullable();
            $table->string('document_02_file')->nullable();
            $table->string('document_03_file')->nullable();
            $table->string('document_04_file')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['document_01_file', 'document_02_file', 'document_03_file', 'document_04_file']);
        });
    }
};
