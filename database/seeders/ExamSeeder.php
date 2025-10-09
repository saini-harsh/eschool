<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\Institution;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\ExamType;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $institutions = Institution::where('status', 1)->get();
        $classes = SchoolClass::where('status', 1)->get();
        $sections = Section::where('status', 1)->get();
        $subjects = Subject::where('status', 1)->get();
        $teachers = Teacher::where('status', 1)->get();
        $examTypes = ExamType::where('status', 1)->get();

        $examCount = 0;

        foreach ($institutions as $institution) {
            $institutionClasses = $classes->where('institution_id', $institution->id);
            $institutionSubjects = $subjects->where('institution_id', $institution->id);
            $institutionTeachers = $teachers->where('institution_id', $institution->id);
            $institutionExamTypes = $examTypes->where('institution_id', $institution->id);

            if ($institutionClasses->isEmpty() || $institutionSubjects->isEmpty() || 
                $institutionTeachers->isEmpty() || $institutionExamTypes->isEmpty()) {
                $this->command->info("Skipping institution {$institution->id} - missing required data");
                continue;
            }

            // Create 2-4 exams per class
            foreach ($institutionClasses as $class) {
                $classSections = $sections->where('class_id', $class->id)
                    ->where('institution_id', $institution->id);

                if ($classSections->isEmpty()) {
                    $this->command->info("Skipping class {$class->name} - no sections found");
                    continue;
                }

                $examsPerClass = rand(2, 4);
                $randomExamTypes = $institutionExamTypes->random(min($examsPerClass, $institutionExamTypes->count()));
                $randomSubjects = $institutionSubjects->random(min(3, $institutionSubjects->count()));
                $randomTeacher = $institutionTeachers->random();

                foreach ($randomExamTypes as $index => $examType) {
                    $subject = $randomSubjects->random();
                    $startDate = Carbon::now()->addDays(rand(1, 30));
                    $endDate = $startDate->copy()->addDays(rand(1, 3));

                    // Check if exam already exists
                    if (!Exam::where('institution_id', $institution->id)
                        ->where('class_id', $class->id)
                        ->where('exam_type_id', $examType->id)
                        ->exists()) {
                        
                        Exam::create([
                            'institution_id' => $institution->id,
                            'exam_type_id' => $examType->id,
                            'title' => $this->getExamName($examType->title, $subject->name, $class->name),
                            'code' => strtoupper(str_replace(' ', '_', $examType->title)) . '_' . $class->id . '_' . $institution->id,
                            'class_id' => $class->id,
                            'section_id' => null, // Can be set later for specific sections
                            'start_date' => $startDate,
                            'end_date' => $endDate,
                            'morning_time' => $this->getRandomStartTime(),
                            'evening_time' => $this->getRandomEndTime(),
                            'subject_dates' => json_encode([
                                $subject->name => $startDate->format('Y-m-d')
                            ]),
                            'morning_subjects' => json_encode([$subject->name]),
                            'evening_subjects' => json_encode([]),
                        ]);
                        $examCount++;
                    }
                }
            }
        }

        $this->command->info("Exams seeded successfully! Created {$examCount} exams.");
    }

    private function getExamName($examTypeTitle, $subjectName, $className)
    {
        return "{$examTypeTitle} - {$subjectName} - {$className}";
    }

    private function getExamDescription($examTypeName, $subjectName)
    {
        $descriptions = [
            'Comprehensive examination covering all topics',
            'Assessment based on recent curriculum',
            'Evaluation of student understanding and knowledge',
            'Test to measure academic progress',
            'Examination to assess learning outcomes'
        ];
        
        return $descriptions[array_rand($descriptions)];
    }

    private function getRandomStartTime()
    {
        $times = ['09:00:00', '10:00:00', '11:00:00', '14:00:00', '15:00:00'];
        return $times[array_rand($times)];
    }

    private function getRandomEndTime()
    {
        $times = ['11:00:00', '12:00:00', '13:00:00', '16:00:00', '17:00:00'];
        return $times[array_rand($times)];
    }
}
