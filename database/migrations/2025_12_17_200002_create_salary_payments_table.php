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
        Schema::create('salary_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->onDelete('cascade');
            $table->string('payee_type'); // 'teacher' or 'staff'
            $table->unsignedBigInteger('payee_id');
            $table->foreignId('salary_structure_id')->nullable()->constrained()->onDelete('set null');
            $table->unsignedTinyInteger('month'); // 1-12
            $table->unsignedSmallInteger('year');
            $table->decimal('base_salary', 12, 2);
            $table->decimal('total_earnings', 12, 2)->default(0);
            $table->decimal('total_deductions', 12, 2)->default(0);
            $table->decimal('net_salary', 12, 2);
            $table->enum('payment_method', ['razorpay', 'bank_transfer', 'cash', 'cheque'])->default('razorpay');
            $table->string('razorpay_payout_id')->nullable();
            $table->string('razorpay_fund_account_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->enum('status', ['pending', 'processing', 'paid', 'failed'])->default('pending');
            $table->date('payment_date')->nullable();
            $table->text('failure_reason')->nullable();
            $table->text('notes')->nullable();
            $table->json('salary_breakdown')->nullable(); // Store component-wise breakdown
            $table->timestamps();

            $table->index(['payee_type', 'payee_id']);
            $table->index(['month', 'year']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_payments');
    }
};
