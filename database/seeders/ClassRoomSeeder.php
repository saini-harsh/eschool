<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use Illuminate\Database\Seeder;

class ClassRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classRooms = [
            ['room_no' => '101', 'capacity' => 30],
            ['room_no' => '102', 'capacity' => 35],
            ['room_no' => '103', 'capacity' => 40],
            ['room_no' => '104', 'capacity' => 25],
            ['room_no' => '105', 'capacity' => 30],
            ['room_no' => '201', 'capacity' => 35],
            ['room_no' => '202', 'capacity' => 40],
            ['room_no' => '203', 'capacity' => 30],
            ['room_no' => '204', 'capacity' => 25],
            ['room_no' => '205', 'capacity' => 35],
            ['room_no' => '301', 'capacity' => 30],
            ['room_no' => '302', 'capacity' => 35],
            ['room_no' => '303', 'capacity' => 40],
            ['room_no' => '304', 'capacity' => 30],
            ['room_no' => '305', 'capacity' => 25],
            ['room_no' => 'Lab-1', 'capacity' => 20],
            ['room_no' => 'Lab-2', 'capacity' => 20],
            ['room_no' => 'Computer Lab', 'capacity' => 25],
            ['room_no' => 'Library', 'capacity' => 50],
            ['room_no' => 'Auditorium', 'capacity' => 200],
        ];

        $classRoomCount = 0;

        foreach ($classRooms as $room) {
            // Check if classroom already exists
            if (!ClassRoom::where('room_no', $room['room_no'])->exists()) {
                ClassRoom::create([
                    'room_no' => $room['room_no'],
                    'capacity' => $room['capacity'],
                    'status' => true,
                ]);
                $classRoomCount++;
            }
        }

        $this->command->info("Classrooms seeded successfully! Created {$classRoomCount} classrooms.");
    }
}
