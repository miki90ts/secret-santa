<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShowMyGiftController extends Controller
{
    /**
     * Show the gift form for the user (as receiver).
     */
    public function __invoke(Event $event)
    {
        $assignment = Assignment::where('event_id', $event->id)
            ->where('receiver_id', Auth::id())
            ->with(['giver', 'gift'])
            ->first();

        if (!$assignment) {
            return back()->with('error', 'Nemate dodeljenog Secret Santa-u za ovaj event.');
        }

        return inertia('Assignments/MyGift', [
            'assignment' => $assignment,
            'event' => $event,
        ]);
    }
}
