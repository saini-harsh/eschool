<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        // Get some students and teachers for realistic attendance data
        $students = Student::where('status', 1)->take(20)->get();
        $teachers = Teacher::where('status', 1)->take(8)->get();

        $attendances = [];

        // Create attendance for the last 7 days
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::today()->subDays($i);

            // Student attendance
            foreach ($students as $student) {
                $status = ['present', 'present', 'present', 'present', 'absent', 'late'][rand(0, 5)];
                $remarks = $this->getRandomRemarks($status);

                $attendances[] = [
                    'user_id' => $student->id,
                    'role' => 'student',
                    'institution_id' => $student->institution_id,
                    'class_id' => $student->class_id,
                    'section_id' => $student->section_id,
                    'teacher_id' => $this->getRandomTeacherForClass($student->class_id, $student->section_id, $student->institution_id),
                    'date' => $date->toDateString(),
                    'status' => $status,
                    'remarks' => $remarks,
                    'marked_by' => $teachers->random()->id,
                    'marked_by_role' => 'teacher',
                    'is_confirmed' => rand(0, 1) == 1,
                    'confirmed_by' => rand(0, 1) == 1 ? $teachers->random()->id : null,
                    'confirmed_at' => rand(0, 1) == 1 ? $date->addHours(rand(1, 8)) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Teacher attendance
            foreach ($teachers as $teacher) {
                $status = ['present', 'present', 'present', 'present', 'absent'][rand(0, 4)];
                $remarks = $this->getRandomRemarks($status);

                $attendances[] = [
                    'user_id' => $teacher->id,
                    'role' => 'teacher',
                    'institution_id' => $teacher->institution_id,
                    'class_id' => null,
                    'section_id' => null,
                    'teacher_id' => null,
                    'date' => $date->toDateString(),
                    'status' => $status,
                    'remarks' => $remarks,
                    'marked_by' => $teachers->random()->id,
                    'marked_by_role' => 'teacher',
                    'is_confirmed' => rand(0, 1) == 1,
                    'confirmed_by' => rand(0, 1) == 1 ? $teachers->random()->id : null,
                    'confirmed_at' => rand(0, 1) == 1 ? $date->addHours(rand(1, 8)) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert in batches for better performance, but only if they don't exist
        $newAttendances = [];
        foreach ($attendances as $attendance) {
            if (!Attendance::where('user_id', $attendance['user_id'])
                ->where('role', $attendance['role'])
                ->where('date', $attendance['date'])
                ->exists()) {
                $newAttendances[] = $attendance;
            }
        }

        if (!empty($newAttendances)) {
            foreach (array_chunk($newAttendances, 100) as $chunk) {
                Attendance::insert($chunk);
            }
        }

        $this->command->info('Attendance records seeded successfully!');
    }

    private function getRandomRemarks($status)
    {
        $remarks = [
            'present' => [
                'On time',
                'Participated actively',
                'Good attendance',
                'Present and engaged',
                'Regular attendance',
                'Punctual',
                'Active participation',
                'Good behavior',
            ],
            'absent' => [
                'Sick leave',
                'Family emergency',
                'Medical appointment',
                'Personal reasons',
                'Family function',
                'Health issues',
                'Emergency at home',
                'Prior commitment',
            ],
            'late' => [
                'Traffic delay',
                'Missed bus',
                'Transportation issues',
                'Weather conditions',
                'Road construction',
                'Vehicle breakdown',
                'Late start',
                'Traffic jam',
            ]
        ];

        $statusRemarks = $remarks[$status] ?? ['No remarks'];
        return $statusRemarks[array_rand($statusRemarks)];
    }

    private function getRandomTeacherForClass($classId, $sectionId, $institutionId)
    {
        // Get a teacher assigned to this class and section
        $teacher = Teacher::where('institution_id', $institutionId)
            ->where('status', 1)
            ->inRandomOrder()
            ->first();

        return $teacher ? $teacher->id : null;
    }
}
