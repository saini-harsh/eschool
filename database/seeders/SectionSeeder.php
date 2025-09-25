<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sectionNames = ['A', 'B', 'C', 'D', 'E', 'F'];

        // Create sections for all institutions
        for ($institutionId = 1; $institutionId <= 4; $institutionId++) {
            foreach ($sectionNames as $name) {
                // Check if section already exists for this institution
                if (!Section::where('name', $name)->where('institution_id', $institutionId)->exists()) {
                    Section::create([
                        'name' => $name,
                        'institution_id' => $institutionId,
                        'status' => 1
                    ]);
                }
            }
        }
        $this->command->info('Sections seeded successfully!');
    }
}
