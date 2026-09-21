<?php

namespace App\Policies;

use App\Models\Podcast;
use App\Models\User;

/**
 * Ownership is the only permission today. An admin role can be layered on
 * later with a Gate::before hook without touching these rules.
 */
class PodcastPolicy
{
    public function view(User $user, Podcast $podcast): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Podcast $podcast): bool
    {
        return $podcast->isOwnedBy($user);
    }

    public function delete(User $user, Podcast $podcast): bool
    {
        return $podcast->isOwnedBy($user);
    }
}
