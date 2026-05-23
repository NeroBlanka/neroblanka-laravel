<?php

namespace App\Mail;

use App\Models\Assignment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AssignmentCreated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly User $freelance,
        public readonly Assignment $assignment,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouvelle mission — ' . $this->assignment->project->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.assignment-created',
        );
    }
}
