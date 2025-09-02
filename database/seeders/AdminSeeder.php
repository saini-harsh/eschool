<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'Super Admin',
            'logo' => 'admin_logo.png',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'setservices' => json_encode([]),
            'password' => Hash::make('admin'),
            'decrypt_pw' => 'admin',
            'status' => 1
        ]);
    }
}
