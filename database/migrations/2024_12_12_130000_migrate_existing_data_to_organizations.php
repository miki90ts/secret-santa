<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Organization;
use App\Models\Event;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Ova migracija kreira default organizaciju i migrira postojeće podatke
     */
    public function up(): void
    {
        // Pronađi prvog admina ili prvog korisnika
        $firstAdmin = User::where('is_admin', true)->first();
        $owner = $firstAdmin ?? User::first();

        if (!$owner) {
            // Ako nema korisnika, vrati se - ovo je fresh instalacija
            return;
        }

        // Kreiraj default organizaciju
        $organization = Organization::create([
            'name' => 'Default Organization',
            'slug' => 'default-org',
            'description' => 'Automatski kreirana organizacija za postojeće podatke',
            'owner_id' => $owner->id,
            'is_active' => true,
        ]);

        // Dodaj owner-a kao admin-a
        $organization->members()->attach($owner->id, [
            'role' => 'admin',
            'joined_at' => now(),
        ]);

        // Dodaj sve korisnike kao članove default organizacije
        $allUsers = User::where('id', '!=', $owner->id)->get();
        foreach ($allUsers as $user) {
            $organization->members()->attach($user->id, [
                'role' => 'member',
                'joined_at' => $user->created_at ?? now(),
            ]);
        }

        // Ažuriraj sve postojeće evente da budu u ovoj organizaciji
        Event::whereNull('organization_id')->update([
            'organization_id' => $organization->id,
        ]);

        // Generiši slug za postojeće evente ako nemaju
        $events = Event::whereNull('slug')->get();
        foreach ($events as $event) {
            $event->slug = \Illuminate\Support\Str::slug($event->name ?? 'event-' . $event->year) . '-' . \Illuminate\Support\Str::random(6);
            $event->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Briši default organizaciju (cascade će obrisati članstva)
        Organization::where('slug', 'default-org')->delete();

        // Vrati events na null organization_id
        Event::whereNotNull('organization_id')->update([
            'organization_id' => null,
        ]);
    }
};
