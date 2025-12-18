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
        Schema::create('tuition_fee_payments', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            $table->foreignId('institution_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('admission_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('fee_structure_id')->constrained('fee_structures')->onDelete('cascade');

            // Payment Information
            $table->decimal('payment_amount', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->enum('payment_method', ['online', 'cash'])->default('cash');
            $table->string('transaction_id')->nullable();
            $table->date('payment_date');
            $table->string('receipt_number')->nullable()->unique();

            // Tuition Fee Specific Information
            $table->json('selected_months')->nullable(); // Array of month numbers (1-12)
            $table->decimal('monthly_fee_amount', 10, 2)->nullable(); // Monthly fee amount at time of payment
            $table->integer('number_of_months')->default(0); // Number of months paid

            // Status and Notes
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('completed');
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes for better query performance
            $table->index(['institution_id', 'payment_date']);
            $table->index(['student_id', 'payment_date']);
            $table->index(['admission_id', 'payment_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tuition_fee_payments');
    }
};
