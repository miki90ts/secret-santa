<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;

class UnregisterEventController extends Controller
{
    public function __invoke(Event $event): RedirectResponse
    {
        if ($event->assignments_made) {
            return back()->with('error', 'Ne možete se odjaviti nakon izvlačenja.');
        }

        auth()->user()->events()->detach($event->id);

        return back()
            ->with('message', [
                'body' => 'Uspešno ste se odjavili sa Secret Santa!',
                'type' => 'success'
            ]);
    }
}
