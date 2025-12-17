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
        Schema::table('teachers', function (Blueprint $table) {
            $table->decimal('salary', 12, 2)->nullable()->after('status');
            $table->foreignId('salary_structure_id')->nullable()->after('salary')->constrained()->onDelete('set null');
            $table->string('bank_account_number')->nullable()->after('salary_structure_id');
            $table->string('bank_ifsc_code')->nullable()->after('bank_account_number');
            $table->string('bank_name')->nullable()->after('bank_ifsc_code');
            $table->string('bank_branch')->nullable()->after('bank_name');
            $table->string('razorpay_contact_id')->nullable()->after('bank_branch');
            $table->string('razorpay_fund_account_id')->nullable()->after('razorpay_contact_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropForeign(['salary_structure_id']);
            $table->dropColumn([
                'salary',
                'salary_structure_id',
                'bank_account_number',
                'bank_ifsc_code',
                'bank_name',
                'bank_branch',
                'razorpay_contact_id',
                'razorpay_fund_account_id',
            ]);
        });
    }
};
