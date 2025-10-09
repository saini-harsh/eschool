<?php

namespace Database\Seeders;

use App\Models\Routine;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\ClassRoom;
use Illuminate\Database\Seeder;

class RoutineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $timeSlots = [
            ['start' => '08:00', 'end' => '08:45'],
            ['start' => '08:45', 'end' => '09:30'],
            ['start' => '09:30', 'end' => '10:15'],
            ['start' => '10:15', 'end' => '11:00'],
            ['start' => '11:00', 'end' => '11:15'], // Break
            ['start' => '11:15', 'end' => '12:00'],
            ['start' => '12:00', 'end' => '12:45'],
            ['start' => '12:45', 'end' => '13:30'],
            ['start' => '13:30', 'end' => '14:15'],
            ['start' => '14:15', 'end' => '15:00'],
        ];

        // Get all institutions, classes, sections, subjects, teachers, and classrooms
        $institutions = [1, 2, 3, 4]; // Based on existing seeder data
        $classes = SchoolClass::whereIn('institution_id', $institutions)->get();
        $subjects = Subject::whereIn('institution_id', $institutions)->get();
        $teachers = Teacher::whereIn('institution_id', $institutions)->get();
        $classRooms = ClassRoom::where('status', 1)->get();

        $routineCount = 0;

        foreach ($institutions as $institutionId) {
            $institutionClasses = $classes->where('institution_id', $institutionId);
            $institutionSubjects = $subjects->where('institution_id', $institutionId);
            $institutionTeachers = $teachers->where('institution_id', $institutionId);

            // Skip if no data available for this institution (classrooms are optional)
            if ($institutionClasses->isEmpty() || $institutionSubjects->isEmpty() || $institutionTeachers->isEmpty()) {
                $this->command->info("Skipping institution {$institutionId} - missing required data (classes: {$institutionClasses->count()}, subjects: {$institutionSubjects->count()}, teachers: {$institutionTeachers->count()}, classrooms: {$classRooms->count()})");
                continue;
            }

            foreach ($institutionClasses as $class) {
                // Get sections for this class
                $sections = Section::where('class_id', $class->id)
                    ->where('institution_id', $institutionId)
                    ->where('status', 1)
                    ->get();

                if ($sections->isEmpty()) {
                    $this->command->info("Skipping class {$class->name} (ID: {$class->id}) - no sections found");
                    continue;
                }

                // Create routines for each section
                foreach ($sections as $section) {
                    // Create 2-3 routines per section per day
                    foreach ($days as $day) {
                        $routinesPerDay = rand(2, 3);
                        $usedTimeSlots = [];
                        
                        for ($i = 0; $i < $routinesPerDay; $i++) {
                            // Select a random time slot that hasn't been used
                            $availableSlots = array_filter($timeSlots, function($slot) use ($usedTimeSlots) {
                                return !in_array($slot['start'], $usedTimeSlots);
                            });
                            
                            if (empty($availableSlots)) {
                                break;
                            }
                            
                            $selectedSlot = $availableSlots[array_rand($availableSlots)];
                            $usedTimeSlots[] = $selectedSlot['start'];

                            // Skip break time slots for subject routines
                            if ($selectedSlot['start'] === '11:00') {
                                continue;
                            }

                            // Get random subject and teacher for this institution
                            if ($institutionSubjects->isEmpty() || $institutionTeachers->isEmpty()) {
                                continue;
                            }
                            
                            $randomSubject = $institutionSubjects->random();
                            
                            // Try to find a teacher who doesn't have a routine at this time
                            $availableTeachers = $institutionTeachers->filter(function($teacher) use ($day, $selectedSlot) {
                                return !Routine::where('teacher_id', $teacher->id)
                                    ->where('day', $day)
                                    ->where('start_time', $selectedSlot['start'])
                                    ->exists();
                            });
                            
                            if ($availableTeachers->isEmpty()) {
                                continue; // Skip this time slot if no teachers are available
                            }
                            
                            $randomTeacher = $availableTeachers->random();
                            
                            // Find an available classroom for this time slot
                            $availableClassRooms = $classRooms->filter(function($room) use ($day, $selectedSlot) {
                                return !Routine::where('class_room_id', $room->id)
                                    ->where('day', $day)
                                    ->where('start_time', $selectedSlot['start'])
                                    ->exists();
                            });
                            
                            $randomClassRoom = $availableClassRooms->isNotEmpty() ? $availableClassRooms->random() : null;

                            // Check if routine already exists for this class-section at this time
                            $existingRoutine = Routine::where('class_id', $class->id)
                                ->where('section_id', $section->id)
                                ->where('day', $day)
                                ->where('start_time', $selectedSlot['start'])
                                ->exists();

                            if (!$existingRoutine) {
                                Routine::create([
                                    'institution_id' => $institutionId,
                                    'class_id' => $class->id,
                                    'section_id' => $section->id,
                                    'subject_id' => $randomSubject->id,
                                    'teacher_id' => $randomTeacher->id,
                                    'class_room_id' => $randomClassRoom ? $randomClassRoom->id : null,
                                    'day' => $day,
                                    'start_time' => $selectedSlot['start'],
                                    'end_time' => $selectedSlot['end'],
                                    'is_break' => false,
                                    'is_other_day' => false,
                                    'admin_id' => 1,
                                    'status' => 1,
                                ]);
                                $routineCount++;
                            }
                        }

                        // Add break time routine
                        $breakRoutine = Routine::where('institution_id', $institutionId)
                            ->where('class_id', $class->id)
                            ->where('section_id', $section->id)
                            ->where('day', $day)
                            ->where('start_time', '11:00')
                            ->exists();

                        if (!$breakRoutine) {
                            // For break routines, we need to provide a subject_id and teacher_id
                            // Let's use the first subject and find a teacher who doesn't have a routine at 11:00
                            $breakSubject = $institutionSubjects->first();
                            $availableBreakTeachers = $institutionTeachers->filter(function($teacher) use ($day) {
                                return !Routine::where('teacher_id', $teacher->id)
                                    ->where('day', $day)
                                    ->where('start_time', '11:00')
                                    ->exists();
                            });
                            
                            if ($breakSubject && $availableBreakTeachers->isNotEmpty()) {
                                $breakTeacher = $availableBreakTeachers->first();
                                Routine::create([
                                    'institution_id' => $institutionId,
                                    'class_id' => $class->id,
                                    'section_id' => $section->id,
                                    'subject_id' => $breakSubject->id,
                                    'teacher_id' => $breakTeacher->id,
                                    'class_room_id' => null,
                                    'day' => $day,
                                    'start_time' => '11:00',
                                    'end_time' => '11:15',
                                    'is_break' => true,
                                    'is_other_day' => false,
                                    'admin_id' => 1,
                                    'status' => 1,
                                ]);
                                $routineCount++;
                            }
                        }
                    }
                }
            }
        }

        $this->command->info("Routines seeded successfully! Created {$routineCount} routines.");
    }
}
