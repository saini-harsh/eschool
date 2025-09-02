<?php

namespace Database\Seeders;

use App\Models\NonWorkingStaff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class NonWorkingStaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staffs = [
            // Green Valley School (institution_id = 1)
            [
                'first_name' => 'Suresh',
                'middle_name' => null,
                'last_name' => 'Kumar',
                'dob' => '1975-05-12',
                'email' => 'suresh.green@example.com',
                'phone' => '9876598765',
                'address' => 'Staff Colony, Delhi',
                'pincode' => '110011',
                'institution_code' => 'INS001',
                'gender' => 'Male',
                'caste_tribe' => 'General',
                'institution_id' => 1,
                'admin_id' => 1,
                'designation' => 'Clerk',
                'date_of_joining' => '2015-07-01',
            ],

            // Sunrise Public School (institution_id = 2)
            [
                'first_name' => 'Meena',
                'middle_name' => null,
                'last_name' => 'Devi',
                'dob' => '1980-09-20',
                'email' => 'meena.sunrise@example.com',
                'phone' => '9876523456',
                'address' => 'Sunrise Colony, Delhi',
                'pincode' => '110022',
                'institution_code' => 'INS002',
                'gender' => 'Female',
                'caste_tribe' => 'OBC',
                'institution_id' => 2,
                'admin_id' => 1,
                'designation' => 'Librarian',
                'date_of_joining' => '2018-03-15',
            ],

            // Delhi International School (institution_id = 3)
            [
                'first_name' => 'Rajesh',
                'middle_name' => null,
                'last_name' => 'Verma',
                'dob' => '1978-03-15',
                'email' => 'rajesh.international@example.com',
                'phone' => '9876544443',
                'address' => 'International Colony, Delhi',
                'pincode' => '110033',
                'institution_code' => 'INS003',
                'gender' => 'Male',
                'caste_tribe' => 'General',
                'institution_id' => 3,
                'admin_id' => 1,
                'designation' => 'Accountant',
                'date_of_joining' => '2016-09-01',
            ],

            // Modern Public School (institution_id = 4)
            [
                'first_name' => 'Sunita',
                'middle_name' => null,
                'last_name' => 'Yadav',
                'dob' => '1982-11-08',
                'email' => 'sunita.modern@example.com',
                'phone' => '9876555553',
                'address' => 'Modern Colony, Delhi',
                'pincode' => '110044',
                'institution_code' => 'INS004',
                'gender' => 'Female',
                'caste_tribe' => 'OBC',
                'institution_id' => 4,
                'admin_id' => 1,
                'designation' => 'Receptionist',
                'date_of_joining' => '2019-01-15',
            ]
        ];

        foreach ($staffs as $staff) {
            NonWorkingStaff::create(array_merge($staff, [
                'profile_image' => "admin/uploads/students/1755236487_689ec887dc5d6.jpeg",
                'password' => Hash::make('staff123'),
                'decrypt_pw' => 'staff123',
                'status' => 1
            ]));
        }
    }
}
