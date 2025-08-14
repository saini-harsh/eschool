<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Institution;
use App\Models\Teacher;
use App\Models\Student;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
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



        // Institutions start
        $institutions = [
            [
                'name' => 'Green Valley School',
                'logo' => 'greenvalley.png',
                'address' => '123 Main Street',
                'pincode' => '110011',
                'established_date' => '2005-06-15',
                'board' => 'CBSE',
                'state' => 'Delhi',
                'district' => 'New Delhi',
                'email' => 'greenvalley@example.com',
                'website' => 'http://greenvalley.edu',
                'phone' => '9876543210',
            ],
            [
                'name' => 'Sunrise Public School',
                'logo' => 'sunrise.png',
                'address' => '456 Sunrise Road',
                'pincode' => '110022',
                'established_date' => '2010-09-10',
                'board' => 'ICSE',
                'state' => 'Delhi',
                'district' => 'South Delhi',
                'email' => 'sunrise@example.com',
                'website' => 'http://sunrise.edu',
                'phone' => '9876509876',
            ]
        ];

        foreach ($institutions as $inst) {
            Institution::create(array_merge($inst, [
                'admin_id' => 1,
                'password' => Hash::make('school123'),
                'decrypt_pw' => 'school123',
                'status' => 1
            ]));
        }
        // Institutions end

        // Teachers Start
        $teachers = [
            // Green Valley School
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
            // Sunrise Public School
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
            ]
        ];

        foreach ($teachers as $t) {
            Teacher::create(array_merge($t, [
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
        // Teachers End

        // Students Start
        $students = [
            // Rajesh's Students
            [
                'first_name' => 'Priya',
                'last_name' => 'Verma',
                'dob' => '2008-08-12',
                'email' => 'priya.rajesh@example.com',
                'phone' => '9876512340',
                'institution_code' => 'INS001',
                'teacher_id' => 1,
                'institution_id' => 1
            ],
            [
                'first_name' => 'Rohan',
                'last_name' => 'Kapoor',
                'dob' => '2009-11-05',
                'email' => 'rohan.rajesh@example.com',
                'phone' => '9876512341',
                'institution_code' => 'INS001',
                'teacher_id' => 1,
                'institution_id' => 1
            ],
            // Anita's Students
            [
                'first_name' => 'Simran',
                'last_name' => 'Gill',
                'dob' => '2007-03-14',
                'email' => 'simran.anita@example.com',
                'phone' => '9876512342',
                'institution_code' => 'INS001',
                'teacher_id' => 2,
                'institution_id' => 1
            ],
            [
                'first_name' => 'Aman',
                'last_name' => 'Yadav',
                'dob' => '2008-09-21',
                'email' => 'aman.anita@example.com',
                'phone' => '9876512343',
                'institution_code' => 'INS001',
                'teacher_id' => 2,
                'institution_id' => 1
            ],
            // Vikram's Students
            [
                'first_name' => 'Karan',
                'last_name' => 'Bhatia',
                'dob' => '2009-06-30',
                'email' => 'karan.vikram@example.com',
                'phone' => '9876512344',
                'institution_code' => 'INS002',
                'teacher_id' => 3,
                'institution_id' => 2
            ],
            [
                'first_name' => 'Tanya',
                'last_name' => 'Malhotra',
                'dob' => '2007-12-10',
                'email' => 'tanya.vikram@example.com',
                'phone' => '9876512345',
                'institution_code' => 'INS002',
                'teacher_id' => 3,
                'institution_id' => 2
            ],
            // Neha's Students
            [
                'first_name' => 'Ishaan',
                'last_name' => 'Rathore',
                'dob' => '2008-04-25',
                'email' => 'ishaan.neha@example.com',
                'phone' => '9876512346',
                'institution_code' => 'INS002',
                'teacher_id' => 4,
                'institution_id' => 2
            ],
            [
                'first_name' => 'Meera',
                'last_name' => 'Joshi',
                'dob' => '2009-10-17',
                'email' => 'meera.neha@example.com',
                'phone' => '9876512347',
                'institution_code' => 'INS002',
                'teacher_id' => 4,
                'institution_id' => 2
            ]
        ];

        foreach ($students as $s) {
            Student::create(array_merge($s, [
                'middle_name' => null,
                'photo' => null,
                'gender' => 'Male',
                'address' => 'Sample Student Address',
                'pincode' => '110001',
                'caste_tribe' => 'General',
                'district' => 'New Delhi',
                'admin_id' => 1,
                'password' => Hash::make('student123'),
                'decrypt_pw' => 'student123',
                'status' => 1
            ]));
        }
        // Students End
    }
}
