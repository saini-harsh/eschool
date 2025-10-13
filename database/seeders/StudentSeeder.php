<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all institutions, teachers, classes, and sections
        $institutions = \App\Models\Institution::where('status', 1)->get();
        $teachers = \App\Models\Teacher::where('status', 1)->get();
        $classes = \App\Models\SchoolClass::where('status', 1)->get();
        $sections = \App\Models\Section::where('status', 1)->get();

        $studentCount = 0;

        foreach ($institutions as $institution) {
            $institutionTeachers = $teachers->where('institution_id', $institution->id);
            $institutionClasses = $classes->where('institution_id', $institution->id);

            if ($institutionTeachers->isEmpty() || $institutionClasses->isEmpty()) {
                $this->command->info("Skipping institution {$institution->id} - missing teachers or classes");
                continue;
            }

            foreach ($institutionClasses as $class) {
                // Get sections for this class
                $classSections = $sections->where('class_id', $class->id)
                    ->where('institution_id', $institution->id);

                if ($classSections->isEmpty()) {
                    $this->command->info("Skipping class {$class->name} - no sections found");
                    continue;
                }

                // Create 3-5 students per section
                foreach ($classSections as $section) {
                    $studentsPerSection = rand(3, 5);
                    $randomTeacher = $institutionTeachers->random();

                    for ($i = 1; $i <= $studentsPerSection; $i++) {
                        $firstName = $this->getRandomFirstName();
                        $lastName = $this->getRandomLastName();
                        $email = strtolower($firstName . '.' . $lastName . '@example.com');
                        
                        // Check if student already exists
                        if (!Student::where('email', $email)->exists()) {
                            Student::create([
                                'first_name' => $firstName,
                                'middle_name' => $this->getRandomMiddleName(),
                                'last_name' => $lastName,
                                'dob' => $this->getRandomDOB(),
                                'email' => $email,
                                'phone' => '98765' . rand(10000, 99999),
                                'gender' => rand(0, 1) ? 'Male' : 'Female',
                                'institution_code' => 'INS' . str_pad($institution->id, 3, '0', STR_PAD_LEFT),
                                'teacher_id' => $randomTeacher->id,
                                'institution_id' => $institution->id,
                                'class_id' => $class->id,
                                'section_id' => $section->id,
                                'address' => $this->getRandomAddress(),
                                'pincode' => '110' . rand(100, 999),
                                'district' => 'New Delhi',
                                'caste_tribe' => 'General',
                                'category' => 'General',
                                'blood_group' => $this->getRandomBloodGroup(),
                                'admission_date' => '2024-04-01',
                                'father_name' => 'Mr. ' . $this->getRandomFirstName() . ' ' . $lastName,
                                'father_phone' => '98765' . rand(10000, 99999),
                                'father_occupation' => $this->getRandomOccupation(),
                                'mother_name' => 'Mrs. ' . $this->getRandomFirstName() . ' ' . $lastName,
                                'mother_phone' => '98765' . rand(10000, 99999),
                                'mother_occupation' => $this->getRandomOccupation(),
                                'guardian_name' => 'Mr. ' . $this->getRandomFirstName() . ' ' . $lastName,
                                'guardian_phone' => '98765' . rand(10000, 99999),
                                'guardian_occupation' => $this->getRandomOccupation(),
                                'guardian_relation' => 'Father',
                                'guardian_address' => $this->getRandomAddress(),
                                // New document fields
                                'aadhaar_no' => $this->generateAadhaarNumber(),
                                'pan_no' => $this->generatePANNumber(),
                                'pen_no' => 'PEN' . rand(100000, 999999),
                                'password' => Hash::make('password'),
                                'decrypt_pw' => 'password', // Plain text password for reference
                                'admin_id' => 1, // Default admin ID
                                'status' => 1,
                            ]);
                            $studentCount++;
                        }
                    }
                }
            }
        }

        $this->command->info("Students seeded successfully! Created {$studentCount} students.");
    }

    private function getRandomFirstName()
    {
        $firstNames = ['Priya', 'Raj', 'Anita', 'Vikram', 'Neha', 'Arun', 'Kavita', 'Sanjay', 'Pooja', 'Rahul', 'Sneha', 'Amit', 'Deepika', 'Rohit', 'Kavya', 'Suresh', 'Meera', 'Vikash', 'Sunita', 'Ravi'];
        return $firstNames[array_rand($firstNames)];
    }

    private function getRandomLastName()
    {
        $lastNames = ['Sharma', 'Verma', 'Kumar', 'Singh', 'Patel', 'Reddy', 'Gupta', 'Agarwal', 'Jain', 'Malhotra', 'Chopra', 'Bansal', 'Goyal', 'Khanna', 'Mehta', 'Saxena', 'Tiwari', 'Yadav', 'Pandey', 'Mishra'];
        return $lastNames[array_rand($lastNames)];
    }

    private function getRandomMiddleName()
    {
        $middleNames = ['Kumari', 'Kumar', 'Singh', 'Devi', 'Prasad', 'Lal', 'Babu', 'Bai', 'Kumari', 'Kumar'];
        return $middleNames[array_rand($middleNames)];
    }

    private function getRandomDOB()
    {
        $start = strtotime('2000-01-01');
        $end = strtotime('2010-12-31');
        $timestamp = rand($start, $end);
        return date('Y-m-d', $timestamp);
    }

    private function getRandomAddress()
    {
        $addresses = [
            '123 Green Park, New Delhi',
            '456 Model Town, New Delhi',
            '789 Punjabi Bagh, New Delhi',
            '321 Lajpat Nagar, New Delhi',
            '654 Karol Bagh, New Delhi',
            '987 Connaught Place, New Delhi',
            '147 Janpath, New Delhi',
            '258 India Gate, New Delhi'
        ];
        return $addresses[array_rand($addresses)];
    }

    private function getRandomBloodGroup()
    {
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        return $bloodGroups[array_rand($bloodGroups)];
    }

    private function getRandomOccupation()
    {
        $occupations = ['Engineer', 'Teacher', 'Doctor', 'Businessman', 'Government Employee', 'Private Employee', 'Farmer', 'Shopkeeper'];
        return $occupations[array_rand($occupations)];
    }

    private function generateAadhaarNumber()
    {
        // Generate a 12-digit Aadhaar-like number
        return str_pad(rand(100000000000, 999999999999), 12, '0', STR_PAD_LEFT);
    }

    private function generatePANNumber()
    {
        // Generate a PAN-like number (5 letters + 4 digits + 1 letter)
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pan = '';
        
        // First 5 letters
        for ($i = 0; $i < 5; $i++) {
            $pan .= $letters[rand(0, strlen($letters) - 1)];
        }
        
        // 4 digits
        $pan .= rand(1000, 9999);
        
        // Last letter
        $pan .= $letters[rand(0, strlen($letters) - 1)];
        
        return $pan;
    }
}