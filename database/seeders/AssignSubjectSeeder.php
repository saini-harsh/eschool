<?php

namespace Database\Seeders;

use App\Models\AssignSubject;
use App\Models\Institution;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class AssignSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all institutions, teachers, classes, sections, and subjects
        $institutions = Institution::where('status', 1)->get();
        $teachers = Teacher::where('status', 1)->get();
        $classes = SchoolClass::where('status', 1)->get();
        $sections = Section::where('status', 1)->get();
        $subjects = Subject::where('status', 1)->get();

        $assignmentCount = 0;

        foreach ($institutions as $institution) {
            $institutionTeachers = $teachers->where('institution_id', $institution->id);
            $institutionClasses = $classes->where('institution_id', $institution->id);
            $institutionSubjects = $subjects->where('institution_id', $institution->id);

            if ($institutionTeachers->isEmpty() || $institutionClasses->isEmpty() || $institutionSubjects->isEmpty()) {
                $this->command->info("Skipping institution {$institution->id} - missing required data");
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

                // Assign 2-4 subjects per section with different teachers
                foreach ($classSections as $section) {
                    $subjectsForSection = $institutionSubjects->random(min(4, $institutionSubjects->count()));
                    
                    foreach ($subjectsForSection as $subject) {
                        $randomTeacher = $institutionTeachers->random();
                        
                        // Check if assignment already exists
                        if (!AssignSubject::where('institution_id', $institution->id)
                            ->where('class_id', $class->id)
                            ->where('section_id', $section->id)
                            ->where('subject_id', $subject->id)
                            ->where('teacher_id', $randomTeacher->id)
                            ->exists()) {
                            
                            AssignSubject::create([
                                'institution_id' => $institution->id,
                                'class_id' => $class->id,
                                'section_id' => $section->id,
                                'subject_id' => $subject->id,
                                'teacher_id' => $randomTeacher->id,
                                'status' => 1,
                            ]);
                            $assignmentCount++;
                        }
                    }
                }
            }
        }

        $this->command->info("Subject assignments seeded successfully! Created {$assignmentCount} assignments.");
    }
}