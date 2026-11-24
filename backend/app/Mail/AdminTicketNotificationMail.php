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

class AdminTicketNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Ticket $ticket,
        public TicketMessage $ticketMessage,
        public string $type,
    ) {
    }

    public function envelope(): Envelope
    {
        $ref = $this->ticketRef();

        $subject = $this->type === 'new_ticket'
            ? "New Support Ticket #{$ref} – QAC Houston"
            : "New Customer Message on Ticket #{$ref} – QAC Houston";

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        $site = SiteSetting::first();

        return new Content(
            view: 'emails.ticket-notification',
            with: [
                'ticket' => $this->ticket,
                'ticketMessage' => $this->ticketMessage,
                'type' => $this->type,
                'ticketRef' => $this->ticketRef(),
                'adminUrl' => rtrim(config('app.url'), '/') . '/admin/tickets/' . $this->ticket->id . '/edit',
                'siteName' => $site->site_name ?? 'QAC Houston',
                'contactPhone' => $site->contact_phone ?? '(832) 896-3865',
                'contactEmail' => $site->contact_email ?? 'hello@qachouston.com',
            ],
        );
    }

    protected function ticketRef(): string
    {
        return strtoupper(substr($this->ticket->uuid, 0, 8));
    }
}
