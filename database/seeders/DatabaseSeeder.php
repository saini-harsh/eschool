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
            SectionSeeder::class,         // Independent
            SchoolClassSeeder::class,     // Depends on institution
            TeacherSeeder::class,         // Depends on institution
            StudentSeeder::class,         // Depends on teacher, class, section
            NonWorkingStaffSeeder::class, // Depends on institution
            EventSeeder::class,           // Depends on institution
            SubjectSeeder::class,         // Depends on institution
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
