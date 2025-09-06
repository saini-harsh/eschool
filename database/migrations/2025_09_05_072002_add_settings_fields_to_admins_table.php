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
        Schema::table('admins', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->text('address_line_1')->nullable()->after('phone');
            $table->text('address_line_2')->nullable()->after('address_line_1');
            $table->string('country')->nullable()->after('address_line_2');
            $table->string('state')->nullable()->after('country');
            $table->string('city')->nullable()->after('state');
            $table->string('pin_code')->nullable()->after('city');
            $table->string('company_name')->nullable()->after('pin_code');
            $table->string('company_url')->nullable()->after('company_name');
            $table->string('company_logo')->nullable()->after('company_url');
            $table->integer('allowance_days')->default(25)->after('company_logo');
            $table->date('year_start')->nullable()->after('allowance_days');
            $table->boolean('working_from_home')->default(true)->after('year_start');
            $table->json('working_days')->nullable()->after('working_from_home');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'address_line_1',
                'address_line_2',
                'country',
                'state',
                'city',
                'pin_code',
                'company_name',
                'company_url',
                'company_logo',
                'allowance_days',
                'year_start',
                'working_from_home',
                'working_days'
            ]);
        });
    }
};
