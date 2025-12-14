<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class OrganizationController extends Controller
{
    /**
     * Prikaži sve organizacije korisnika
     */
    public function index()
    {
        $organizations = auth()->user()->organizations()
            ->with('owner')
            ->withCount('members', 'events')
            ->latest()
            ->get();

        $ownedOrganizations = auth()->user()->ownedOrganizations()
            ->withCount('members', 'events')
            ->latest()
            ->get();

        return Inertia::render('Organizations/Index', [
            'organizations' => $organizations,
            'ownedOrganizations' => $ownedOrganizations,
        ]);
    }

    /**
     * Prikaži formu za kreiranje nove organizacije
     */
    public function create()
    {
        return Inertia::render('Organizations/Create');
    }

    /**
     * Kreiraj novu organizaciju
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $organization = Organization::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . Str::random(6),
            'description' => $validated['description'] ?? null,
            'owner_id' => auth()->id(),
            'is_active' => true,
        ]);

        // Dodaj kreatora kao admin-a
        $organization->members()->attach(auth()->id(), [
            'role' => 'admin',
            'joined_at' => now(),
        ]);

        return redirect()->route('organizations.show', $organization)
            ->with('success', 'Organizacija uspešno kreirana!');
    }

    /**
     * Prikaži jednu organizaciju
     */
    public function show(Organization $organization)
    {
        // Proveri da li je korisnik član
        if (!$organization->isMember(auth()->user())) {
            abort(403, 'Nemate pristup ovoj organizaciji.');
        }

        $organization->load([
            'owner',
            'members' => fn($q) => $q->withPivot('role', 'joined_at'),
            'events' => fn($q) => $q->latest()->withCount('participants'),
        ]);

        $isAdmin = $organization->isAdmin(auth()->user());

        return Inertia::render('Organizations/Show', [
            'organization' => $organization,
            'isAdmin' => $isAdmin,
        ]);
    }

    /**
     * Prikaži formu za edit
     */
    public function edit(Organization $organization)
    {
        // Proveri da li je admin
        if (!$organization->isAdmin(auth()->user())) {
            abort(403, 'Samo admin može menjati organizaciju.');
        }

        return Inertia::render('Organizations/Edit', [
            'organization' => $organization,
        ]);
    }

    /**
     * Ažuriraj organizaciju
     */
    public function update(Request $request, Organization $organization)
    {
        // Proveri da li je admin
        if (!$organization->isAdmin(auth()->user())) {
            abort(403, 'Samo admin može menjati organizaciju.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $organization->update($validated);

        return redirect()->route('organizations.show', $organization)
            ->with('success', 'Organizacija uspešno ažurirana!');
    }

    /**
     * Obriši organizaciju
     */
    public function destroy(Organization $organization)
    {
        // Samo owner može obrisati
        if ($organization->owner_id !== auth()->id()) {
            abort(403, 'Samo vlasnik može obrisati organizaciju.');
        }

        $organization->delete();

        return redirect()->route('organizations.index')
            ->with('success', 'Organizacija uspešno obrisana!');
    }

    /**
     * Pridruži se organizaciji putem invite linka
     */
    public function join(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)
            ->where('is_active', true)
            ->withCount('members', 'events')
            ->firstOrFail();

        // Ako korisnik nije ulogovan, prikaži stranicu sa loginom
        if (!auth()->check()) {
            return Inertia::render('Organizations/Join', [
                'organization' => $organization,
            ]);
        }

        $user = auth()->user();

        // Proveri da li je već član
        if ($organization->isMember($user)) {
            return redirect()->route('organizations.show', $organization)
                ->with('info', 'Već ste član ove organizacije.');
        }

        // Dodaj kao člana
        $organization->members()->attach($user->id, [
            'role' => 'member',
            'joined_at' => now(),
        ]);

        return redirect()->route('organizations.show', $organization)
            ->with('success', 'Uspešno ste se pridružili organizaciji!');
    }

    /**
     * Napusti organizaciju
     */
    public function leave(Organization $organization)
    {
        $user = auth()->user();

        // Ne može owner da napusti
        if ($organization->owner_id === $user->id) {
            return back()->with('error', 'Vlasnik ne može napustiti organizaciju. Prvo prebacite vlasništvo ili obrišite organizaciju.');
        }

        $organization->members()->detach($user->id);

        return redirect()->route('organizations.index')
            ->with('success', 'Napustili ste organizaciju.');
    }

    /**
     * Promeni ulogu člana
     */
    public function updateMemberRole(Request $request, Organization $organization, $userId)
    {
        if (!$organization->isAdmin(auth()->user())) {
            abort(403, 'Samo admin može menjati uloge.');
        }

        $validated = $request->validate([
            'role' => 'required|in:admin,member',
        ]);

        $organization->members()->updateExistingPivot($userId, [
            'role' => $validated['role'],
        ]);

        return back()->with('success', 'Uloga člana ažurirana!');
    }

    /**
     * Ukloni člana iz organizacije
     */
    public function removeMember(Organization $organization, $userId)
    {
        if (!$organization->isAdmin(auth()->user())) {
            abort(403, 'Samo admin može ukloniti članove.');
        }

        // Ne može owner da bude uklonjen
        if ($organization->owner_id == $userId) {
            return back()->with('error', 'Ne možete ukloniti vlasnika organizacije.');
        }

        $organization->members()->detach($userId);

        return back()->with('success', 'Član uklonjen iz organizacije!');
    }
}
