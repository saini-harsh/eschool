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
        Schema::create('email_sms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('institution_id')->nullable();
            $table->string('title');
            $table->text('description');
            $table->enum('send_through', ['email', 'sms', 'whatsapp']);
            $table->enum('recipient_type', ['group', 'individual', 'class']);
            $table->json('recipients');
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            
            $table->foreign('institution_id')->references('id')->on('institutions')->onDelete('cascade');
            $table->index('institution_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_sms');
    }
};
