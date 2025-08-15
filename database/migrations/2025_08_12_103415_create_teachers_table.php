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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('dob');
            $table->string('email')->unique();
            $table->string('phone', 20);
            $table->string('profile_image')->nullable();
            $table->string('address');
            $table->string('pincode', 10);
            $table->string('institution_code');
            $table->string('gender');
            $table->string('caste_tribe')->nullable();
            $table->foreignId('institution_id')->constrained()->onDelete('cascade');
            $table->foreignId('admin_id')->constrained()->onDelete('cascade');
            $table->string('password');
            $table->string('decrypt_pw');
            $table->boolean('status')->default(1);
            $table->string('employee_id')->unique()->nullable(); // Teacher employee ID
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
