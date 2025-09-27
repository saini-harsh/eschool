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
        $assignments = [
            // Green Valley School (institution_id = 1)
            // Rajesh Sharma (teacher_id = 1) - Class Teacher for multiple classes
            [
                'institution_id' => 1,
                'class_id' => 1, // Class 1
                'section_id' => 1,
                'teacher_id' => 1, // Rajesh Sharma
                'status' => 1,
            ],
            [
                'institution_id' => 1,
                'class_id' => 4, // Class 1
                'section_id' => 2,
                'teacher_id' => 1, // Rajesh Sharma
                'status' => 1,
            ],
            [
                'institution_id' => 1,
                'class_id' => 6, // Class 3
                'section_id' => 1,
                'teacher_id' => 1, // Rajesh Sharma
                'status' => 1,
            ],

            // Anita Mehra (teacher_id = 2) - Class Teacher for multiple classes
            [
                'institution_id' => 1,
                'class_id' => 5, // Class 2
                'section_id' => 1,
                'teacher_id' => 2, // Anita Mehra
                'status' => 1,
            ],
            [
                'institution_id' => 1,
                'class_id' => 5, // Class 2
                'section_id' => 2,
                'teacher_id' => 2, // Anita Mehra
                'status' => 1,
            ],
            [
                'institution_id' => 1,
                'class_id' => 7, // Class 4
                'section_id' => 1,
                'teacher_id' => 2, // Anita Mehra
                'status' => 1,
            ],

            // Sunrise Public School (institution_id = 2)
            // Vikram Kumar (teacher_id = 3) - Class Teacher for multiple classes
            [
                'institution_id' => 2,
                'class_id' => 8, // Class 5
                'section_id' => 1,
                'teacher_id' => 3, // Vikram Kumar
                'status' => 1,
            ],
            [
                'institution_id' => 2,
                'class_id' => 8, // Class 5
                'section_id' => 2,
                'teacher_id' => 3, // Vikram Kumar
                'status' => 1,
            ],
            [
                'institution_id' => 2,
                'class_id' => 10, // Class 7
                'section_id' => 1,
                'teacher_id' => 3, // Vikram Kumar
                'status' => 1,
            ],

            // Neha Singh (teacher_id = 4) - Class Teacher for multiple classes
            [
                'institution_id' => 2,
                'class_id' => 9, // Class 6
                'section_id' => 1,
                'teacher_id' => 4, // Neha Singh
                'status' => 1,
            ],
            [
                'institution_id' => 2,
                'class_id' => 9, // Class 6
                'section_id' => 2,
                'teacher_id' => 4, // Neha Singh
                'status' => 1,
            ],
            [
                'institution_id' => 2,
                'class_id' => 11, // Class 8
                'section_id' => 1,
                'teacher_id' => 4, // Neha Singh
                'status' => 1,
            ],

            // Delhi International School (institution_id = 3)
            // Arun Patel (teacher_id = 5) - Class Teacher for multiple classes
            [
                'institution_id' => 3,
                'class_id' => 11, // Class 8
                'section_id' => 2,
                'teacher_id' => 5, // Arun Patel
                'status' => 1,
            ],
            [
                'institution_id' => 3,
                'class_id' => 11, // Class 8
                'section_id' => 3,
                'teacher_id' => 5, // Arun Patel
                'status' => 1,
            ],
            [
                'institution_id' => 3,
                'class_id' => 12, // Class 9
                'section_id' => 1,
                'teacher_id' => 5, // Arun Patel
                'status' => 1,
            ],

            // Kavita Reddy (teacher_id = 6) - Class Teacher for multiple classes
            [
                'institution_id' => 3,
                'class_id' => 12, // Class 9
                'section_id' => 2,
                'teacher_id' => 6, // Kavita Reddy
                'status' => 1,
            ],
            [
                'institution_id' => 3,
                'class_id' => 12, // Class 9
                'section_id' => 3,
                'teacher_id' => 6, // Kavita Reddy
                'status' => 1,
            ],
            [
                'institution_id' => 3,
                'class_id' => 10, // Class 7
                'section_id' => 2,
                'teacher_id' => 6, // Kavita Reddy
                'status' => 1,
            ],

            // Modern Public School (institution_id = 4)
            // Sanjay Gupta (teacher_id = 7) - Class Teacher for multiple classes
            [
                'institution_id' => 4,
                'class_id' => 8, // Class 5
                'section_id' => 3,
                'teacher_id' => 7, // Sanjay Gupta
                'status' => 1,
            ],
            [
                'institution_id' => 4,
                'class_id' => 8, // Class 5
                'section_id' => 4,
                'teacher_id' => 7, // Sanjay Gupta
                'status' => 1,
            ],
            [
                'institution_id' => 4,
                'class_id' => 8, // Class 5
                'section_id' => 5,
                'teacher_id' => 7, // Sanjay Gupta
                'status' => 1,
            ],

            // Pooja Sharma (teacher_id = 8) - Class Teacher for multiple classes
            [
                'institution_id' => 4,
                'class_id' => 9, // Class 6
                'section_id' => 3,
                'teacher_id' => 8, // Pooja Sharma
                'status' => 1,
            ],
            [
                'institution_id' => 4,
                'class_id' => 9, // Class 6
                'section_id' => 4,
                'teacher_id' => 8, // Pooja Sharma
                'status' => 1,
            ],
            [
                'institution_id' => 4,
                'class_id' => 10, // Class 7
                'section_id' => 3,
                'teacher_id' => 8, // Pooja Sharma
                'status' => 1,
            ],

            // Additional assignments for better coverage
            // Green Valley School - More assignments
            [
                'institution_id' => 1,
                'class_id' => 3, // UKG
                'section_id' => 1,
                'teacher_id' => 1, // Rajesh Sharma
                'status' => 1,
            ],
            [
                'institution_id' => 1,
                'class_id' => 3, // UKG
                'section_id' => 2,
                'teacher_id' => 2, // Anita Mehra
                'status' => 1,
            ],

            // Sunrise Public School - More assignments
            [
                'institution_id' => 2,
                'class_id' => 7, // Class 4
                'section_id' => 1,
                'teacher_id' => 3, // Vikram Kumar
                'status' => 1,
            ],
            [
                'institution_id' => 2,
                'class_id' => 7, // Class 4
                'section_id' => 2,
                'teacher_id' => 4, // Neha Singh
                'status' => 1,
            ],

            // Delhi International School - More assignments
            [
                'institution_id' => 3,
                'class_id' => 10, // Class 7
                'section_id' => 3,
                'teacher_id' => 5, // Arun Patel
                'status' => 1,
            ],
            [
                'institution_id' => 3,
                'class_id' => 10, // Class 7
                'section_id' => 4,
                'teacher_id' => 6, // Kavita Reddy
                'status' => 1,
            ],

            // Modern Public School - More assignments
            [
                'institution_id' => 4,
                'class_id' => 11, // Class 8
                'section_id' => 1,
                'teacher_id' => 7, // Sanjay Gupta
                'status' => 1,
            ],
            [
                'institution_id' => 4,
                'class_id' => 11, // Class 8
                'section_id' => 2,
                'teacher_id' => 8, // Pooja Sharma
                'status' => 1,
            ],
        ];

        foreach ($assignments as $assignment) {
            // Check if assignment already exists
            if (!AssignClassTeacher::where('institution_id', $assignment['institution_id'])
                ->where('class_id', $assignment['class_id'])
                ->where('section_id', $assignment['section_id'])
                ->where('teacher_id', $assignment['teacher_id'])
                ->exists()) {
                AssignClassTeacher::create($assignment);
            }
        }

        $this->command->info('Class-teacher assignments seeded successfully!');
    }
}
