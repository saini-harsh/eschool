<?php

namespace Database\Seeders;

use App\Models\Invigilator;
use App\Models\Institution;
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Exam;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class InvigilatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $institutions = Institution::where('status', 1)->get();
        $teachers = Teacher::where('status', 1)->get();
        $classes = SchoolClass::where('status', 1)->get();
        $sections = Section::where('status', 1)->get();
        $exams = Exam::all();

        $invigilatorCount = 0;

        foreach ($institutions as $institution) {
            $institutionTeachers = $teachers->where('institution_id', $institution->id);
            $institutionClasses = $classes->where('institution_id', $institution->id);
            $institutionSections = $sections->where('institution_id', $institution->id);
            $institutionExams = $exams->where('institution_id', $institution->id);

            if ($institutionTeachers->isEmpty() || $institutionClasses->isEmpty() || 
                $institutionSections->isEmpty() || $institutionExams->isEmpty()) {
                $this->command->info("Skipping institution {$institution->id} - missing required data");
                continue;
            }

            // Create invigilator assignments for exams
            foreach ($institutionExams as $exam) {
                $examClass = $institutionClasses->where('id', $exam->class_id)->first();
                $examSections = $institutionSections->where('class_id', $exam->class_id);
                
                if (!$examClass || $examSections->isEmpty()) {
                    continue;
                }

                // Assign 1-2 invigilators per exam
                $invigilatorsPerExam = rand(1, 2);
                $selectedTeachers = $institutionTeachers->random(min($invigilatorsPerExam, $institutionTeachers->count()));
                
                foreach ($selectedTeachers as $teacher) {
                    $randomSection = $examSections->random();
                    
                    // Check if invigilator assignment already exists
                    if (!Invigilator::where('teacher_id', $teacher->id)
                        ->where('exam_id', $exam->id)
                        ->where('date', $exam->start_date)
                        ->exists()) {
                        
                        Invigilator::create([
                            'teacher_id' => $teacher->id,
                            'class_id' => $exam->class_id,
                            'section_id' => $randomSection->id,
                            'exam_id' => $exam->id,
                            'date' => $exam->start_date,
                            'time' => $exam->morning_time ?? '09:00:00',
                            'institution_id' => $institution->id,
                        ]);
                        $invigilatorCount++;
                    }
                }
            }
        }

        $this->command->info("Invigilators seeded successfully! Created {$invigilatorCount} invigilator assignments.");
    }
}
