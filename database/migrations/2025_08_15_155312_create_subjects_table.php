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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('type');
            $table->boolean('status')->default(true);
            $table->foreignId('institution_id')->constrained()->onDelete('cascade'); // Assuming subjects belong to an institution
            $table->foreignId('class_id')->nullable();
            $table->timestamps();
            
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
        Schema::dropIfExists('subjects');
    }
};
