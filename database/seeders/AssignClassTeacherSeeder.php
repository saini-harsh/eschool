<?php

namespace Database\Seeders;

use App\Models\AssignClassTeacher;
use App\Models\Teacher;
use App\Models\SchoolClass;
use Illuminate\Database\Seeder;

class AssignClassTeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all institutions, teachers, classes, and sections
        $institutions = \App\Models\Institution::where('status', 1)->get();
        $teachers = Teacher::where('status', 1)->get();
        $classes = SchoolClass::where('status', 1)->get();
        $sections = \App\Models\Section::where('status', 1)->get();

        $assignmentCount = 0;

        foreach ($institutions as $institution) {
            $institutionTeachers = $teachers->where('institution_id', $institution->id);
            $institutionClasses = $classes->where('institution_id', $institution->id);

            if ($institutionTeachers->isEmpty() || $institutionClasses->isEmpty()) {
                $this->command->info("Skipping institution {$institution->id} - missing teachers or classes");
                continue;
            }

            foreach ($institutionClasses as $class) {
                // Get sections for this class
                $classSections = $sections->where('class_id', $class->id)
                    ->where('institution_id', $institution->id);

                if ($classSections->isEmpty()) {
                    $this->command->info("Skipping class {$class->name} - no sections found");
                    continue;
                }

                // Assign 1-2 teachers per class, with each teacher handling 1-2 sections
                $teachersForClass = $institutionTeachers->random(min(2, $institutionTeachers->count()));
                $sectionsArray = $classSections->toArray();
                $sectionsPerTeacher = ceil(count($sectionsArray) / count($teachersForClass));

                $sectionIndex = 0;
                foreach ($teachersForClass as $teacher) {
                    $sectionsToAssign = array_slice($sectionsArray, $sectionIndex, $sectionsPerTeacher);
                    
                    foreach ($sectionsToAssign as $section) {
                        // Check if assignment already exists
                        if (!AssignClassTeacher::where('institution_id', $institution->id)
                            ->where('class_id', $class->id)
                            ->where('section_id', $section['id'])
                            ->where('teacher_id', $teacher->id)
                            ->exists()) {
                            
                            AssignClassTeacher::create([
                                'institution_id' => $institution->id,
                                'class_id' => $class->id,
                                'section_id' => $section['id'],
                                'teacher_id' => $teacher->id,
                                'status' => 1,
                            ]);
                            $assignmentCount++;
                        }
                    }
                    $sectionIndex += $sectionsPerTeacher;
                }
            }
        }

        $this->command->info("Class-teacher assignments seeded successfully! Created {$assignmentCount} assignments.");
    }
}
