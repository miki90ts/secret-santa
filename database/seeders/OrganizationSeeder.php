<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Organization;
use App\Models\Event;

class OrganizationSeeder extends Seeder
{
    /**
     * Seed test organizations with members and events.
     */
    public function run(): void
    {
        // Kreiraj testne organizacije
        $org1 = Organization::create([
            'name' => 'Tech Company',
            'slug' => 'tech-company-abc123',
            'description' => 'Softverska kompanija',
            'owner_id' => 1,
            'is_active' => true,
        ]);

        $org2 = Organization::create([
            'name' => 'Marketing Agency',
            'slug' => 'marketing-agency-xyz456',
            'description' => 'Marketing agencija',
            'owner_id' => 1,
            'is_active' => true,
        ]);

        // Dodaj članove u org1
        $users = User::limit(10)->get();
        foreach ($users as $user) {
            $org1->members()->attach($user->id, [
                'role' => $user->id == 1 ? 'admin' : 'member',
                'joined_at' => now(),
            ]);
        }

        // Dodaj članove u org2
        $users2 = User::skip(5)->limit(8)->get();
        foreach ($users2 as $user) {
            $org2->members()->attach($user->id, [
                'role' => $user->id == 1 ? 'admin' : 'member',
                'joined_at' => now(),
            ]);
        }

        // Kreiraj evente za org1
        Event::create([
            'organization_id' => $org1->id,
            'year' => 2025,
            'name' => 'Tech Company Secret Santa 2025',
            'description' => 'Novogodišnja razmena poklona',
            'registration_start' => now()->subDays(10),
            'registration_end' => now()->addDays(10),
            'is_active' => true,
            'assignments_made' => false,
        ]);

        // Kreiraj evente za org2
        Event::create([
            'organization_id' => $org2->id,
            'year' => 2025,
            'name' => 'Marketing Agency Secret Santa 2025',
            'description' => 'Naša božićna tradicija',
            'registration_start' => now()->subDays(5),
            'registration_end' => now()->addDays(15),
            'is_active' => true,
            'assignments_made' => false,
        ]);

        $this->command->info('Organizacije, članovi i eventi uspešno seedovani!');
    }
}
