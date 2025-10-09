<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call individual seeders in proper order
        $this->call([
            AdminSeeder::class,           // Must be first (creates admin with ID 1)
            InstitutionSeeder::class,     // Depends on admin
            ClassRoomSeeder::class,       // Independent - classrooms needed for routines
            SchoolClassSeeder::class,     // Depends on institution - MUST be before SectionSeeder
            SectionSeeder::class,         // Depends on institution and classes
            TeacherSeeder::class,         // Depends on institution
            StudentSeeder::class,         // Depends on teacher, class, section
            NonWorkingStaffSeeder::class, // Depends on institution
                EventSeeder::class,           // Depends on institution
            SubjectSeeder::class,         // Depends on institution
            ExamTypeSeeder::class,        // Depends on institution
            ExamSeeder::class,            // Depends on institution, class, subject, teacher, exam_type
            InvigilatorSeeder::class,     // Depends on institution, teachers, classes, sections, exams
            EmailSmsSeeder::class,        // Depends on institution, students, teachers, classes
            AssignClassTeacherSeeder::class, // Depends on institution, teacher, class, section
            AssignSubjectSeeder::class,   // Depends on institution, teacher, class, section, subject
            AssignmentSeeder::class,      // Depends on institution, teacher, class, section, subject
            StudentAssignmentSeeder::class, // Depends on assignments and students
            AttendanceSeeder::class,      // Depends on students and teachers
            RoutineSeeder::class,         // Depends on institution, class, section, subject, teacher, classroom
            LessonPlanSeeder::class,      // Depends on institution, class, subject, teacher
        ]);
    }
}
