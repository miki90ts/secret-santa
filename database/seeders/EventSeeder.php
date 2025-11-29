<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::create([
            'year' => now()->year,
            'name' => 'Secret Santa ' . now()->year,
            'description' => 'Godišnji Secret Santa događaj u našoj firmi!',
            'registration_start' => now()->startOfMonth(),
            'registration_end' => now()->addMonth(),
            'is_active' => true,
            'assignments_made' => false,
        ]);
    }
}
