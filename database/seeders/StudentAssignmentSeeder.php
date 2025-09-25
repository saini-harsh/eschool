<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Student;
use App\Models\StudentAssignment;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class StudentAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some assignments and students to create submissions
        $assignments = Assignment::where('status', 1)->get();
        $students = Student::where('status', 1)->get();

        $submissions = [];

        foreach ($assignments as $assignment) {
            // Get students from the same class and section as the assignment
            $eligibleStudents = $students->where('class_id', $assignment->class_id)
                                       ->where('section_id', $assignment->section_id);

            foreach ($eligibleStudents->take(rand(3, 8)) as $student) {
                $dueDate = Carbon::parse($assignment->due_date);
                $submissionDate = null;
                $status = 'pending';
                $marks = null;
                $feedback = null;

                // Randomly decide if student submitted (70% chance)
                if (rand(1, 10) <= 7) {
                    $submissionDate = $dueDate->copy()->addDays(rand(-2, 3));
                    
                    if ($submissionDate->gt($dueDate)) {
                        $status = 'late';
                    } else {
                        $status = 'submitted';
                    }

                    // Randomly decide if teacher graded (60% chance for submitted assignments)
                    if (rand(1, 10) <= 6) {
                        $marks = rand(60, 100);
                        $feedback = $this->getRandomFeedback($marks);
                        $status = 'graded';
                    }
                }

                $submissions[] = [
                    'assignment_id' => $assignment->id,
                    'student_id' => $student->id,
                    'submitted_file' => $submissionDate ? 'student/uploads/assignments/' . $student->id . '_assignment_' . $assignment->id . '.pdf' : null,
                    'submission_date' => $submissionDate,
                    'status' => $status,
                    'remarks' => $submissionDate ? $this->getRandomRemarks() : null,
                    'marks' => $marks,
                    'feedback' => $feedback,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

            // Insert submissions in batches, but only if they don't exist
            $newSubmissions = [];
            foreach ($submissions as $submission) {
                if (!StudentAssignment::where('assignment_id', $submission['assignment_id'])
                    ->where('student_id', $submission['student_id'])
                    ->exists()) {
                    $newSubmissions[] = $submission;
                }
            }

            if (!empty($newSubmissions)) {
                foreach (array_chunk($newSubmissions, 100) as $chunk) {
                    StudentAssignment::insert($chunk);
                }
            }

            $this->command->info('Student assignment submissions seeded successfully!');
    }

    private function getRandomFeedback($marks)
    {
        $feedbacks = [
            'Excellent work! Your understanding of the concepts is very clear.',
            'Good effort! Try to be more detailed in your explanations.',
            'Well done! Your analysis shows good critical thinking.',
            'Nice work! Consider adding more examples to support your arguments.',
            'Good submission! Your presentation could be improved.',
            'Excellent! Your work demonstrates thorough understanding.',
            'Well written! Try to include more references next time.',
            'Good job! Your conclusions are well-supported.',
            'Outstanding work! Your creativity really shows.',
            'Very good! Your methodology is sound and well-explained.',
            'Good effort! Consider reviewing the formatting guidelines.',
            'Excellent analysis! Your insights are valuable.',
            'Well done! Your work meets all the requirements.',
            'Good submission! Your research is comprehensive.',
            'Excellent work! Your presentation is clear and engaging.',
        ];

        return $feedbacks[array_rand($feedbacks)];
    }

    private function getRandomRemarks()
    {
        $remarks = [
            'Completed as per instructions.',
            'Worked on this assignment with full dedication.',
            'Hope this meets the requirements.',
            'Please let me know if any changes are needed.',
            'Submitted on time as requested.',
            'Completed with help from study group.',
            'Worked independently on this assignment.',
            'Please review and provide feedback.',
            'Hope the quality meets expectations.',
            'Completed with additional research.',
            'Worked hard on this assignment.',
            'Please check if everything is correct.',
            'Submitted with confidence in the work.',
            'Completed following all guidelines.',
            'Hope this submission is satisfactory.',
        ];

        return $remarks[array_rand($remarks)];
    }
}
