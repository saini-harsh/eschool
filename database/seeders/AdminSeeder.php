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
        // Check if admin already exists
        if (!Admin::where('email', 'admin@gmail.com')->exists()) {
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
            $this->command->info('Admin created successfully!');
        } else {
            $this->command->info('Admin already exists, skipping...');
        }
    }
}
