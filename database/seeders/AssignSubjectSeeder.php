<?php

namespace Database\Seeders;

use App\Models\AssignSubject;
use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class AssignSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $assignments = [
            // Green Valley School (institution_id = 1)
            // Rajesh Sharma (teacher_id = 1) - Mathematics and Science
            [
                'institution_id' => 1,
                'class_id' => 1, // Class 1
                'section_id' => 1,
                'subject_id' => 1, // Mathematics
                'teacher_id' => 1, // Rajesh Sharma
                'status' => 1,
            ],
            [
                'institution_id' => 1,
                'class_id' => 6, // Class 3
                'section_id' => 1,
                'subject_id' => 3, // Science
                'teacher_id' => 1, // Rajesh Sharma
                'status' => 1,
            ],
            [
                'institution_id' => 1,
                'class_id' => 4, // Class 1
                'section_id' => 1,
                'subject_id' => 1, // Mathematics
                'teacher_id' => 1, // Rajesh Sharma
                'status' => 1,
            ],

            // Anita Mehra (teacher_id = 2) - English and Hindi
            [
                'institution_id' => 1,
                'class_id' => 5, // Class 2
                'section_id' => 2,
                'subject_id' => 2, // English
                'teacher_id' => 2, // Anita Mehra
                'status' => 1,
            ],
            [
                'institution_id' => 1,
                'class_id' => 7, // Class 4
                'section_id' => 3,
                'subject_id' => 5, // Hindi
                'teacher_id' => 2, // Anita Mehra
                'status' => 1,
            ],

            // Sunrise Public School (institution_id = 2)
            // Vikram Kumar (teacher_id = 3) - Computer and Art
            [
                'institution_id' => 2,
                'class_id' => 8, // Class 5
                'section_id' => 1,
                'subject_id' => 6, // Computer
                'teacher_id' => 3, // Vikram Kumar
                'status' => 1,
            ],
            [
                'institution_id' => 2,
                'class_id' => 10, // Class 7
                'section_id' => 4,
                'subject_id' => 7, // Art
                'teacher_id' => 3, // Vikram Kumar
                'status' => 1,
            ],

            // Neha Singh (teacher_id = 4) - Social Studies and Science
            [
                'institution_id' => 2,
                'class_id' => 9, // Class 6
                'section_id' => 2,
                'subject_id' => 4, // Social Studies
                'teacher_id' => 4, // Neha Singh
                'status' => 1,
            ],
            [
                'institution_id' => 2,
                'class_id' => 5, // Class 2
                'section_id' => 2,
                'subject_id' => 3, // Science
                'teacher_id' => 4, // Neha Singh
                'status' => 1,
            ],

            // Delhi International School (institution_id = 3)
            // Arun Patel (teacher_id = 5) - Mathematics and Science
            [
                'institution_id' => 3,
                'class_id' => 11, // Class 8
                'section_id' => 1,
                'subject_id' => 1, // Mathematics
                'teacher_id' => 5, // Arun Patel
                'status' => 1,
            ],
            [
                'institution_id' => 3,
                'class_id' => 11, // Class 8
                'section_id' => 3,
                'subject_id' => 3, // Science
                'teacher_id' => 5, // Arun Patel
                'status' => 1,
            ],

            // Kavita Reddy (teacher_id = 6) - English and Social Studies
            [
                'institution_id' => 3,
                'class_id' => 12, // Class 9
                'section_id' => 2,
                'subject_id' => 2, // English
                'teacher_id' => 6, // Kavita Reddy
                'status' => 1,
            ],
            [
                'institution_id' => 3,
                'class_id' => 12, // Class 9
                'section_id' => 1,
                'subject_id' => 4, // Social Studies
                'teacher_id' => 6, // Kavita Reddy
                'status' => 1,
            ],

            // Modern Public School (institution_id = 4)
            // Sanjay Gupta (teacher_id = 7) - Physical Education and Science
            [
                'institution_id' => 4,
                'class_id' => 8, // Class 5
                'section_id' => 5,
                'subject_id' => 8, // Physical Education
                'teacher_id' => 7, // Sanjay Gupta
                'status' => 1,
            ],
            [
                'institution_id' => 4,
                'class_id' => 11, // Class 8
                'section_id' => 3,
                'subject_id' => 3, // Science
                'teacher_id' => 7, // Sanjay Gupta
                'status' => 1,
            ],

            // Pooja Sharma (teacher_id = 8) - Science and English
            [
                'institution_id' => 4,
                'class_id' => 9, // Class 6
                'section_id' => 1,
                'subject_id' => 3, // Science
                'teacher_id' => 8, // Pooja Sharma
                'status' => 1,
            ],
            [
                'institution_id' => 4,
                'class_id' => 10, // Class 7
                'section_id' => 2,
                'subject_id' => 2, // English
                'teacher_id' => 8, // Pooja Sharma
                'status' => 1,
            ],
        ];

        foreach ($assignments as $assignment) {
            // Check if assignment already exists
            if (!AssignSubject::where('institution_id', $assignment['institution_id'])
                ->where('class_id', $assignment['class_id'])
                ->where('section_id', $assignment['section_id'])
                ->where('subject_id', $assignment['subject_id'])
                ->where('teacher_id', $assignment['teacher_id'])
                ->exists()) {
                AssignSubject::create($assignment);
            }
        }

        $this->command->info('Subject assignments seeded successfully!');
    }
}
