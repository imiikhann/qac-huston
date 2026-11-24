<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $siteName }} - We've replied to your message</title>
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
                                Hi {{ $ticket->name }}, we've replied!
                            </h1>

                            <p style="color:#52525b; font-size:15px; line-height:1.6; margin:12px 0 24px 0;">
                                Thank you for reaching out to {{ $siteName }}. Our team has sent you a reply on your support conversation:
                            </p>

                            {{-- Ticket badge --}}
                            <table role="presentation" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                                <tr>
                                    <td style="background-color:#FFF3E0; border:1px solid #FF9000; color:#313639; font-size:12px; font-weight:bold; text-transform:uppercase; letter-spacing:1px; padding:8px 18px; border-radius:999px;">
                                        Ticket #{{ $ticketRef }} &middot; {{ strtoupper($ticket->status) }}
                                    </td>
                                </tr>
                            </table>

                            {{-- Reply message --}}
                            <div style="background-color:#f7f7f8; border:1px solid #e4e4e7; border-radius:8px; padding:20px 24px;">
                                <div style="font-size:12px; font-weight:bold; text-transform:uppercase; letter-spacing:1px; color:#FF9000; margin-bottom:8px;">
                                    Reply from {{ $siteName }}
                                </div>
                                <p style="margin:0; color:#313639; font-size:14px; line-height:1.6; white-space:pre-wrap;">{{ $reply->message }}</p>
                                <p style="margin:12px 0 0 0; color:#a1a1aa; font-size:12px;">
                                    — Sent {{ $reply->created_at->format('l, F j, Y \a\t g:i A') }}
                                </p>
                            </div>

                            <p style="color:#52525b; font-size:14px; line-height:1.6; margin:24px 0 0 0;">
                                Need to continue the conversation? Simply reply through the chat widget on our website —
                                just open the site and click the chat button at the bottom right corner.
                            </p>

                            {{-- Contact info --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:32px;">
                                <tr>
                                    <td align="center">
                                        <p style="margin:0; color:#52525b; font-size:13px;">Questions? Call us anytime at
                                            <a href="tel:{{ $contactPhone }}" style="color:#FF9000; font-weight:bold; text-decoration:none;">{{ $contactPhone }}</a>
                                        </p>
                                        <p style="margin:6px 0 0 0; color:#71717a; font-size:13px;">
                                            Email: <a href="mailto:{{ $contactEmail }}" style="color:#313639; text-decoration:none;">{{ $contactEmail }}</a>
                                            @if ($address) &nbsp;|&nbsp; {{ $address }} @endif
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color:#fafafa; padding:20px 40px; text-align:center; border-top:1px solid #e4e4e7;">
                            <p style="margin:0; color:#a1a1aa; font-size:12px;">&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
