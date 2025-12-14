<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;

class OrganizationPolicy
{
    /**
     * Determine whether the user can view any organizations.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the organization.
     */
    public function view(User $user, Organization $organization): bool
    {
        // Može videti ako je član ili je public (u budućnosti)
        return $organization->isMember($user) || $user->is_admin;
    }

    /**
     * Determine whether the user can create organizations.
     */
    public function create(User $user): bool
    {
        return true; // Svi autentifikovani korisnici mogu kreirati
    }

    /**
     * Determine whether the user can update the organization.
     */
    public function update(User $user, Organization $organization): bool
    {
        return $organization->isAdmin($user) || $user->is_admin;
    }

    /**
     * Determine whether the user can delete the organization.
     */
    public function delete(User $user, Organization $organization): bool
    {
        // Samo owner može obrisati
        return $organization->owner_id === $user->id || $user->is_admin;
    }

    /**
     * Determine whether the user can manage members.
     */
    public function manageMembers(User $user, Organization $organization): bool
    {
        return $organization->isAdmin($user) || $user->is_admin;
    }

    /**
     * Determine whether the user can create events in this organization.
     */
    public function createEvent(User $user, Organization $organization): bool
    {
        return $organization->isAdmin($user) || $user->is_admin;
    }
}
