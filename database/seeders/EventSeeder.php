<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            // Green Valley School Events (institution_id = 1)
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

            // Sunrise Public School Events (institution_id = 2)
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

            // Delhi International School Events (institution_id = 3)
            [
                'title' => 'International Cultural Festival',
                'role' => 'student',
                'category' => 'Event',
                'institution_id' => 3,
                'location' => 'International Hall',
                'start_date' => '2025-04-10',
                'end_date' => '2025-04-12',
                'start_time' => '18:00:00',
                'end_time' => '22:00:00',
                'description' => 'Three-day international cultural festival featuring global traditions and performances.',
                'color' => '#6f42c1',
                'url' => null,
                'file_path' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'IB Curriculum Workshop',
                'role' => 'teacher',
                'category' => 'Meeting',
                'institution_id' => 3,
                'location' => 'Training Center',
                'start_date' => '2025-02-25',
                'end_date' => '2025-02-25',
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'description' => 'Workshop on IB curriculum implementation and best practices.',
                'color' => '#28a745',
                'url' => null,
                'file_path' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Modern Public School Events (institution_id = 4)
            [
                'title' => 'Modern Science Fair',
                'role' => 'student',
                'category' => 'Event',
                'institution_id' => 4,
                'location' => 'Science Complex',
                'start_date' => '2025-03-25',
                'end_date' => '2025-03-25',
                'start_time' => '10:00:00',
                'end_time' => '16:00:00',
                'description' => 'Annual science fair showcasing innovative student projects and experiments.',
                'color' => '#20c997',
                'url' => null,
                'file_path' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Parent Orientation',
                'role' => 'teacher',
                'category' => 'Meeting',
                'institution_id' => 4,
                'location' => 'Main Auditorium',
                'start_date' => '2025-01-15',
                'end_date' => '2025-01-15',
                'start_time' => '14:00:00',
                'end_time' => '16:00:00',
                'description' => 'Parent orientation program for new academic session.',
                'color' => '#17a2b8',
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
    }
}
