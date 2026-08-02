<?php

namespace App\Mail;

use App\Models\Appointment;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public Appointment $appointment;

    public string $status;

    protected array $labels = [
        'pending' => 'Pending',
        'confirmed' => 'Confirmed',
        'cancelled' => 'Cancelled',
        'completed' => 'Completed',
    ];

    protected array $subjects = [
        'pending' => 'Your Appointment Request Received – QAC Houston',
        'confirmed' => 'Your Appointment Is Confirmed – QAC Houston',
        'cancelled' => 'Your Appointment Has Been Cancelled – QAC Houston',
        'completed' => 'Your Appointment Is Complete – QAC Houston',
    ];

    public function __construct(Appointment $appointment, string $status)
    {
        $this->appointment = $appointment->load(['customer', 'vehicle', 'service']);
        $this->status = $status;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjects[$this->status] ?? 'QAC Houston Appointment Update',
        );
    }

    public function content(): Content
    {
        $site = SiteSetting::first();

        return new Content(
            view: 'emails.appointment-status',
            with: [
                'appointment' => $this->appointment,
                'status' => $this->status,
                'statusLabel' => $this->labels[$this->status] ?? ucfirst($this->status),
                'site' => $site,
                'siteName' => $site->site_name ?? 'QAC Houston',
                'contactPhone' => $site->contact_phone ?? '(832) 896-3865',
                'contactEmail' => $site->contact_email ?? 'hello@qachouston.com',
                'address' => $site->address ?? null,
            ],
        );
    }
}
