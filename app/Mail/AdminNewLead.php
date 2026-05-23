<?php

namespace App\Mail;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNewLead extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly Lead $lead) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "[Neroblanka] Nouveau lead — {$this->lead->full_name} ({$this->lead->service_type->label()})");
    }

    public function content(): Content
    {
        return new Content(view: 'emails.admin-new-lead');
    }
}
