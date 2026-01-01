<?php

namespace Database\Seeders;

use App\Models\Cycle;
use Illuminate\Database\Seeder;

class CycleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cycle::firstOrCreate(
            ['slug' => '2025-scholarship-cycle'],
            [
                'title' => '2025 Scholarship Cycle',
                'description' => 'Annual scholarship program for Isan-Ekiti indigenes pursuing education in secondary schools, universities, and polytechnics.',
                'tracks_json' => ['secondary', 'university', 'polytechnic'],
                'start_at' => now(),
                'deadline_at' => now()->addMonths(2),
                'results_release_at' => now()->addMonths(3),
                'budget_total' => 5000000.00,
                'status' => 'published',
                'form_schema_json' => [],
            ]
        );

        $this->command->info('✅ Sample cycle created!');
    }
}
