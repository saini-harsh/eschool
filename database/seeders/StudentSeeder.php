<?php

namespace Database\Seeders;

use App\Models\Student;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = [
            // Green Valley School Students (institution_id = 1)
            // Rajesh's Students (teacher_id = 1)
            [
                'first_name' => 'Priya',
                'last_name' => 'Verma',
                'dob' => '2008-08-12',
                'email' => 'priya.rajesh@example.com',
                'phone' => '9876512340',
                'institution_code' => 'INS001',
                'teacher_id' => 1,
                'institution_id' => 1,
                'class_id' => 1, // Class 1 for Green Valley
                'section_id' => 2
            ],
            [
                'first_name' => 'Rohan',
                'last_name' => 'Kapoor',
                'dob' => '2009-11-05',
                'email' => 'rohan.rajesh@example.com',
                'phone' => '9876512341',
                'institution_code' => 'INS001',
                'teacher_id' => 1,
                'institution_id' => 1,
                'class_id' => 2, // Class 2 for Green Valley
                'section_id' => 3
            ],
            // Anita's Students (teacher_id = 2)
            [
                'first_name' => 'Simran',
                'last_name' => 'Gill',
                'dob' => '2007-03-14',
                'email' => 'simran.anita@example.com',
                'phone' => '9876512342',
                'institution_code' => 'INS001',
                'teacher_id' => 2,
                'institution_id' => 1,
                'class_id' => 2, // Class 2 for Green Valley
                'section_id' => 3
            ],
            [
                'first_name' => 'Aman',
                'last_name' => 'Yadav',
                'dob' => '2008-09-21',
                'email' => 'aman.anita@example.com',
                'phone' => '9876512343',
                'institution_code' => 'INS001',
                'teacher_id' => 2,
                'institution_id' => 1,
                'class_id' => 3, // Class 3 for Green Valley
                'section_id' => 4
            ],

            // Sunrise Public School Students (institution_id = 2)
            // Vikram's Students (teacher_id = 3)
            [
                'first_name' => 'Karan',
                'last_name' => 'Bhatia',
                'dob' => '2009-06-30',
                'email' => 'karan.vikram@example.com',
                'phone' => '9876512344',
                'institution_code' => 'INS002',
                'teacher_id' => 3,
                'institution_id' => 2,
                'class_id' => 13, // Class 1 for Sunrise (13th class created)
                'section_id' => 2
            ],
            [
                'first_name' => 'Tanya',
                'last_name' => 'Malhotra',
                'dob' => '2007-12-10',
                'email' => 'tanya.vikram@example.com',
                'phone' => '9876512345',
                'institution_code' => 'INS002',
                'teacher_id' => 3,
                'institution_id' => 2,
                'class_id' => 14, // Class 2 for Sunrise (14th class created)
                'section_id' => 3
            ],
            // Neha's Students (teacher_id = 4)
            [
                'first_name' => 'Ishaan',
                'last_name' => 'Rathore',
                'dob' => '2008-04-25',
                'email' => 'ishaan.neha@example.com',
                'phone' => '9876512346',
                'institution_code' => 'INS002',
                'teacher_id' => 4,
                'institution_id' => 2,
                'class_id' => 13, // Class 1 for Sunrise (13th class created)
                'section_id' => 2
            ],
            [
                'first_name' => 'Meera',
                'last_name' => 'Joshi',
                'dob' => '2009-10-17',
                'email' => 'meera.neha@example.com',
                'phone' => '9876512347',
                'institution_code' => 'INS002',
                'teacher_id' => 4,
                'institution_id' => 2,
                'class_id' => 14, // Class 2 for Sunrise (14th class created)
                'section_id' => 3
            ]
        ];

        foreach ($students as $student) {
            Student::create(array_merge($student, [
                'middle_name' => null,
                'photo' => "admin/uploads/students/1755236487_689ec887dc5d6.jpeg",
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
    }
}
