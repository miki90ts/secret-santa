<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RegisterEventController extends Controller
{
    use AuthorizesRequests;

    public function __invoke(Event $event): RedirectResponse
    {
        $this->authorize('register', $event);

        auth()->user()->events()->attach($event->id, [
            'registered_at' => now(),
        ]);

        return back()
            ->with('message', [
                'body' => 'Uspešno ste se prijavili za Secret Santa!',
                'type' => 'success'
            ]);
    }
}
