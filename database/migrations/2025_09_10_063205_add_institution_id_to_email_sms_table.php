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
        Schema::table('email_sms', function (Blueprint $table) {
            $table->unsignedBigInteger('institution_id')->nullable()->after('id');
            $table->foreign('institution_id')->references('id')->on('institutions')->onDelete('cascade');
            $table->index('institution_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('email_sms', function (Blueprint $table) {
            $table->dropForeign(['institution_id']);
            $table->dropIndex(['institution_id']);
            $table->dropColumn('institution_id');
        });
    }
};
