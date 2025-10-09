<?php

namespace Database\Seeders;

use App\Models\EmailSms;
use App\Models\Institution;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EmailSmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $institutions = Institution::where('status', 1)->get();
        $students = Student::where('status', 1)->get();
        $teachers = Teacher::where('status', 1)->get();
        $classes = SchoolClass::where('status', 1)->get();
        $sections = Section::where('status', 1)->get();

        $emailSmsCount = 0;

        foreach ($institutions as $institution) {
            $institutionStudents = $students->where('institution_id', $institution->id);
            $institutionTeachers = $teachers->where('institution_id', $institution->id);
            $institutionClasses = $classes->where('institution_id', $institution->id);

            // Create 5-10 email/sms records per institution
            $recordsPerInstitution = rand(5, 10);

            for ($i = 1; $i <= $recordsPerInstitution; $i++) {
                $sendThrough = ['email', 'sms', 'whatsapp'][rand(0, 2)];
                $recipientType = ['group', 'individual', 'class'][rand(0, 2)];
                
                $recipients = $this->getRecipients($recipientType, $institutionStudents, $institutionTeachers, $institutionClasses, $sections);
                
                if (empty($recipients)) {
                    continue; // Skip if no recipients
                }

                EmailSms::create([
                    'institution_id' => $institution->id,
                    'title' => $this->getRandomTitle($sendThrough),
                    'description' => $this->getRandomDescription($sendThrough),
                    'send_through' => $sendThrough,
                    'recipient_type' => $recipientType,
                    'recipients' => json_encode($recipients),
                    'status' => ['pending', 'sent', 'failed'][rand(0, 2)],
                    'sent_at' => rand(0, 1) ? Carbon::now()->subDays(rand(1, 30)) : null,
                ]);
                $emailSmsCount++;
            }
        }

        $this->command->info("Email/SMS records seeded successfully! Created {$emailSmsCount} records.");
    }

    private function getRecipients($recipientType, $students, $teachers, $classes, $sections)
    {
        switch ($recipientType) {
            case 'individual':
                $allUsers = $students->merge($teachers);
                return $allUsers->random(min(3, $allUsers->count()))->pluck('id')->toArray();
                
            case 'class':
                $selectedClasses = $classes->random(min(2, $classes->count()));
                $recipients = [];
                foreach ($selectedClasses as $class) {
                    $classSections = $sections->where('class_id', $class->id);
                    foreach ($classSections as $section) {
                        $recipients[] = [
                            'class_id' => $class->id,
                            'section_id' => $section->id,
                            'type' => 'class'
                        ];
                    }
                }
                return $recipients;
                
            case 'group':
            default:
                return [
                    ['type' => 'all_students'],
                    ['type' => 'all_teachers'],
                    ['type' => 'all_parents']
                ];
        }
    }

    private function getRandomTitle($sendThrough)
    {
        $titles = [
            'email' => [
                'Important Notice',
                'Academic Update',
                'Parent Meeting Invitation',
                'Exam Schedule',
                'Holiday Announcement',
                'Fee Payment Reminder',
                'School Event Notification',
                'Academic Performance Report'
            ],
            'sms' => [
                'Attendance Alert',
                'Fee Due Reminder',
                'Exam Tomorrow',
                'Parent Meeting',
                'Holiday Notice',
                'School Closed',
                'Result Published',
                'Urgent Notice'
            ],
            'whatsapp' => [
                'School Update',
                'Quick Notice',
                'Event Reminder',
                'Important Alert',
                'Academic News',
                'School Announcement',
                'Parent Communication',
                'Student Update'
            ]
        ];

        $sendThroughTitles = $titles[$sendThrough] ?? $titles['email'];
        return $sendThroughTitles[array_rand($sendThroughTitles)];
    }

    private function getRandomDescription($sendThrough)
    {
        $descriptions = [
            'email' => [
                'This is an important communication regarding your child\'s academic progress.',
                'Please find attached the latest academic report and updates.',
                'We would like to inform you about the upcoming school events.',
                'Kindly review the attached documents and respond at your earliest convenience.',
                'This communication contains important information about school policies.'
            ],
            'sms' => [
                'Your child was absent today. Please contact the school office.',
                'Fee payment is due. Please make payment at the earliest.',
                'Exam scheduled for tomorrow. Please ensure your child is prepared.',
                'Parent-teacher meeting scheduled. Your presence is requested.',
                'School will remain closed tomorrow due to maintenance work.'
            ],
            'whatsapp' => [
                'Quick update: School event tomorrow at 10 AM.',
                'Reminder: Parent meeting scheduled for this weekend.',
                'Important: Please check your child\'s homework completion.',
                'Notice: School will close early today due to weather conditions.',
                'Update: New academic calendar has been uploaded.'
            ]
        ];

        $sendThroughDescriptions = $descriptions[$sendThrough] ?? $descriptions['email'];
        return $sendThroughDescriptions[array_rand($sendThroughDescriptions)];
    }
}
