<?php

namespace Database\Seeders;

use App\Models\Invigilator;
use App\Models\Institution;
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Exam;
use App\Models\ClassRoom;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class InvigilatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $institutions = Institution::where('status', 1)->get();
        $teachers = Teacher::where('status', 1)->get();
        $classes = SchoolClass::where('status', 1)->get();
        $sections = Section::where('status', 1)->get();
        $exams = Exam::all();

        $invigilatorCount = 0;

        foreach ($institutions as $institution) {
            $institutionTeachers = $teachers->where('institution_id', $institution->id);
            $institutionClasses = $classes->where('institution_id', $institution->id);
            $institutionSections = $sections->where('institution_id', $institution->id);
            $institutionExams = $exams->where('institution_id', $institution->id);

            if ($institutionTeachers->isEmpty() || $institutionClasses->isEmpty() || 
                $institutionSections->isEmpty() || $institutionExams->isEmpty()) {
                $this->command->info("Skipping institution {$institution->id} - missing required data");
                continue;
            }

            // Rooms are global; pick active rooms to assign invigilators per exam date
            $rooms = ClassRoom::where('status', true)->get();
            if ($rooms->isEmpty()) {
                $this->command->info("Skipping institution {$institution->id} - no rooms available");
                continue;
            }

            foreach ($institutionExams as $exam) {
                $dates = [];
                if ($exam->subject_dates) {
                    $decoded = is_array($exam->subject_dates) ? $exam->subject_dates : json_decode($exam->subject_dates, true);
                    if (is_array($decoded)) {
                        $dates = $decoded;
                    }
                }

                // Fallback: use start_date if no subject_dates present
                if (empty($dates) && $exam->start_date) {
                    $dates = [$exam->start_date];
                }

                foreach ($dates as $date) {
                    // Assign 2-3 rooms per date (or less if not enough rooms)
                    $roomsForDate = $rooms->shuffle()->take(min(3, $rooms->count()));
                    foreach ($roomsForDate as $room) {
                        $teacher = $institutionTeachers->random();

                        // Avoid duplicates per exam/date/room
                        $exists = Invigilator::where('institution_id', $institution->id)
                            ->where('exam_id', $exam->id)
                            ->where('date', $date)
                            ->where('class_room_id', $room->id)
                            ->exists();
                        if ($exists) {
                            continue;
                        }

                        Invigilator::create([
                            'teacher_id' => $teacher->id,
                            'class_id' => null,
                            'section_id' => null,
                            'class_room_id' => $room->id,
                            'exam_id' => $exam->id,
                            'date' => $date,
                            'time' => $exam->morning_time ?? '09:00:00',
                            'institution_id' => $institution->id,
                        ]);
                        $invigilatorCount++;
                    }
                }
            }
        }

        $this->command->info("Invigilators seeded successfully! Created {$invigilatorCount} invigilator assignments.");
    }
}
