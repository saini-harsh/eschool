<?php

namespace Database\Seeders;

use App\Models\Institution;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $institutions = [
            [
                'name' => 'Green Valley School',
                'address' => '123 Main Street',
                'pincode' => '110011',
                'established_date' => '2005-06-15',
                'board' => 'CBSE',
                'state' => 'Delhi',
                'district' => 'New Delhi',
                'email' => 'greenvalley@example.com',
                'website' => 'http://greenvalley.edu',
                'phone' => '9876543210',
            ],
            [
                'name' => 'Sunrise Public School',
                'address' => '456 Sunrise Road',
                'pincode' => '110022',
                'established_date' => '2010-09-10',
                'board' => 'ICSE',
                'state' => 'Delhi',
                'district' => 'South Delhi',
                'email' => 'sunrise@example.com',
                'website' => 'http://sunrise.edu',
                'phone' => '9876509876',
            ],
            [
                'name' => 'Delhi International School',
                'address' => '789 International Avenue',
                'pincode' => '110033',
                'established_date' => '2012-03-20',
                'board' => 'IB',
                'state' => 'Delhi',
                'district' => 'Central Delhi',
                'email' => 'delhi.international@example.com',
                'website' => 'http://delhiinternational.edu',
                'phone' => '9876544444',
            ],
            [
                'name' => 'Modern Public School',
                'address' => '321 Modern Street',
                'pincode' => '110044',
                'established_date' => '2008-11-05',
                'board' => 'CBSE',
                'state' => 'Delhi',
                'district' => 'West Delhi',
                'email' => 'modern.public@example.com',
                'website' => 'http://modernpublic.edu',
                'phone' => '9876555555',
            ]
        ];

        foreach ($institutions as $inst) {
            // Check if institution already exists
            if (!Institution::where('email', $inst['email'])->exists()) {
                Institution::create(array_merge($inst, [
                    'admin_id' => 1,
                    'logo' => 1,
                    'password' => Hash::make('school123'),
                    'decrypt_pw' => 'school123',
                    'status' => 1
                ]));
            }
        }
        $this->command->info('Institutions seeded successfully!');
    }
}
