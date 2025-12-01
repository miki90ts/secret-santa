<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShowGiftFeedbackController extends Controller
{
    /**
     * Show gift feedback - what the receiver thought of the gift.
     */
    public function __invoke(Event $event)
    {
        $assignment = Assignment::where('event_id', $event->id)
            ->where('giver_id', Auth::id())
            ->with(['receiver', 'gift'])
            ->first();

        if (!$assignment) {
            return back()->with('error', 'Nemate dodelu za ovaj event.');
        }

        return inertia('Assignments/GiftFeedback', [
            'assignment' => $assignment,
            'event' => $event,
        ]);
    }
}
