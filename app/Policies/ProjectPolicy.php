<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function view(User $user, Project $project): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'client') {
            return $project->client_id === $user->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->role === 'client';
    }

    public function update(User $user, Project $project): bool
    {
        return $user->role === 'admin';
    }
}
