<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wish;
use App\Models\Event;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreWishRequest;
use App\Http\Requests\UpdateWishRequest;
use Illuminate\Http\Request;

class WishController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $activeEvent = Event::active()->firstOrFail();

        $activeEvent->load([
            'participants.wishes' => function ($query) use ($activeEvent) {
                $query->where('event_id', $activeEvent->id);
            },
            'participants.received_comments.user',
        ]);
        return inertia()->render('Dashboard', [
            'users' => $activeEvent ? UserResource::collection($activeEvent->participants) : [],
            'activeEvent' => $activeEvent,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $activeEvent = Event::where('is_active', true)->first();

        return inertia()->render('Test', [
            'users' => UserResource::collection(
                User::with(['wishes' => function ($query) use ($activeEvent) {
                    if ($activeEvent) {
                        $query->where('event_id', $activeEvent->id);
                    }
                }, 'wishes.comments.user'])
                    ->get()
            ),
            'activeEvent' => $activeEvent,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'want' => 'nullable|string',
            'does_not_want' => 'nullable|string',
        ]);

        Wish::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'event_id' => $validated['event_id'],
            ],
            [
                'want' => $validated['want'],
                'does_not_want' => $validated['does_not_want'],
            ]
        );

        return back()->with('message', [
            'body' => 'Uspešno sačuvano',
            'type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Wish $wish)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wish $wish)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'want' => 'nullable|string',
            'does_not_want' => 'nullable|string',
        ]);

        $wish = Wish::where('user_id', auth()->id())
            ->where('event_id', $validated['event_id'])
            ->first();

        if ($wish) {
            $wish->update($validated);
        } else {
            Wish::create([
                'user_id' => auth()->id(),
                'event_id' => $validated['event_id'],
                'want' => $validated['want'],
                'does_not_want' => $validated['does_not_want'],
            ]);
        }

        return redirect(route('dashboard'))->with('message', [
            'body' => 'Uspešno sačuvano',
            'type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wish $wish)
    {
        //
    }

    /**
     * Show wish history for the authenticated user.
     */
    public function history()
    {
        $wishes = Wish::where('user_id', auth()->id())
            ->with(['event', 'comments.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return inertia('Wishes/History', [
            'wishes' => $wishes,
        ]);
    }
}
