<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FeeStructure;
use App\Models\Institution;
use App\Models\SchoolClass;
use App\Models\Section;
use Faker\Factory as Faker;

class FeeStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        
        // Get all institutions
        $institutions = Institution::all();
        
        if ($institutions->isEmpty()) {
            $this->command->warn('No institutions found. Please run InstitutionSeeder first.');
            return;
        }

        $feeTypes = ['monthly', 'quarterly', 'yearly'];
        $feeNames = [
            'Tuition Fee',
            'Library Fee',
            'Laboratory Fee',
            'Sports Fee',
            'Transportation Fee',
            'Computer Fee',
            'Development Fee',
            'Examination Fee',
            'Activity Fee',
            'Maintenance Fee',
            'Security Fee',
            'Medical Fee',
            'Art & Craft Fee',
            'Music Fee',
            'Dance Fee'
        ];

        foreach ($institutions as $institution) {
            // Get classes for this institution
            $classes = SchoolClass::where('institution_id', $institution->id)->get();
            
            if ($classes->isEmpty()) {
                $this->command->warn("No classes found for institution: {$institution->name}");
                continue;
            }

            // Create 3-8 fee structures per institution
            $feeStructureCount = $faker->numberBetween(3, 8);
            
            for ($i = 0; $i < $feeStructureCount; $i++) {
                $class = $classes->random();
                $feeType = $faker->randomElement($feeTypes);
                $feeName = $faker->randomElement($feeNames);
                
                // Get sections for the selected class
                $sections = Section::where('class_id', $class->id)->get();
                
                // Determine amount based on fee type
                $baseAmount = $faker->numberBetween(500, 5000);
                $amount = match($feeType) {
                    'monthly' => $baseAmount,
                    'quarterly' => $baseAmount * 3,
                    'yearly' => $baseAmount * 12,
                    default => $baseAmount
                };

                // Create fee structure
                FeeStructure::create([
                    'name' => $feeName,
                    'description' => $faker->optional(0.7)->sentence(),
                    'institution_id' => $institution->id,
                    'class_id' => $class->id,
                    'section_id' => $sections->isNotEmpty() && $faker->boolean(60) ? $sections->random()->id : null,
                    'amount' => $amount,
                    'fee_type' => $feeType,
                    'due_date' => $faker->dateTimeBetween('-1 year', '+1 year')->format('Y-m-d'),
                    'status' => $faker->boolean(85), // 85% chance of being active
                ]);
            }
        }

        $this->command->info('Fee structures seeded successfully!');
    }
}
