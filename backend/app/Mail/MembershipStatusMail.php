<?php

namespace App\Mail;

use App\Models\Membership;
use App\Models\SiteSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MembershipStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public Membership $membership;

    public string $status;

    protected array $labels = [
        'pending' => 'Pending',
        'active' => 'Active',
        'expired' => 'Expired',
        'cancelled' => 'Cancelled',
    ];

    protected array $subjects = [
        'pending' => 'Your Membership Enrollment Request Received – QAC Houston',
        'active' => 'Your Membership Is Now Active – QAC Houston',
        'expired' => 'Your Membership Has Expired – QAC Houston',
        'cancelled' => 'Your Membership Has Been Cancelled – QAC Houston',
    ];

    public function __construct(Membership $membership, string $status)
    {
        $this->membership = $membership->load(['customer', 'program']);
        $this->status = $status;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjects[$this->status] ?? 'QAC Houston Membership Update',
        );
    }

    public function content(): Content
    {
        $site = SiteSetting::first();

        return new Content(
            view: 'emails.membership-status',
            with: [
                'membership' => $this->membership,
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

    public function attachments(): array
    {
        if ($this->status !== 'active') {
            return [];
        }

        $site = SiteSetting::first();

        try {
            $pdf = Pdf::loadView('pdf.membership-card', [
                'membership' => $this->membership,
                'siteName' => $site->site_name ?? 'QAC Houston',
                'contactPhone' => $site->contact_phone ?? '(832) 896-3865',
                'contactEmail' => $site->contact_email ?? 'hello@qachouston.com',
                'address' => $site->address ?? null,
            ])->output();

            return [
                Attachment::fromData(
                    fn () => $pdf,
                    'QAC-Membership-Card-' . $this->membership->membership_number . '.pdf'
                )
                    ->withMime('application/pdf'),
            ];
        } catch (\Throwable $e) {
            report($e);

            return [];
        }
    }
}
