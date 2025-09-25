<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use Illuminate\Database\Seeder;

class SchoolClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classNames = [
            'Nursery',
            'LKG',
            'UKG',
            'Class 1',
            'Class 2',
            'Class 3',
            'Class 4',
            'Class 5',
            'Class 6',
            'Class 7',
            'Class 8',
            'Class 9',
        ];

        // Create classes for all institutions
        for ($institutionId = 1; $institutionId <= 4; $institutionId++) {
            foreach ($classNames as $name) {
                // Check if class already exists for this institution
                if (!SchoolClass::where('name', $name)->where('institution_id', $institutionId)->exists()) {
                    SchoolClass::create([
                        'name' => $name,
                        'section_ids' => json_encode(['1','2','3','4','5']), // default sections
                        'student_count' => 0,
                        'institution_id' => $institutionId,
                        'admin_id' => 1,
                        'status' => 1,
                    ]);
                }
            }
        }
        $this->command->info('School classes seeded successfully!');
    }
}
