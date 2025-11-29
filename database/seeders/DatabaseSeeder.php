<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Event;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Kreiraj admin korisnika
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'is_admin' => true,
        ]);

        // Kreiraj test korisnika
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'is_admin' => false,
        ]);

        // Kreiraj dodatne korisnike
        User::factory(18)->create();

        // Seed events
        $this->call([
            EventSeeder::class,
        ]);

        // Registruj neke korisnike za aktivan event
        $activeEvent = Event::where('is_active', true)->first();
        if ($activeEvent) {
            $users = User::take(10)->get();
            foreach ($users as $user) {
                $user->events()->attach($activeEvent->id, [
                    'registered_at' => now(),
                ]);
            }
        }
    }
}
