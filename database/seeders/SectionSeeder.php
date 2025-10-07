<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            ['name' => 'A','institution_id' => 1,'class_id' => 1  ],
            ['name' => 'B','institution_id' => 1,'class_id' => 1  ],
            ['name' => 'C','institution_id' => 1,'class_id' => 1  ],
            ['name' => 'D','institution_id' => 1,'class_id' => 1  ],
            ['name' => 'E','institution_id' => 1,'class_id' => 1  ],
            ['name' => 'F','institution_id' => 1,'class_id' => 1  ],
            ['name' => 'G','institution_id' => 1,'class_id' => 1  ],
            ['name' => 'H','institution_id' => 1,'class_id' => 1  ],
            ['name' => 'I','institution_id' => 1,'class_id' => 1  ],
            ['name' => 'J','institution_id' => 1,'class_id' => 1  ],
            ['name' => 'K','institution_id' => 1,'class_id' => 1  ],
            ['name' => 'L','institution_id' => 1,'class_id' => 1  ],
        ];

        foreach ($sections as $section) {
            Section::create(array_merge($section, [
                'status' => 1,
                'institution_id' => $section['institution_id'],
                'class_id' => $section['class_id']
            ]));
        }
        $this->command->info('Sections seeded successfully!');
    }
}
