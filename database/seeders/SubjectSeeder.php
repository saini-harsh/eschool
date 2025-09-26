<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            ['name' => 'Mathematics', 'code' => 'MATH', 'type' => 'theory'],
            ['name' => 'English', 'code' => 'ENG', 'type' => 'theory'],
            ['name' => 'Science', 'code' => 'SCI', 'type' => 'theory'],
            ['name' => 'Social Studies', 'code' => 'SST', 'type' => 'theory'],
            ['name' => 'Hindi', 'code' => 'HIN', 'type' => 'theory'],
            ['name' => 'Computer', 'code' => 'COMP', 'type' => 'practical'],
            ['name' => 'Art', 'code' => 'ART', 'type' => 'practical'],
            ['name' => 'Physical Education', 'code' => 'PE', 'type' => 'practical'],
        ];

        // Create subjects for all institutions
        for ($institutionId = 1; $institutionId <= 4; $institutionId++) {
            foreach ($subjects as $subject) {
                // Check if subject already exists for this institution
                if (!Subject::where('name', $subject['name'])->where('institution_id', $institutionId)->exists()) {
                    Subject::create([
                        'name' => $subject['name'],
                        'code' => $subject['code'],
                        'type' => $subject['type'],
                        'status' => 1,
                        'institution_id' => $institutionId,
                        'class_id' => 1,    // you can link to class later
                    ]);
                }
            }
        }
        $this->command->info('Subjects seeded successfully!');
    }
}
