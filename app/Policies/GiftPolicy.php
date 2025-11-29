<?php

namespace App\Policies;

use App\Models\Gift;
use App\Models\User;

class GiftPolicy
{
    /**
     * Determine if the user can view the gift.
     * Svi mogu videti gift ali bez informacije ko je kupio.
     */
    public function view(User $user, Gift $gift): bool
    {
        return true;
    }

    /**
     * Determine if the user can create/update the gift.
     * Samo receiver može uneti/ažurirati gift.
     */
    public function update(User $user, Gift $gift): bool
    {
        return $user->id === $gift->assignment->receiver_id;
    }
}
