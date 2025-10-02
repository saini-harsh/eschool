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
        Schema::table('subjects', function (Blueprint $table) {
            // Add composite unique constraint on code and institution_id
            // This allows same code across different institutions
            $table->unique(['code', 'institution_id'], 'subjects_code_institution_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Drop the composite unique constraint
            $table->dropUnique('subjects_code_institution_unique');
        });
    }
};
