<?php

namespace App\Jobs;

use App\Mail\DeliverableApproved;
use App\Models\Deliverable;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendDeliverableApproved implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly User $freelance,
        private readonly Deliverable $deliverable,
    ) {}

    public function handle(): void
    {
        Mail::to($this->freelance->email)->send(new DeliverableApproved($this->freelance, $this->deliverable));
    }
}
