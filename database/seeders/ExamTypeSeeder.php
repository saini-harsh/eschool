<?php

namespace Database\Seeders;

use App\Models\ExamType;
use App\Models\Institution;
use Illuminate\Database\Seeder;

class ExamTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $institutions = Institution::where('status', 1)->get();
        $examTypeCount = 0;

        foreach ($institutions as $institution) {
            $examTypes = [
                'Unit Test',
                'Monthly Test',
                'Quarterly Exam',
                'Half Yearly Exam',
                'Annual Exam',
                'Final Exam',
                'Pre-Board Exam',
                'Practice Test',
                'Mock Test',
                'Assessment Test'
            ];

            foreach ($examTypes as $examTypeName) {
                // Check if exam type already exists
                if (!ExamType::where('title', $examTypeName)
                    ->where('institution_id', $institution->id)
                    ->exists()) {
                    
                    ExamType::create([
                        'title' => $examTypeName,
                        'code' => strtoupper(str_replace(' ', '_', $examTypeName)) . '_' . $institution->id,
                        'description' => $this->getExamTypeDescription($examTypeName),
                        'institution_id' => $institution->id,
                        'status' => 1,
                    ]);
                    $examTypeCount++;
                }
            }
        }

        $this->command->info("Exam types seeded successfully! Created {$examTypeCount} exam types.");
    }

    private function getExamTypeDescription($examTypeName)
    {
        $descriptions = [
            'Unit Test' => 'Regular unit-wise assessment to evaluate student progress',
            'Monthly Test' => 'Monthly evaluation to track student performance',
            'Quarterly Exam' => 'Quarterly comprehensive examination',
            'Half Yearly Exam' => 'Mid-year comprehensive examination',
            'Annual Exam' => 'Year-end comprehensive examination',
            'Final Exam' => 'Final examination for the academic year',
            'Pre-Board Exam' => 'Practice examination before board exams',
            'Practice Test' => 'Practice examination for skill development',
            'Mock Test' => 'Simulated examination for preparation',
            'Assessment Test' => 'General assessment test for evaluation'
        ];

        return $descriptions[$examTypeName] ?? 'General examination type';
    }
}
