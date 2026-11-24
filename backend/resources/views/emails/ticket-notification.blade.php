<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $siteName }} - Support Ticket Notification</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f5; font-family:Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f5; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 16px rgba(0,0,0,0.08);">
                    {{-- Header --}}
                    <tr>
                        <td style="background-color:#313639; padding:28px 40px; text-align:center;">
                            <span style="color:#ffffff; font-size:22px; font-weight:bold; letter-spacing:1px;">
                                <span style="color:#FF9000;">QAC</span> HOUSTON
                            </span>
                            <div style="color:#a1a1aa; font-size:12px; margin-top:4px;">Quality Automotive Center</div>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:40px;">
                            <h1 style="margin:0 0 8px 0; color:#313639; font-size:22px;">
                                {{ $type === 'new_ticket' ? 'New support ticket received' : 'New message from ' . $ticket->name }}
                            </h1>

                            <p style="color:#52525b; font-size:15px; line-height:1.6; margin:12px 0 24px 0;">
                                @if ($type === 'new_ticket')
                                    A visitor has started a conversation through the website chat widget. Open the ticket below to read and reply.
                                @else
                                    {{ $ticket->name }} has sent a new message on an existing ticket. Open the ticket below to read and reply.
                                @endif
                            </p>

                            {{-- Badge --}}
                            <table role="presentation" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                                <tr>
                                    <td style="background-color:#FFF3E0; border:1px solid #FF9000; color:#313639; font-size:12px; font-weight:bold; text-transform:uppercase; letter-spacing:1px; padding:8px 18px; border-radius:999px;">
                                        Ticket #{{ $ticketRef }} &middot; {{ strtoupper($ticket->status) }}
                                    </td>
                                </tr>
                            </table>

                            {{-- Details --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#fafafa; border:1px solid #e4e4e7; border-radius:8px;">
                                <tr>
                                    <td style="padding:16px 24px; border-bottom:1px solid #e4e4e7; width:140px; color:#71717a; font-size:13px;">Name</td>
                                    <td style="padding:16px 24px; border-bottom:1px solid #e4e4e7; color:#313639; font-size:14px; font:bold;">
                                        <strong>{{ $ticket->name }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:16px 24px; border-bottom:1px solid #e4e4e7; color:#71717a; font-size:13px;">Email</td>
                                    <td style="padding:16px 24px; border-bottom:1px solid #e4e4e7; color:#313639; font-size:14px;">
                                        <a href="mailto:{{ $ticket->email }}" style="color:#FF9000; text-decoration:none;">{{ $ticket->email }}</a>
                                    </td>
                                </tr>
                                @if ($ticket->phone)
                                <tr>
                                    <td style="padding:16px 24px; border-bottom:1px solid #e4e4e7; color:#71717a; font-size:13px;">Phone</td>
                                    <td style="padding:16px 24px; border-bottom:1px solid #e4e4e7; color:#313639; font-size:14px;">{{ $ticket->phone }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td style="padding:16px 24px; border-bottom:1px solid #e4e4e7; color:#71717a; font-size:13px;">Subject</td>
                                    <td style="padding:16px 24px; border-bottom:1px solid #e4e4e7; color:#313639; font-size:14px;">{{ $ticket->subject ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:16px 24px; color:#71717a; font-size:13px;">Received</td>
                                    <td style="padding:16px 24px; color:#313639; font-size:14px;">{{ $ticketMessage->created_at->format('l, F j, Y \a\t g:i A') }}</td>
                                </tr>
                            </table>

                            {{-- Message --}}
                            <div style="margin-top:24px; background-color:#fffbeb; border:1px solid #fde68a; border-radius:8px; padding:20px 24px;">
                                <div style="font-size:12px; font-weight:bold; text-transform:uppercase; letter-spacing:1px; color:#b45309; margin-bottom:8px;">
                                    {{ $ticketMessage->sender === 'admin' ? 'Your reply' : 'Customer message' }}
                                </div>
                                <p style="margin:0; color:#313639; font-size:14px; line-height:1.6; white-space:pre-wrap;">{{ $ticketMessage->message }}</p>
                            </div>

                            {{-- CTA --}}
                            <table role="presentation" cellpadding="0" cellspacing="0" style="margin-top:28px;">
                                <tr>
                                    <td style="background-color:#FF9000; border-radius:8px; text-align:center;">
                                        <a href="{{ $adminUrl }}" style="display:inline-block; padding:14px 32px; color:#ffffff; font-size:15px; font-weight:bold; text-decoration:none;">
                                            View &amp; Reply in Admin Panel
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:24px 0 0 0; color:#71717a; font-size:13px; line-height:1.6;">
                                You are receiving this because you are an administrator of {{ $siteName }}.
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color:#fafafa; padding:20px 40px; text-align:center; border-top:1px solid #e4e4e7;">
                            <p style="margin:0; color:#71717a; font-size:13px;">
                                Questions? Call us at
                                <a href="tel:{{ $contactPhone }}" style="color:#FF9000; font-weight:bold; text-decoration:none;">{{ $contactPhone }}</a>
                                &nbsp;|&nbsp;
                                <a href="mailto:{{ $contactEmail }}" style="color:#313639; text-decoration:none;">{{ $contactEmail }}</a>
                            </p>
                            <p style="margin:6px 0 0 0; color:#a1a1aa; font-size:12px;">&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
