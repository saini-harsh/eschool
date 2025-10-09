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
        // Get all institutions and classes
        $institutions = \App\Models\Institution::where('status', 1)->get();
        $classes = \App\Models\SchoolClass::where('status', 1)->get();
        
        $this->command->info("Found {$institutions->count()} institutions and {$classes->count()} classes.");
        
        $sectionNames = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'];
        $sectionCount = 0;

        foreach ($institutions as $institution) {
            $institutionClasses = $classes->where('institution_id', $institution->id);
            $this->command->info("Institution {$institution->id} has {$institutionClasses->count()} classes.");
            
            foreach ($institutionClasses as $class) {
                // Create 2-4 sections per class
                $sectionsPerClass = rand(2, 4);
                $selectedSectionNames = array_slice($sectionNames, 0, $sectionsPerClass);
                
                foreach ($selectedSectionNames as $sectionName) {
                    // Check if section already exists
                    if (!Section::where('institution_id', $institution->id)
                        ->where('class_id', $class->id)
                        ->where('name', $sectionName)
                        ->exists()) {
                        
                        Section::create([
                            'name' => $sectionName,
                            'institution_id' => $institution->id,
                            'class_id' => $class->id,
                            'status' => 1,
                        ]);
                        $sectionCount++;
                        $this->command->info("Created section {$sectionName} for class {$class->name} in institution {$institution->id}");
                    }
                }
            }
        }
        
        $this->command->info("Sections seeded successfully! Created {$sectionCount} sections.");
    }
}
