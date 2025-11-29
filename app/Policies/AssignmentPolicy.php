<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\User;

class AssignmentPolicy
{
    /**
     * Determine if the user can view the assignment.
     * Korisnik može videti samo svoj assignment (ko mu kupuje poklon).
     */
    public function view(User $user, Assignment $assignment): bool
    {
        return $user->id === $assignment->giver_id;
    }

    /**
     * Determine if the user can view any assignments.
     * Admin može videti sve.
     */
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }
}
