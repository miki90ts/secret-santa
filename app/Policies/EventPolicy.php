<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    /**
     * Determine if the user can view any events.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine if the user can view the event.
     */
    public function view(User $user, Event $event): bool
    {
        // Može videti ako je član organizacije, ili global admin
        return  $event->organization->isMember($user)
            || $user->is_admin;
    }

    /**
     * Determine if the user can create events.
     */
    public function create(User $user): bool
    {
        // Može kreirati ako je global admin ili admin bar jedne organizacije
        return $user->is_admin || $user->organizations()->wherePivot('role', 'admin')->exists();
    }

    /**
     * Determine if the user can update the event.
     */
    public function update(User $user, Event $event): bool
    {
        return $user->is_admin || $event->organization->isAdmin($user);
    }

    /**
     * Determine if the user can delete the event.
     */
    public function delete(User $user, Event $event): bool
    {
        return $user->is_admin || $event->organization->isAdmin($user);
    }

    /**
     * Determine if the user can make assignments for the event.
     */
    public function makeAssignments(User $user, Event $event): bool
    {
        return ($user->is_admin || $event->organization->isAdmin($user))
            && $event->canMakeAssignments();
    }

    /**
     * Determine if the user can register for the event.
     */
    public function register(User $user, Event $event): bool
    {
        // Može se registrovati ako je:
        // - član organizacije
        // - Registracija je otvorena
        // - Nije već prijavljen
        $canAccess = $event->organization->isMember($user);

        return $canAccess
            && $event->isRegistrationOpen()
            && !$user->isParticipantInEvent($event);
    }
}
