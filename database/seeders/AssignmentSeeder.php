<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Institution;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AssignmentSeeder extends Seeder
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

                // Create 1-3 assignments per section per subject
                foreach ($classSections as $section) {
                    $sectionSubjects = $institutionSubjects->random(min(3, $institutionSubjects->count()));
                    
                    foreach ($sectionSubjects as $subject) {
                        $assignmentsPerSection = rand(1, 3);
                        $randomTeacher = $institutionTeachers->random();
                        
                        for ($i = 1; $i <= $assignmentsPerSection; $i++) {
                            // Check if assignment already exists
                            if (!Assignment::where('institution_id', $institution->id)
                                ->where('class_id', $class->id)
                                ->where('section_id', $section->id)
                                ->where('subject_id', $subject->id)
                                ->where('teacher_id', $randomTeacher->id)
                                ->exists()) {
                                
                                Assignment::create([
                                    'title' => $this->getRandomAssignmentTitle($subject->name),
                                    'description' => $this->getRandomAssignmentDescription($subject->name),
                                    'institution_id' => $institution->id,
                                    'teacher_id' => $randomTeacher->id,
                                    'class_id' => $class->id,
                                    'section_id' => $section->id,
                                    'subject_id' => $subject->id,
                                    'due_date' => Carbon::now()->addDays(rand(1, 15)),
                                    'assignment_file' => null, // Can be added later
                                    'status' => 1,
                                ]);
                                $assignmentCount++;
                            }
                        }
                    }
                }
            }
        }

        $this->command->info("Assignments seeded successfully! Created {$assignmentCount} assignments.");
    }

    private function getRandomAssignmentTitle($subjectName)
    {
        $titles = [
            'Mathematics' => ['Algebra Basics', 'Geometry Problems', 'Trigonometry Applications', 'Calculus Fundamentals'],
            'English' => ['Creative Writing', 'Grammar Exercise', 'Reading Comprehension', 'Poetry Analysis'],
            'Science' => ['Lab Report', 'Scientific Method', 'Physics Experiments', 'Chemistry Reactions'],
            'Social Studies' => ['Historical Analysis', 'Geography Project', 'Civic Responsibilities', 'Cultural Studies'],
            'Hindi' => ['हिंदी व्याकरण', 'कहानी लेखन', 'कविता विश्लेषण', 'निबंध लेखन'],
            'Computer' => ['Programming Project', 'Database Design', 'Web Development', 'Software Testing'],
            'Art' => ['Painting Techniques', 'Sculpture Design', 'Color Theory', 'Art History'],
            'Physical Education' => ['Fitness Assessment', 'Sports Training', 'Health Education', 'Team Building']
        ];

        $subjectTitles = $titles[$subjectName] ?? ['General Assignment', 'Project Work', 'Research Task', 'Practical Exercise'];
        return $subjectTitles[array_rand($subjectTitles)];
    }

    private function getRandomAssignmentDescription($subjectName)
    {
        $descriptions = [
            'Complete the given exercises and submit by the due date.',
            'Research the topic thoroughly and present your findings.',
            'Practice the concepts learned in class and apply them.',
            'Work in groups to complete this collaborative project.',
            'Use proper methodology and show all your work clearly.',
            'Submit a detailed report with proper formatting.',
            'Include diagrams, charts, and examples where applicable.',
            'Demonstrate understanding through practical application.'
        ];

        return $descriptions[array_rand($descriptions)];
    }
}