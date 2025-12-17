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
        Schema::table('institutions', function (Blueprint $table) {
            $table->string('razorpay_key_id')->nullable()->after('permissions');
            $table->string('razorpay_key_secret')->nullable()->after('razorpay_key_id');
            $table->string('razorpay_webhook_secret')->nullable()->after('razorpay_key_secret');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            $table->dropColumn([
                'razorpay_key_id',
                'razorpay_key_secret',
                'razorpay_webhook_secret',
            ]);
        });
    }
};
