<?php

namespace App\Jobs;

use App\Mail\ProjectBriefReceived;
use App\Models\Project;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendProjectBriefReceived implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly User $admin,
        private readonly Project $project,
    ) {}

    public function handle(): void
    {
        Mail::to($this->admin->email)->send(new ProjectBriefReceived($this->admin, $this->project));
    }
}
