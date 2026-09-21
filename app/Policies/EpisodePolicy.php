<?php

namespace App\Policies;

use App\Models\Episode;
use App\Models\User;

class EpisodePolicy
{
    public function view(User $user, Episode $episode): bool
    {
        return $episode->isVisibleTo($user);
    }

    public function update(User $user, Episode $episode): bool
    {
        return $episode->podcast->isOwnedBy($user);
    }

    public function delete(User $user, Episode $episode): bool
    {
        return $episode->podcast->isOwnedBy($user);
    }
}
