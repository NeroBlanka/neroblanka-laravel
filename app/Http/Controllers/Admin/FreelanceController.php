<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FreelanceController extends Controller
{
    public function index(): View
    {
        $freelances = User::where('role', 'freelance')
            ->withCount(['assignments as active_assignments_count' => fn($q) => $q->where('status', 'active')])
            ->orderBy('full_name')
            ->get();

        return view('admin.freelances', compact('freelances'));
    }

    public function toggleAvailability(User $user): RedirectResponse
    {
        abort_unless($user->role === 'freelance', 404);
        $user->update(['is_available' => !$user->is_available]);

        return back();
    }
}
