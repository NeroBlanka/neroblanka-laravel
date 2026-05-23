<?php

namespace App\Jobs;

use App\Mail\DeliverableSubmitted;
use App\Models\Deliverable;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendDeliverableSubmitted implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly User $client,
        private readonly Deliverable $deliverable,
    ) {}

    public function handle(): void
    {
        Mail::to($this->client->email)->send(new DeliverableSubmitted($this->client, $this->deliverable));
    }
}
