<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachers = [
            // Green Valley School (institution_id = 1)
            [
                'first_name' => 'Rajesh',
                'last_name' => 'Sharma',
                'dob' => '1985-03-20',
                'email' => 'rajesh.green@example.com',
                'phone' => '9876501234',
                'institution_code' => 'INS001',
                'gender' => 'Male',
                'institution_id' => 1,
                'profile_image' => "admin/uploads/teachers/1755163497_689dab69813d1.jpeg",
            ],
            [
                'first_name' => 'Anita',
                'last_name' => 'Mehra',
                'dob' => '1990-07-15',
                'email' => 'anita.green@example.com',
                'phone' => '9876505678',
                'institution_code' => 'INS001',
                'gender' => 'Female',
                'institution_id' => 1,
                'profile_image' => "admin/uploads/teachers/1755163512_689dab787633a.jpeg",
            ],
            
            // Sunrise Public School (institution_id = 2)
            [
                'first_name' => 'Vikram',
                'last_name' => 'Kumar',
                'dob' => '1988-01-10',
                'email' => 'vikram.sunrise@example.com',
                'phone' => '9876512345',
                'institution_code' => 'INS002',
                'gender' => 'Male',
                'institution_id' => 2,
                'profile_image' => "admin/uploads/teachers/1755163484_689dab5c7eb17.jpeg",
            ],
            [
                'first_name' => 'Neha',
                'last_name' => 'Singh',
                'dob' => '1992-05-25',
                'email' => 'neha.sunrise@example.com',
                'phone' => '9876516789',
                'institution_code' => 'INS002',
                'gender' => 'Female',
                'institution_id' => 2,
                'profile_image' => "admin/uploads/teachers/1755163497_689dab69813d1.jpeg",
            ],

            // Delhi International School (institution_id = 3)
            [
                'first_name' => 'Arun',
                'last_name' => 'Patel',
                'dob' => '1987-08-12',
                'email' => 'arun.international@example.com',
                'phone' => '9876544441',
                'institution_code' => 'INS003',
                'gender' => 'Male',
                'institution_id' => 3,
                'profile_image' => "admin/uploads/teachers/1755163484_689dab5c7eb17.jpeg",
            ],
            [
                'first_name' => 'Kavita',
                'last_name' => 'Reddy',
                'dob' => '1991-12-03',
                'email' => 'kavita.international@example.com',
                'phone' => '9876544442',
                'institution_code' => 'INS003',
                'gender' => 'Female',
                'institution_id' => 3,
                'profile_image' => "admin/uploads/teachers/1755163512_689dab787633a.jpeg",
            ],

            // Modern Public School (institution_id = 4)
            [
                'first_name' => 'Sanjay',
                'last_name' => 'Gupta',
                'dob' => '1986-06-18',
                'email' => 'sanjay.modern@example.com',
                'phone' => '9876555551',
                'institution_code' => 'INS004',
                'gender' => 'Male',
                'institution_id' => 4,
                'profile_image' => "admin/uploads/teachers/1755163484_689dab5c7eb17.jpeg",
            ],
            [
                'first_name' => 'Pooja',
                'last_name' => 'Sharma',
                'dob' => '1993-04-22',
                'email' => 'pooja.modern@example.com',
                'phone' => '9876555552',
                'institution_code' => 'INS004',
                'gender' => 'Female',
                'institution_id' => 4,
                'profile_image' => "admin/uploads/teachers/1755163512_689dab787633a.jpeg",
            ]
        ];

        foreach ($teachers as $teacher) {
            // Check if teacher already exists
            if (!Teacher::where('email', $teacher['email'])->exists()) {
                Teacher::create(array_merge($teacher, [
                    'middle_name' => null,
                    'address' => 'Sample Teacher Address',
                    'pincode' => '110001',
                    'caste_tribe' => 'General',
                    'admin_id' => 1,
                    'password' => Hash::make('teacher123'),
                    'decrypt_pw' => 'teacher123',
                    'status' => 1
                ]));
            }
        }
        $this->command->info('Teachers seeded successfully!');
    }
}
