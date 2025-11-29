<?php

namespace App\Http\Controllers;

use App\Models\Gift;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class GiftController extends Controller
{
    /**
     * Store or update a gift.
     */
    public function store(Request $request, Assignment $assignment): RedirectResponse
    {
        $validated = $request->validate([
            'description' => 'required|string',
            'is_satisfied' => 'required|boolean',
            'feedback' => 'nullable|string',
        ]);

        // Proveri da je trenutni korisnik receiver
        if ($assignment->receiver_id !== auth()->id()) {
            abort(403, 'Nemate dozvolu da unesete poklon.');
        }

        $gift = $assignment->gift;

        if ($gift) {
            // Ažuriraj postojeći gift
            $gift->update([
                ...$validated,
                'received_at' => $gift->received_at ?? now(),
            ]);
            $message = 'Poklon je uspešno ažuriran.';
        } else {
            // Kreiraj novi gift
            Gift::create([
                'assignment_id' => $assignment->id,
                ...$validated,
                'received_at' => now(),
            ]);
            $message = 'Poklon je uspešno dodat.';
        }

        return back()->with('success', $message);
    }

    /**
     * Display gifts for an event (without revealing givers).
     */
    public function index(Request $request)
    {
        $eventId = $request->query('event_id');

        $gifts = Gift::whereHas('assignment', function ($query) use ($eventId) {
            $query->where('event_id', $eventId);
        })
            ->with(['assignment.receiver'])
            ->get()
            ->map(function ($gift) {
                return [
                    'id' => $gift->id,
                    'receiver_name' => $gift->assignment->receiver->name,
                    'description' => $gift->description,
                    'is_satisfied' => $gift->is_satisfied,
                    'feedback' => $gift->feedback,
                    'received_at' => $gift->received_at,
                    // Ne prikazujemo giver_id - to je tajno!
                ];
            });

        return inertia('Gifts/Index', [
            'gifts' => $gifts,
            'eventId' => $eventId,
        ]);
    }
}
