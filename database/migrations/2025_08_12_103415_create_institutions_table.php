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
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo')->nullable();
            $table->string('address');
            $table->string('pincode', 10);
            $table->date('established_date');
            $table->string('board');
            $table->string('state');
            $table->string('district');
            $table->string('email')->unique();
            $table->string('website')->nullable();
            $table->string('phone', 20);
            $table->foreignId('admin_id')->constrained()->onDelete('cascade');
            $table->string('password');
            $table->string('decrypt_pw');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};
