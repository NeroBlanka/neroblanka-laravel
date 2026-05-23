<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\User;

class AssignmentPolicy
{
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function view(User $user, Assignment $assignment): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'freelance') {
            return $assignment->freelance_id === $user->id;
        }

        return false;
    }
}
