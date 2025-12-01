<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Assignment;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShowMyAssignmentController extends Controller
{
    /**
     * Show the user's assignment (who they need to buy a gift for).
     */
    public function __invoke(Event $event)
    {
        $assignment = Assignment::where('event_id', $event->id)
            ->where('giver_id', Auth::id())
            ->with(['receiver.wishes' => function ($query) use ($event) {
                $query->where('event_id', $event->id);
            }])
            ->first();

        if (!$assignment) {
            return back()->with('error', 'Nemate dodelu za ovaj event.');
        }

        // Load comments separately if needed
        $comments = Comment::where('event_id', $event->id)
            ->where('receiver_id', $assignment->receiver_id)
            ->with('user')
            ->get();

        return inertia('Assignments/Show', [
            'assignment' => $assignment,
            'event' => $event,
            'comments' => $comments,
        ]);
    }
}
