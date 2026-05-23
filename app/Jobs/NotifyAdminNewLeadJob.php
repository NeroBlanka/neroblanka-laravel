<?php

namespace App\Jobs;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NotifyAdminNewLeadJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public function __construct(public readonly Lead $lead) {}

    public function handle(): void
    {
        $admin = User::withoutGlobalScopes()->where('role', 'admin')->first();
        if (!$admin) {
            return;
        }

        Mail::to($admin->email)->send(new \App\Mail\AdminNewLead($this->lead));
    }
}
