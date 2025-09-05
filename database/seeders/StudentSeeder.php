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
                'middle_name' => 'Kumari',
                'last_name' => 'Verma',
                'dob' => '2008-08-12',
                'email' => 'priya.rajesh@example.com',
                'phone' => '9876512340',
                'gender' => 'Female',
                'institution_code' => 'INS001',
                'teacher_id' => 1,
                'institution_id' => 1,
                'class_id' => 1, // Class 1 for Green Valley
                'section_id' => 2,
                'address' => '123 Green Park, New Delhi',
                'pincode' => '110016',
                'district' => 'New Delhi',
                'caste_tribe' => 'General',
                'category' => 'General',
                'blood_group' => 'A+',
                'admission_date' => '2024-04-01',
                'father_name' => 'Mr. Rajesh Verma',
                'father_phone' => '9876543210',
                'father_occupation' => 'Engineer',
                'mother_name' => 'Mrs. Sunita Verma',
                'mother_phone' => '9876543211',
                'mother_occupation' => 'Teacher',
                'guardian_name' => 'Mr. Rajesh Verma',
                'guardian_phone' => '9876543210',
                'guardian_occupation' => 'Engineer',
                'guardian_relation' => 'Father',
                'guardian_address' => '123 Green Park, New Delhi'
            ],
            [
                'first_name' => 'Rohan',
                'middle_name' => 'Kumar',
                'last_name' => 'Kapoor',
                'dob' => '2009-11-05',
                'email' => 'rohan.rajesh@example.com',
                'phone' => '9876512341',
                'gender' => 'Male',
                'institution_code' => 'INS001',
                'teacher_id' => 1,
                'institution_id' => 1,
                'class_id' => 2, // Class 2 for Green Valley
                'section_id' => 3,
                'address' => '456 Model Town, New Delhi',
                'pincode' => '110009',
                'district' => 'New Delhi',
                'caste_tribe' => 'OBC',
                'category' => 'OBC',
                'blood_group' => 'B+',
                'admission_date' => '2024-04-01',
                'father_name' => 'Mr. Suresh Kapoor',
                'father_phone' => '9876543212',
                'father_occupation' => 'Doctor',
                'mother_name' => 'Mrs. Rekha Kapoor',
                'mother_phone' => '9876543213',
                'mother_occupation' => 'Nurse',
                'guardian_name' => 'Mr. Suresh Kapoor',
                'guardian_phone' => '9876543212',
                'guardian_occupation' => 'Doctor',
                'guardian_relation' => 'Father',
                'guardian_address' => '456 Model Town, New Delhi'
            ],
            // Anita's Students (teacher_id = 2)
            [
                'first_name' => 'Simran',
                'middle_name' => 'Kaur',
                'last_name' => 'Gill',
                'dob' => '2007-03-14',
                'email' => 'simran.anita@example.com',
                'phone' => '9876512342',
                'gender' => 'Female',
                'institution_code' => 'INS001',
                'teacher_id' => 2,
                'institution_id' => 1,
                'class_id' => 2, // Class 2 for Green Valley
                'section_id' => 3,
                'address' => '789 Punjabi Bagh, New Delhi',
                'pincode' => '110026',
                'district' => 'New Delhi',
                'caste_tribe' => 'General',
                'category' => 'General',
                'blood_group' => 'AB+',
                'admission_date' => '2024-04-01',
                'father_name' => 'Mr. Harpreet Gill',
                'father_phone' => '9876543214',
                'father_occupation' => 'Business',
                'mother_name' => 'Mrs. Gurpreet Gill',
                'mother_phone' => '9876543215',
                'mother_occupation' => 'Housewife',
                'guardian_name' => 'Mr. Harpreet Gill',
                'guardian_phone' => '9876543214',
                'guardian_occupation' => 'Business',
                'guardian_relation' => 'Father',
                'guardian_address' => '789 Punjabi Bagh, New Delhi'
            ],
            [
                'first_name' => 'Aman',
                'middle_name' => 'Singh',
                'last_name' => 'Yadav',
                'dob' => '2008-09-21',
                'email' => 'aman.anita@example.com',
                'phone' => '9876512343',
                'gender' => 'Male',
                'institution_code' => 'INS001',
                'teacher_id' => 2,
                'institution_id' => 1,
                'class_id' => 3, // Class 3 for Green Valley
                'section_id' => 4,
                'address' => '321 Lajpat Nagar, New Delhi',
                'pincode' => '110024',
                'district' => 'New Delhi',
                'caste_tribe' => 'SC',
                'category' => 'SC',
                'blood_group' => 'O-',
                'admission_date' => '2024-04-01',
                'father_name' => 'Mr. Ram Yadav',
                'father_phone' => '9876543216',
                'father_occupation' => 'Farmer',
                'mother_name' => 'Mrs. Sita Yadav',
                'mother_phone' => '9876543217',
                'mother_occupation' => 'Housewife',
                'guardian_name' => 'Mr. Ram Yadav',
                'guardian_phone' => '9876543216',
                'guardian_occupation' => 'Farmer',
                'guardian_relation' => 'Father',
                'guardian_address' => '321 Lajpat Nagar, New Delhi'
            ],

            // Sunrise Public School Students (institution_id = 2)
            // Vikram's Students (teacher_id = 3)
            [
                'first_name' => 'Karan',
                'middle_name' => 'Raj',
                'last_name' => 'Bhatia',
                'dob' => '2009-06-30',
                'email' => 'karan.vikram@example.com',
                'phone' => '9876512344',
                'gender' => 'Male',
                'institution_code' => 'INS002',
                'teacher_id' => 3,
                'institution_id' => 2,
                'class_id' => 13, // Class 1 for Sunrise (13th class created)
                'section_id' => 2,
                'address' => '654 Karol Bagh, New Delhi',
                'pincode' => '110005',
                'district' => 'New Delhi',
                'caste_tribe' => 'General',
                'category' => 'General',
                'blood_group' => 'A-',
                'admission_date' => '2024-04-01',
                'father_name' => 'Mr. Vikram Bhatia',
                'father_phone' => '9876543218',
                'father_occupation' => 'Lawyer',
                'mother_name' => 'Mrs. Priya Bhatia',
                'mother_phone' => '9876543219',
                'mother_occupation' => 'Doctor',
                'guardian_name' => 'Mr. Vikram Bhatia',
                'guardian_phone' => '9876543218',
                'guardian_occupation' => 'Lawyer',
                'guardian_relation' => 'Father',
                'guardian_address' => '654 Karol Bagh, New Delhi'
            ],
            [
                'first_name' => 'Tanya',
                'middle_name' => 'Sharma',
                'last_name' => 'Malhotra',
                'dob' => '2007-12-10',
                'email' => 'tanya.vikram@example.com',
                'phone' => '9876512345',
                'gender' => 'Female',
                'institution_code' => 'INS002',
                'teacher_id' => 3,
                'institution_id' => 2,
                'class_id' => 14, // Class 2 for Sunrise (14th class created)
                'section_id' => 3,
                'address' => '987 Connaught Place, New Delhi',
                'pincode' => '110001',
                'district' => 'New Delhi',
                'caste_tribe' => 'General',
                'category' => 'General',
                'blood_group' => 'B-',
                'admission_date' => '2024-04-01',
                'father_name' => 'Mr. Rajesh Malhotra',
                'father_phone' => '9876543220',
                'father_occupation' => 'Banker',
                'mother_name' => 'Mrs. Neha Malhotra',
                'mother_phone' => '9876543221',
                'mother_occupation' => 'Accountant',
                'guardian_name' => 'Mr. Rajesh Malhotra',
                'guardian_phone' => '9876543220',
                'guardian_occupation' => 'Banker',
                'guardian_relation' => 'Father',
                'guardian_address' => '987 Connaught Place, New Delhi'
            ],
            // Neha's Students (teacher_id = 4)
            [
                'first_name' => 'Ishaan',
                'middle_name' => 'Kumar',
                'last_name' => 'Rathore',
                'dob' => '2008-04-25',
                'email' => 'ishaan.neha@example.com',
                'phone' => '9876512346',
                'gender' => 'Male',
                'institution_code' => 'INS002',
                'teacher_id' => 4,
                'institution_id' => 2,
                'class_id' => 13, // Class 1 for Sunrise (13th class created)
                'section_id' => 2,
                'address' => '147 Janpath, New Delhi',
                'pincode' => '110001',
                'district' => 'New Delhi',
                'caste_tribe' => 'General',
                'category' => 'General',
                'blood_group' => 'AB-',
                'admission_date' => '2024-04-01',
                'father_name' => 'Mr. Vikram Rathore',
                'father_phone' => '9876543222',
                'father_occupation' => 'Police Officer',
                'mother_name' => 'Mrs. Sunita Rathore',
                'mother_phone' => '9876543223',
                'mother_occupation' => 'Teacher',
                'guardian_name' => 'Mr. Vikram Rathore',
                'guardian_phone' => '9876543222',
                'guardian_occupation' => 'Police Officer',
                'guardian_relation' => 'Father',
                'guardian_address' => '147 Janpath, New Delhi'
            ],
            [
                'first_name' => 'Meera',
                'middle_name' => 'Devi',
                'last_name' => 'Joshi',
                'dob' => '2009-10-17',
                'email' => 'meera.neha@example.com',
                'phone' => '9876512347',
                'gender' => 'Female',
                'institution_code' => 'INS002',
                'teacher_id' => 4,
                'institution_id' => 2,
                'class_id' => 14, // Class 2 for Sunrise (14th class created)
                'section_id' => 3,
                'address' => '258 India Gate, New Delhi',
                'pincode' => '110003',
                'district' => 'New Delhi',
                'caste_tribe' => 'ST',
                'category' => 'ST',
                'blood_group' => 'O+',
                'admission_date' => '2024-04-01',
                'father_name' => 'Mr. Ramesh Joshi',
                'father_phone' => '9876543224',
                'father_occupation' => 'Government Employee',
                'mother_name' => 'Mrs. Geeta Joshi',
                'mother_phone' => '9876543225',
                'mother_occupation' => 'Housewife',
                'guardian_name' => 'Mr. Ramesh Joshi',
                'guardian_phone' => '9876543224',
                'guardian_occupation' => 'Government Employee',
                'guardian_relation' => 'Father',
                'guardian_address' => '258 India Gate, New Delhi'
            ]
        ];

        foreach ($students as $student) {
            Student::create(array_merge($student, [
                'photo' => "admin/uploads/students/1755236487_689ec887dc5d6.jpeg",
                'permanent_address' => $student['address'] ?? 'Same as Current Address',
                'student_id' => 'STU' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                
                // Photo fields (set to null for now)
                'father_photo' => null,
                'mother_photo' => null,
                'guardian_photo' => null,
                
                // Document Information
                'document_01_title' => 'Birth Certificate',
                'document_01_file' => null,
                'document_02_title' => 'Aadhar Card',
                'document_02_file' => null,
                'document_03_title' => 'Previous School Certificate',
                'document_03_file' => null,
                'document_04_title' => 'Medical Certificate',
                'document_04_file' => null,
                
                'admin_id' => 1,
                'password' => Hash::make('student123'),
                'decrypt_pw' => 'student123',
                'status' => 1
            ]));
        }
    }
}
