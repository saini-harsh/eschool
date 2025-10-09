<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AdmissionFee;
use App\Models\Institution;
use App\Models\SchoolClass;
use App\Models\Section;
use Faker\Factory as Faker;

class AdmissionFeeSeeder extends Seeder
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

        $admissionFeeNames = [
            'Admission Fee',
            'Registration Fee',
            'Application Fee',
            'Processing Fee',
            'Documentation Fee',
            'Enrollment Fee',
            'Development Fee',
            'Infrastructure Fee',
            'Library Fee',
            'Laboratory Fee',
            'Sports Fee',
            'Transportation Fee',
            'Security Fee',
            'Maintenance Fee',
            'Technology Fee'
        ];

        foreach ($institutions as $institution) {
            // Get classes for this institution
            $classes = SchoolClass::where('institution_id', $institution->id)->get();
            
            if ($classes->isEmpty()) {
                $this->command->warn("No classes found for institution: {$institution->name}");
                continue;
            }

            // Create 2-5 admission fees per institution
            $admissionFeeCount = $faker->numberBetween(2, 5);
            
            for ($i = 0; $i < $admissionFeeCount; $i++) {
                $class = $classes->random();
                $admissionFeeName = $faker->randomElement($admissionFeeNames);
                
                // Get sections for the selected class
                $sections = Section::where('class_id', $class->id)->get();
                
                // Determine amount based on class level
                $baseAmount = match($class->name) {
                    'Nursery', 'LKG', 'UKG' => $faker->numberBetween(1000, 3000),
                    'Class 1', 'Class 2', 'Class 3' => $faker->numberBetween(2000, 5000),
                    'Class 4', 'Class 5', 'Class 6' => $faker->numberBetween(3000, 7000),
                    'Class 7', 'Class 8' => $faker->numberBetween(4000, 8000),
                    'Class 9', 'Class 10' => $faker->numberBetween(5000, 10000),
                    'Class 11', 'Class 12' => $faker->numberBetween(6000, 12000),
                    default => $faker->numberBetween(2000, 8000)
                };

                // Create effective dates
                $effectiveFrom = $faker->dateTimeBetween('-6 months', '+6 months');
                $effectiveUntil = $faker->boolean(30) ? $faker->dateTimeBetween($effectiveFrom, '+2 years') : null;

                // Create admission fee
                AdmissionFee::create([
                    'name' => $admissionFeeName,
                    'description' => $faker->optional(0.6)->sentence(),
                    'institution_id' => $institution->id,
                    'class_id' => $class->id,
                    'section_id' => $sections->isNotEmpty() && $faker->boolean(40) ? $sections->random()->id : null,
                    'amount' => $baseAmount,
                    'effective_from' => $effectiveFrom->format('Y-m-d'),
                    'effective_until' => $effectiveUntil ? $effectiveUntil->format('Y-m-d') : null,
                    'is_mandatory' => $faker->boolean(80), // 80% chance of being mandatory
                    'status' => $faker->boolean(90), // 90% chance of being active
                ]);
            }
        }

        $this->command->info('Admission fees seeded successfully!');
    }
}
