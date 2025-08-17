<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        $attendances = [
            // Institution 1: Students 1-4
            [
                'user_id' => 1,
                'role' => 'student',
                'institution_id' => 1,
                'date' => Carbon::today()->toDateString(),
                'status' => 'present',
                'remarks' => 'On time',
            ],
            [
                'user_id' => 2,
                'role' => 'student',
                'institution_id' => 1,
                'date' => Carbon::today()->toDateString(),
                'status' => 'absent',
                'remarks' => 'Sick leave',
            ],
            [
                'user_id' => 3,
                'role' => 'student',
                'institution_id' => 1,
                'date' => Carbon::today()->toDateString(),
                'status' => 'late',
                'remarks' => 'Traffic delay',
            ],
            [
                'user_id' => 4,
                'role' => 'student',
                'institution_id' => 1,
                'date' => Carbon::today()->toDateString(),
                'status' => 'present',
                'remarks' => '',
            ],
            // Institution 2: Students 5-8
            [
                'user_id' => 5,
                'role' => 'student',
                'institution_id' => 2,
                'date' => Carbon::today()->toDateString(),
                'status' => 'present',
                'remarks' => '',
            ],
            [
                'user_id' => 6,
                'role' => 'student',
                'institution_id' => 2,
                'date' => Carbon::today()->toDateString(),
                'status' => 'absent',
                'remarks' => 'Family emergency',
            ],
            [
                'user_id' => 7,
                'role' => 'student',
                'institution_id' => 2,
                'date' => Carbon::today()->toDateString(),
                'status' => 'late',
                'remarks' => 'Missed bus',
            ],
            [
                'user_id' => 8,
                'role' => 'student',
                'institution_id' => 2,
                'date' => Carbon::today()->toDateString(),
                'status' => 'present',
                'remarks' => 'Participated in class',
            ],
        ];

        foreach ($attendances as $attendance) {
            Attendance::create($attendance);
        }
    }
}
