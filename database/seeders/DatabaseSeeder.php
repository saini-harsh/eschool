<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Admin;
use App\Models\Section;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Institution;
use App\Models\SchoolClass;
use App\Models\NonWorkingStaff;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
                'logo' => 1,
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
        // Students End

        // Sections start
        $sections = [
            // Rajesh's Students
            [
                'name' => 'A',
            ],
            [
                'name' => 'B',
            ],
            [
                'name' => 'C',
            ],
            [
                'name' => 'D',
            ],
            [
                'name' => 'E',
            ],
            [
                'name' => 'F',
            ],
        ];
        foreach ($sections as $s) {
            Section::create(array_merge($s, [
                'status' => 1
            ]));
        }
        // Sections end

        // ✅ Non Working Staff Start
        $staffs = [
            [
                'first_name' => 'Suresh',
                'middle_name' => null,
                'last_name' => 'Kumar',
                'dob' => '1975-05-12',
                'email' => 'suresh.green@example.com',
                'phone' => '9876598765',
                'address' => 'Staff Colony, Delhi',
                'pincode' => '110011',
                'institution_code' => 'INS001',
                'gender' => 'Male',
                'caste_tribe' => 'General',
                'institution_id' => 1,
                'admin_id' => 1,
                'designation' => 'Clerk',
                'date_of_joining' => '2015-07-01',
            ],
            [
                'first_name' => 'Meena',
                'middle_name' => null,
                'last_name' => 'Devi',
                'dob' => '1980-09-20',
                'email' => 'meena.sunrise@example.com',
                'phone' => '9876523456',
                'address' => 'Sunrise Colony, Delhi',
                'pincode' => '110022',
                'institution_code' => 'INS002',
                'gender' => 'Female',
                'caste_tribe' => 'OBC',
                'institution_id' => 2,
                'admin_id' => 1,
                'designation' => 'Librarian',
                'date_of_joining' => '2018-03-15',
                ]
            ];

            foreach ($staffs as $st) {
                NonWorkingStaff::create(array_merge($st, [
                'profile_image' => "admin/uploads/students/1755236487_689ec887dc5d6.jpeg",
                'password' => Hash::make('staff123'),
                'decrypt_pw' => 'staff123',
                'status' => 1
            ]));
        }
        // ✅ Non Working Staff End

        // SchoolClass Start
        $classNames = [
            'Nursery',
            'LKG',
            'UKG',
            'Class 1',
            'Class 2',
            'Class 3',
            'Class 4',
            'Class 5',
            'Class 6',
            'Class 7',
            'Class 8',
            'Class 9',
        ];

        foreach ($classNames as $name) {
            SchoolClass::create([
                'name' => $name,
                'section_ids' => json_encode(['0', '1']), // default sections
                'student_count' => 0,
                'institution_id' => 1, // change if needed
                'admin_id' => 1,       // change if needed
                'status' => 1,       // change if needed
            ]);
        }
        // SchoolClass End

        // Subjects Start
        $subjects = [
            ['name' => 'Mathematics', 'code' => 'MATH', 'type' => 'theory'],
            ['name' => 'English', 'code' => 'ENG', 'type' => 'theory'],
            ['name' => 'Science', 'code' => 'SCI', 'type' => 'theory'],
            ['name' => 'Social Studies', 'code' => 'SST', 'type' => 'theory'],
            ['name' => 'Hindi', 'code' => 'HIN', 'type' => 'theory'],
            ['name' => 'Computer', 'code' => 'COMP', 'type' => 'practical'],
            ['name' => 'Art', 'code' => 'ART', 'type' => 'practical'],
            ['name' => 'Physical Education', 'code' => 'PE', 'type' => 'practical'],
        ];
        
        foreach ($subjects as $subject) {
            Subject::create([
                'name' => $subject['name'],
                'code' => $subject['code'],
                'type' => $subject['type'],
                'status' => 1,
                'institution_id' => 1, // adjust if multiple institutions
                'class_id' => 1,    // you can link to class later
            ]);
        }
        // Subjects End

        // Events Start
        $events = [
            [
                'title' => 'Annual Sports Day',
                'role' => 'student',
                'category' => 'Event',
                'institution_id' => 1,
                'location' => 'School Ground',
                'start_date' => '2025-02-15',
                'end_date' => '2025-02-15',
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'description' => 'Annual sports day celebration with various athletic events and competitions for all students.',
                'color' => '#3788d8',
                'url' => 'https://school.edu/sports-day',
                'file_path' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Mid-Term Examination',
                'role' => 'student',
                'category' => 'Exam',
                'institution_id' => 1,
                'location' => 'All Classrooms',
                'start_date' => '2025-01-20',
                'end_date' => '2025-01-25',
                'start_time' => '08:30:00',
                'end_time' => '12:30:00',
                'description' => 'Mid-term examinations for all classes. Students must bring their own stationery.',
                'color' => '#dc3545',
                'url' => null,
                'file_path' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Teachers Meeting',
                'role' => 'teacher',
                'category' => 'Meeting',
                'institution_id' => 1,
                'location' => 'Conference Room',
                'start_date' => '2025-01-30',
                'end_date' => '2025-01-30',
                'start_time' => '14:00:00',
                'end_time' => '16:00:00',
                'description' => 'Monthly teachers meeting to discuss curriculum updates and upcoming events.',
                'color' => '#28a745',
                'url' => null,
                'file_path' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Republic Day Holiday',
                'role' => 'student',
                'category' => 'Holiday',
                'institution_id' => 1,
                'location' => 'School Closed',
                'start_date' => '2025-01-26',
                'end_date' => '2025-01-26',
                'start_time' => null,
                'end_time' => null,
                'description' => 'School will be closed for Republic Day celebration.',
                'color' => '#ffc107',
                'url' => null,
                'file_path' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Science Fair Deadline',
                'role' => 'student',
                'category' => 'Deadline',
                'institution_id' => 1,
                'location' => 'Science Lab',
                'start_date' => '2025-02-10',
                'end_date' => '2025-02-10',
                'start_time' => '16:00:00',
                'end_time' => '16:00:00',
                'description' => 'Deadline for submitting science fair project proposals.',
                'color' => '#6f42c1',
                'url' => 'https://school.edu/science-fair',
                'file_path' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Parent-Teacher Conference',
                'role' => 'teacher',
                'category' => 'Meeting',
                'institution_id' => 1,
                'location' => 'School Auditorium',
                'start_date' => '2025-02-05',
                'end_date' => '2025-02-07',
                'start_time' => '10:00:00',
                'end_time' => '15:00:00',
                'description' => 'Annual parent-teacher conference to discuss student progress and performance.',
                'color' => '#17a2b8',
                'url' => null,
                'file_path' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Cultural Festival',
                'role' => 'student',
                'category' => 'Event',
                'institution_id' => 1,
                'location' => 'School Auditorium',
                'start_date' => '2025-03-15',
                'end_date' => '2025-03-17',
                'start_time' => '18:00:00',
                'end_time' => '22:00:00',
                'description' => 'Three-day cultural festival featuring music, dance, drama, and art exhibitions.',
                'color' => '#fd7e14',
                'url' => 'https://school.edu/cultural-festival',
                'file_path' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Final Examination',
                'role' => 'student',
                'category' => 'Exam',
                'institution_id' => 1,
                'location' => 'All Classrooms',
                'start_date' => '2025-04-20',
                'end_date' => '2025-05-05',
                'start_time' => '09:00:00',
                'end_time' => '13:00:00',
                'description' => 'Final examinations for all classes. Students must follow the examination schedule.',
                'color' => '#dc3545',
                'url' => null,
                'file_path' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Summer Vacation',
                'role' => 'student',
                'category' => 'Holiday',
                'institution_id' => 1,
                'location' => 'School Closed',
                'start_date' => '2025-05-15',
                'end_date' => '2025-06-30',
                'start_time' => null,
                'end_time' => null,
                'description' => 'Summer vacation period. School will reopen on July 1st.',
                'color' => '#20c997',
                'url' => null,
                'file_path' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Library Book Return Deadline',
                'role' => 'student',
                'category' => 'Deadline',
                'institution_id' => 1,
                'location' => 'School Library',
                'start_date' => '2025-01-31',
                'end_date' => '2025-01-31',
                'start_time' => '15:00:00',
                'end_time' => '15:00:00',
                'description' => 'Last date to return all library books before the end of semester.',
                'color' => '#6f42c1',
                'url' => null,
                'file_path' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Staff Development Workshop',
                'role' => 'teacher',
                'category' => 'Meeting',
                'institution_id' => 1,
                'location' => 'Training Room',
                'start_date' => '2025-02-12',
                'end_date' => '2025-02-12',
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'description' => 'Professional development workshop for all teaching staff on modern teaching methodologies.',
                'color' => '#28a745',
                'url' => null,
                'file_path' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Independence Day Celebration',
                'role' => 'student',
                'category' => 'Event',
                'institution_id' => 1,
                'location' => 'School Ground',
                'start_date' => '2025-08-15',
                'end_date' => '2025-08-15',
                'start_time' => '08:00:00',
                'end_time' => '12:00:00',
                'description' => 'Independence Day celebration with flag hoisting, cultural programs, and patriotic songs.',
                'color' => '#fd7e14',
                'url' => null,
                'file_path' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add events for the second institution (Sunrise Public School)
            [
                'title' => 'Sunrise Annual Day',
                'role' => 'student',
                'category' => 'Event',
                'institution_id' => 2,
                'location' => 'School Auditorium',
                'start_date' => '2025-03-20',
                'end_date' => '2025-03-20',
                'start_time' => '18:00:00',
                'end_time' => '22:00:00',
                'description' => 'Annual day celebration with cultural performances and award ceremony.',
                'color' => '#fd7e14',
                'url' => null,
                'file_path' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Sunrise Teachers Training',
                'role' => 'teacher',
                'category' => 'Meeting',
                'institution_id' => 2,
                'location' => 'Conference Hall',
                'start_date' => '2025-02-18',
                'end_date' => '2025-02-18',
                'start_time' => '09:00:00',
                'end_time' => '16:00:00',
                'description' => 'Training session for teachers on new curriculum and teaching methods.',
                'color' => '#28a745',
                'url' => null,
                'file_path' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Staff Meeting',
                'role' => 'nonworkingstaff',
                'category' => 'Meeting',
                'institution_id' => 1,
                'location' => 'Staff Room',
                'start_date' => '2025-01-28',
                'end_date' => '2025-01-28',
                'start_time' => '15:00:00',
                'end_time' => '16:00:00',
                'description' => 'Monthly staff meeting to discuss administrative matters and upcoming events.',
                'color' => '#28a745',
                'url' => null,
                'file_path' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Library Maintenance',
                'role' => 'nonworkingstaff',
                'category' => 'Other',
                'institution_id' => 1,
                'location' => 'School Library',
                'start_date' => '2025-02-01',
                'end_date' => '2025-02-01',
                'start_time' => '08:00:00',
                'end_time' => '12:00:00',
                'description' => 'Library maintenance and book cataloging work.',
                'color' => '#6c757d',
                'url' => null,
                'file_path' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($events as $event) {
            DB::table('academic_events')->insert($event);
        }
        // Events End

    }
}
