<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Organization;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EventController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): Response
    {
        $user = auth()->user();

        // Ako je admin, vidi sve evente
        // Ako nije, vidi samo evente organizacija kojima pripada
        if ($user->is_admin) {
            $events = Event::with('organization')
                ->withCount('participants')
                ->orderBy('year', 'desc')
                ->get();
        } else {
            $organizationIds = $user->organizations()->pluck('organizations.id');
            $events = Event::with('organization')
                ->whereIn('organization_id', $organizationIds)
                ->withCount('participants')
                ->orderBy('year', 'desc')
                ->get();
        }

        // Filtriraj po organizaciji ako je prosleđeno
        if ($request->has('organization_id')) {
            $events = $events->where('organization_id', $request->organization_id);
        }

        $activeEvent = $events->firstWhere('is_active', true);

        return Inertia::render('Events/Index', [
            'events' => $events,
            'activeEvent' => $activeEvent,
            'isAdmin' => $user->is_admin,
            'userOrganizations' => $user->organizations,
        ]);
    }

    /**
     * Show the form for creating a new event.
     */
    public function create(Request $request): Response
    {
        $this->authorize('create', Event::class);

        // Ako je prosleđeno organization_id, proveri pristup
        $organizationId = $request->get('organization_id');
        $organization = null;

        if ($organizationId) {
            $organization = Organization::findOrFail($organizationId);
            // Proveri da li je korisnik admin organizacije
            if (!$organization->isAdmin(auth()->user())) {
                abort(403, 'Nemate pristup kreirati event za ovu organizaciju.');
            }
        }

        $userOrganizations = auth()->user()->organizations()
            ->wherePivot('role', 'admin')
            ->orWhere('owner_id', auth()->id())
            ->get();

        return Inertia::render('Events/Create', [
            'organization' => $organization,
            'userOrganizations' => $userOrganizations,
        ]);
    }

    /**
     * Store a newly created event.
     */
    public function store(StoreEventRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Proveri pristup za organizaciju
        $organization = Organization::findOrFail($validated['organization_id']);
        if (!$organization->isAdmin(auth()->user())) {
            abort(403, 'Nemate pristup kreirati event za ovu organizaciju.');
        }

        // Deaktiviraj sve druge evente u istoj organizaciji ako je novi aktivan
        if ($validated['is_active'] ?? false) {
            Event::where('organization_id', $organization->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        Event::create($validated);

        return redirect()->route('events.index')
            ->with('success', 'Event je uspešno kreiran.');
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event): Response
    {
        // Proveri pristup (mora biti član organizacije ili event public)
        $user = auth()->user();
        if (!$event->organization->isMember($user) && !$user->is_admin) {
            abort(403, 'Nemate pristup ovom eventu.');
        }

        $event->load(['organization', 'participants', 'wishes.user']);

        $userParticipating = $user->isParticipantInEvent($event);
        $isOrgAdmin = $event->organization->isAdmin($user);

        return Inertia::render('Events/Show', [
            'event' => $event,
            'userParticipating' => $userParticipating,
            'canRegister' => $user->can('register', $event),
            'isAdmin' => $user->is_admin,
            'isOrgAdmin' => $isOrgAdmin,
        ]);
    }

    /**
     * Show the form for editing the event.
     */
    public function edit(Event $event): Response
    {
        $this->authorize('update', $event);

        $userOrganizations = auth()->user()->organizations()
            ->wherePivot('role', 'admin')
            ->orWhere('owner_id', auth()->id())
            ->get();

        return Inertia::render('Events/Edit', [
            'event' => $event->load('organization'),
            'userOrganizations' => $userOrganizations,
        ]);
    }

    /**
     * Update the specified event.
     */
    public function update(UpdateEventRequest $request, Event $event): RedirectResponse
    {
        $validated = $request->validated();

        // Deaktiviraj sve druge evente u istoj organizaciji ako je ovaj aktivan
        if ($validated['is_active'] ?? false) {
            Event::where('organization_id', $event->organization_id)
                ->where('id', '!=', $event->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $event->update($validated);

        return redirect()->route('events.show', $event)
            ->with('success', 'Event je uspešno ažuriran.');
    }

    /**
     * Remove the specified event.
     */
    public function destroy(Event $event): RedirectResponse
    {
        $this->authorize('delete', $event);

        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event je uspešno obrisan.');
    }
}
