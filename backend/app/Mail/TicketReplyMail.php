<?php

namespace App\Mail;

use App\Models\SiteSetting;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Ticket $ticket,
        public TicketMessage $reply,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Re: Your conversation with QAC Houston – Ticket #' . $this->ticketRef(),
        );
    }

    public function content(): Content
    {
        $site = SiteSetting::first();

        return new Content(
            view: 'emails.ticket-reply',
            with: [
                'ticket' => $this->ticket,
                'reply' => $this->reply,
                'ticketRef' => $this->ticketRef(),
                'siteName' => $site->site_name ?? 'QAC Houston',
                'contactPhone' => $site->contact_phone ?? '(832) 896-3865',
                'contactEmail' => $site->contact_email ?? 'hello@qachouston.com',
                'address' => $site->address ?? null,
            ],
        );
    }

    protected function ticketRef(): string
    {
        return strtoupper(substr($this->ticket->uuid, 0, 8));
    }
}
