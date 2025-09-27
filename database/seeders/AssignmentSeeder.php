<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\AssignSubject;
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
        // Get all subject assignments to create realistic assignments
        $subjectAssignments = AssignSubject::with(['subject', 'teacher'])
            ->where('status', 1)
            ->get();

        $assignments = [];

        foreach ($subjectAssignments as $assignment) {
            // Create assignments based on the subject assignments
            $assignments[] = $this->createAssignmentForSubject($assignment);
        }

        // Add some additional assignments for variety
        $additionalAssignments = [
            // Green Valley School - Rajesh Sharma (Mathematics)
            [
                'title' => 'Advanced Algebra Problems',
                'description' => 'Solve complex algebraic equations involving multiple variables. Show step-by-step solutions.',
                'institution_id' => 1,
                'teacher_id' => 1, // Rajesh Sharma
                'class_id' => 1, // Class 1
                'section_id' => 1,
                'subject_id' => 1, // Mathematics
                'due_date' => Carbon::now()->addDays(5),
                'assignment_file' => 'teacher/uploads/assignment/advanced_algebra.pdf',
                'status' => 1,
            ],
            // Green Valley School - Anita Mehra (English)
            [
                'title' => 'Creative Writing - Poetry',
                'description' => 'Write a collection of 5 poems on different themes. Each poem should be at least 8 lines long.',
                'institution_id' => 1,
                'teacher_id' => 1, // Anita Mehra
                'class_id' => 1, // Class 2
                'section_id' => 1,
                'subject_id' => 2, // English
                'due_date' => Carbon::now()->addDays(8),
                'assignment_file' => 'teacher/uploads/assignment/poetry_guidelines.pdf',
                'status' => 1,
            ],
            // Sunrise Public School - Vikram Kumar (Computer)
            [
                'title' => 'Web Development Project',
                'description' => 'Create a simple HTML webpage with CSS styling. Include at least 3 pages with navigation.',
                'institution_id' => 2,
                'teacher_id' => 3, // Vikram Kumar
                'class_id' => 8, // Class 5
                'section_id' => 1,
                'subject_id' => 6, // Computer
                'due_date' => Carbon::now()->addDays(12),
                'assignment_file' => 'teacher/uploads/assignment/web_dev_project.pdf',
                'status' => 1,
            ],
            // Delhi International School - Arun Patel (Mathematics)
            [
                'title' => 'Trigonometry Applications',
                'description' => 'Solve real-world problems using trigonometric functions. Include diagrams and explanations.',
                'institution_id' => 3,
                'teacher_id' => 5, // Arun Patel
                'class_id' => 11, // Class 8
                'section_id' => 1,
                'subject_id' => 1, // Mathematics
                'due_date' => Carbon::now()->addDays(10),
                'assignment_file' => 'teacher/uploads/assignment/trigonometry_applications.pdf',
                'status' => 1,
            ],
            // Modern Public School - Pooja Sharma (Science)
            [
                'title' => 'Biology Research Project',
                'description' => 'Research and present findings on the human digestive system. Include diagrams and explanations.',
                'institution_id' => 4,
                'teacher_id' => 8, // Pooja Sharma
                'class_id' => 9, // Class 6
                'section_id' => 1,
                'subject_id' => 3, // Science
                'due_date' => Carbon::now()->addDays(14),
                'assignment_file' => 'teacher/uploads/assignment/digestive_system.pdf',
                'status' => 1,
            ],
        ];

        // Add overdue assignments
        $overdueAssignments = [
            [
                'title' => 'Mathematics Quiz Preparation',
                'description' => 'Review chapters 1-5 for the upcoming mathematics quiz. Practice problems from the textbook.',
                'institution_id' => 1,
                'teacher_id' => 1, // Rajesh Sharma
                'class_id' => 4, // Class 1
                'section_id' => 1,
                'subject_id' => 1, // Mathematics
                'due_date' => Carbon::now()->subDays(2), // Overdue
                'assignment_file' => 'teacher/uploads/assignment/math_quiz_prep.pdf',
                'status' => 1,
            ],
            [
                'title' => 'Science Homework - Plant Life Cycle',
                'description' => 'Draw and label the life cycle of a flowering plant. Include all stages from seed to mature plant.',
                'institution_id' => 2,
                'teacher_id' => 4, // Neha Singh
                'class_id' => 5, // Class 2
                'section_id' => 2,
                'subject_id' => 3, // Science
                'due_date' => Carbon::now()->subDays(1), // Overdue
                'assignment_file' => 'teacher/uploads/assignment/plant_life_cycle.pdf',
                'status' => 1,
            ],
        ];

        // Combine all assignments
        $allAssignments = array_merge($assignments, $additionalAssignments, $overdueAssignments);

            foreach ($allAssignments as $assignment) {
                // Check if assignment already exists
                if (!Assignment::where('title', $assignment['title'])
                    ->where('institution_id', $assignment['institution_id'])
                    ->where('teacher_id', $assignment['teacher_id'])
                    ->where('class_id', $assignment['class_id'])
                    ->where('section_id', $assignment['section_id'])
                    ->exists()) {
                    Assignment::create($assignment);
                }
            }

            $this->command->info('Assignments seeded successfully!');
    }

    /**
     * Create an assignment for a given subject assignment
     */
    private function createAssignmentForSubject($subjectAssignment)
    {
        $subject = $subjectAssignment->subject;
        $teacher = $subjectAssignment->teacher;
        
        // Generate assignment title based on subject
        $titles = [
            'Mathematics' => [
                'Basic Arithmetic Problems',
                'Algebra Fundamentals',
                'Geometry Practice',
                'Statistics and Probability',
                'Word Problems Solving'
            ],
            'English' => [
                'Reading Comprehension',
                'Grammar and Vocabulary',
                'Creative Writing Exercise',
                'Literature Analysis',
                'Essay Writing Practice'
            ],
            'Science' => [
                'Scientific Method Project',
                'Lab Report Writing',
                'Research on Natural Phenomena',
                'Experiment Documentation',
                'Science Fair Preparation'
            ],
            'Social Studies' => [
                'Historical Research Project',
                'Geography Assignment',
                'Civics and Government Study',
                'Cultural Analysis',
                'Current Events Discussion'
            ],
            'Hindi' => [
                'Hindi Grammar Practice',
                'Poetry Recitation',
                'Story Writing in Hindi',
                'Hindi Literature Analysis',
                'Vocabulary Building'
            ],
            'Computer' => [
                'Programming Basics',
                'Software Application Project',
                'Digital Presentation',
                'Coding Challenge',
                'Technology Research'
            ],
            'Art' => [
                'Drawing and Sketching',
                'Painting Techniques',
                'Art History Research',
                'Creative Design Project',
                'Art Portfolio Development'
            ],
            'Physical Education' => [
                'Fitness Assessment',
                'Sports Skills Practice',
                'Health and Wellness Project',
                'Physical Activity Log',
                'Sports Rules and Regulations'
            ]
        ];

        $subjectTitles = $titles[$subject->name] ?? ['General Assignment'];
        $title = $subjectTitles[array_rand($subjectTitles)];

        // Generate description based on subject
        $descriptions = [
            'Mathematics' => 'Complete the following mathematical problems. Show all your work and provide clear explanations for your solutions.',
            'English' => 'Read the provided text carefully and answer the questions. Pay attention to grammar, vocabulary, and comprehension.',
            'Science' => 'Conduct research on the given topic and present your findings. Include proper scientific methodology and conclusions.',
            'Social Studies' => 'Research the historical/cultural topic and write a comprehensive report with proper citations.',
            'Hindi' => 'Complete the Hindi language exercises. Focus on grammar, vocabulary, and proper pronunciation.',
            'Computer' => 'Complete the programming/computer science tasks. Follow the instructions carefully and test your solutions.',
            'Art' => 'Create an artistic project based on the given theme. Use your creativity and artistic skills.',
            'Physical Education' => 'Complete the physical activities and document your progress. Focus on health and fitness goals.'
        ];

        $description = $descriptions[$subject->name] ?? 'Complete the assignment as per the given instructions.';

        return [
            'title' => $title,
            'description' => $description,
            'institution_id' => $subjectAssignment->institution_id,
            'teacher_id' => $subjectAssignment->teacher_id,
            'class_id' => $subjectAssignment->class_id,
            'section_id' => $subjectAssignment->section_id,
            'subject_id' => $subjectAssignment->subject_id,
            'due_date' => Carbon::now()->addDays(rand(3, 15)),
            'assignment_file' => 'teacher/uploads/assignment/' . strtolower(str_replace(' ', '_', $subject->name)) . '_assignment.pdf',
            'status' => 1,
        ];
    }
}
