<?php

namespace Database\Seeders;

use App\Models\LessonPlan;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class LessonPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lessonPlanTemplates = [
            // Mathematics lesson plans
            [
                'title' => 'Introduction to Algebra',
                'description' => 'Basic concepts of algebra including variables, constants, and simple equations.',
                'subjects' => ['Mathematics'],
                'classes' => ['Class 6', 'Class 7', 'Class 8', 'Class 9']
            ],
            [
                'title' => 'Geometry Fundamentals',
                'description' => 'Understanding basic geometric shapes, angles, and their properties.',
                'subjects' => ['Mathematics'],
                'classes' => ['Class 4', 'Class 5', 'Class 6', 'Class 7']
            ],
            [
                'title' => 'Arithmetic Operations',
                'description' => 'Addition, subtraction, multiplication, and division with practical examples.',
                'subjects' => ['Mathematics'],
                'classes' => ['Class 1', 'Class 2', 'Class 3', 'Class 4']
            ],

            // English lesson plans
            [
                'title' => 'Creative Writing Workshop',
                'description' => 'Developing creative writing skills through storytelling and descriptive writing.',
                'subjects' => ['English'],
                'classes' => ['Class 5', 'Class 6', 'Class 7', 'Class 8', 'Class 9']
            ],
            [
                'title' => 'Grammar Basics',
                'description' => 'Introduction to parts of speech, sentence structure, and basic grammar rules.',
                'subjects' => ['English'],
                'classes' => ['Class 3', 'Class 4', 'Class 5', 'Class 6']
            ],
            [
                'title' => 'Reading Comprehension',
                'description' => 'Improving reading skills and comprehension through various text types.',
                'subjects' => ['English'],
                'classes' => ['Class 2', 'Class 3', 'Class 4', 'Class 5']
            ],

            // Science lesson plans
            [
                'title' => 'Solar System Exploration',
                'description' => 'Understanding planets, stars, and the solar system through interactive activities.',
                'subjects' => ['Science'],
                'classes' => ['Class 4', 'Class 5', 'Class 6', 'Class 7']
            ],
            [
                'title' => 'Plant Life Cycle',
                'description' => 'Learning about how plants grow, reproduce, and their importance to the environment.',
                'subjects' => ['Science'],
                'classes' => ['Class 3', 'Class 4', 'Class 5', 'Class 6']
            ],
            [
                'title' => 'Simple Machines',
                'description' => 'Understanding levers, pulleys, and other simple machines in daily life.',
                'subjects' => ['Science'],
                'classes' => ['Class 5', 'Class 6', 'Class 7', 'Class 8']
            ],

            // Social Studies lesson plans
            [
                'title' => 'Indian Independence Movement',
                'description' => 'Learning about key figures and events in India\'s struggle for independence.',
                'subjects' => ['Social Studies'],
                'classes' => ['Class 6', 'Class 7', 'Class 8', 'Class 9']
            ],
            [
                'title' => 'Geography of India',
                'description' => 'Understanding India\'s physical features, climate, and natural resources.',
                'subjects' => ['Social Studies'],
                'classes' => ['Class 4', 'Class 5', 'Class 6', 'Class 7']
            ],
            [
                'title' => 'Civic Responsibilities',
                'description' => 'Learning about rights, duties, and responsibilities as citizens.',
                'subjects' => ['Social Studies'],
                'classes' => ['Class 5', 'Class 6', 'Class 7', 'Class 8']
            ],

            // Hindi lesson plans
            [
                'title' => 'हिंदी व्याकरण',
                'description' => 'हिंदी भाषा के मूलभूत व्याकरणिक नियमों की समझ।',
                'subjects' => ['Hindi'],
                'classes' => ['Class 3', 'Class 4', 'Class 5', 'Class 6']
            ],
            [
                'title' => 'कहानी लेखन',
                'description' => 'रचनात्मक कहानी लेखन कौशल का विकास।',
                'subjects' => ['Hindi'],
                'classes' => ['Class 4', 'Class 5', 'Class 6', 'Class 7']
            ],

            // Computer lesson plans
            [
                'title' => 'Introduction to Programming',
                'description' => 'Basic programming concepts using Scratch and simple algorithms.',
                'subjects' => ['Computer'],
                'classes' => ['Class 6', 'Class 7', 'Class 8', 'Class 9']
            ],
            [
                'title' => 'Microsoft Office Basics',
                'description' => 'Learning to use Word, Excel, and PowerPoint for school projects.',
                'subjects' => ['Computer'],
                'classes' => ['Class 5', 'Class 6', 'Class 7', 'Class 8']
            ],

            // Art lesson plans
            [
                'title' => 'Watercolor Techniques',
                'description' => 'Learning basic watercolor painting techniques and color mixing.',
                'subjects' => ['Art'],
                'classes' => ['Class 3', 'Class 4', 'Class 5', 'Class 6']
            ],
            [
                'title' => 'Clay Modeling',
                'description' => 'Creating three-dimensional art using clay and modeling techniques.',
                'subjects' => ['Art'],
                'classes' => ['Class 2', 'Class 3', 'Class 4', 'Class 5']
            ],

            // Physical Education lesson plans
            [
                'title' => 'Basketball Fundamentals',
                'description' => 'Learning basic basketball skills including dribbling, shooting, and passing.',
                'subjects' => ['Physical Education'],
                'classes' => ['Class 6', 'Class 7', 'Class 8', 'Class 9']
            ],
            [
                'title' => 'Yoga and Meditation',
                'description' => 'Introduction to basic yoga poses and meditation techniques for stress relief.',
                'subjects' => ['Physical Education'],
                'classes' => ['Class 4', 'Class 5', 'Class 6', 'Class 7']
            ],
        ];

        $institutions = [1, 2, 3, 4]; // Based on existing seeder data
        $lessonPlanCount = 0;

        foreach ($institutions as $institutionId) {
            // Get teachers, classes, and subjects for this institution
            $teachers = Teacher::where('institution_id', $institutionId)->get();
            $classes = SchoolClass::where('institution_id', $institutionId)->get();
            $subjects = Subject::where('institution_id', $institutionId)->get();

            if ($teachers->isEmpty() || $classes->isEmpty() || $subjects->isEmpty()) {
                $this->command->info("Skipping institution {$institutionId} - missing required data");
                continue;
            }

            // Create lesson plans for each template
            foreach ($lessonPlanTemplates as $template) {
                $subject = $subjects->where('name', $template['subjects'][0])->first();
                if (!$subject) {
                    continue;
                }

                // Find classes that match the template
                $matchingClasses = $classes->filter(function($class) use ($template) {
                    return in_array($class->name, $template['classes']);
                });

                if ($matchingClasses->isEmpty()) {
                    continue;
                }

                // Create lesson plans for each matching class
                foreach ($matchingClasses as $class) {
                    // Create 1-3 lesson plans per class per subject
                    $lessonPlansPerClass = rand(1, 3);
                    
                    for ($i = 0; $i < $lessonPlansPerClass; $i++) {
                        if ($teachers->isEmpty()) {
                            break;
                        }
                        
                        $randomTeacher = $teachers->random();
                        
                        // Create unique title for each lesson plan
                        $title = $template['title'];
                        if ($i > 0) {
                            $title .= " - Part " . ($i + 1);
                        }

                        // Check if lesson plan already exists
                        $existingLessonPlan = LessonPlan::where('institution_id', $institutionId)
                            ->where('class_id', $class->id)
                            ->where('subject_id', $subject->id)
                            ->where('teacher_id', $randomTeacher->id)
                            ->where('title', $title)
                            ->exists();

                        if (!$existingLessonPlan) {
                            LessonPlan::create([
                                'title' => $title,
                                'description' => $template['description'],
                                'institution_id' => $institutionId,
                                'teacher_id' => $randomTeacher->id,
                                'class_id' => $class->id,
                                'subject_id' => $subject->id,
                                'lesson_plan_file' => null, // Can be added later
                                'status' => 1,
                                'created_by' => 1, // Admin ID
                            ]);
                            $lessonPlanCount++;
                        }
                    }
                }
            }
        }

        $this->command->info("Lesson plans seeded successfully! Created {$lessonPlanCount} lesson plans.");
    }
}
