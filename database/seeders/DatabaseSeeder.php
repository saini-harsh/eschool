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
            SectionSeeder::class,         // Independent
            SchoolClassSeeder::class,     // Depends on institution
            TeacherSeeder::class,         // Depends on institution
            StudentSeeder::class,         // Depends on teacher, class, section
            NonWorkingStaffSeeder::class, // Depends on institution
            EventSeeder::class,           // Depends on institution
            SubjectSeeder::class,         // Depends on institution
        ]);
    }
}
