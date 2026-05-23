<?php

namespace App\Policies;

use App\Models\Deliverable;
use App\Models\User;

class DeliverablePolicy
{
    public function approve(User $user, Deliverable $deliverable): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'client') {
            return $deliverable->assignment->project->client_id === $user->id;
        }

        return false;
    }

    public function revision(User $user, Deliverable $deliverable): bool
    {
        return $this->approve($user, $deliverable);
    }

    public function create(User $user): bool
    {
        return $user->role === 'freelance';
    }
}
