<?php

namespace App\Mail;

use App\Models\Deliverable;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DeliverableApproved extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly User $freelance,
        public readonly Deliverable $deliverable,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Livrable approuvé — ' . $this->deliverable->assignment->project->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.deliverable-approved',
        );
    }
}
