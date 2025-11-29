<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EventController extends Controller
{
    use AuthorizesRequests;

    public function index(): Response
    {
        $events = Event::withCount('participants')
            ->orderBy('year', 'desc')
            ->get();

        $activeEvent = Event::withCount('participants')->where('is_active', true)->first();

        return Inertia::render('Events/Index', [
            'events' => $events,
            'activeEvent' => $activeEvent,
            'isAdmin' => auth()->user()->is_admin,
        ]);
    }

    /**
     * Show the form for creating a new event.
     */
    public function create(): Response
    {
        $this->authorize('create', Event::class);

        return Inertia::render('Events/Create');
    }

    /**
     * Store a newly created event.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Event::class);

        $validated = $request->validate([
            'year' => 'required|integer|unique:events,year',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date|after:registration_start',
            'is_active' => 'boolean',
        ]);

        // Deaktiviraj sve druge evente ako je novi aktivan
        if ($validated['is_active'] ?? false) {
            Event::where('is_active', true)->update(['is_active' => false]);
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
        $event->load(['participants', 'wishes.user']);

        $userParticipating = auth()->user()->isParticipantInEvent($event);

        return Inertia::render('Events/Show', [
            'event' => $event,
            'userParticipating' => $userParticipating,
            'canRegister' => auth()->user()->can('register', $event),
            'isAdmin' => auth()->user()->is_admin,
        ]);
    }

    /**
     * Show the form for editing the event.
     */
    public function edit(Event $event): Response
    {
        $this->authorize('update', $event);

        return Inertia::render('Events/Edit', [
            'event' => $event,
        ]);
    }

    /**
     * Update the specified event.
     */
    public function update(Request $request, Event $event): RedirectResponse
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'year' => 'required|integer|unique:events,year,' . $event->id,
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date|after:registration_start',
            'is_active' => 'boolean',
        ]);

        // Deaktiviraj sve druge evente ako je ovaj aktivan
        if ($validated['is_active'] ?? false) {
            Event::where('id', '!=', $event->id)
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
