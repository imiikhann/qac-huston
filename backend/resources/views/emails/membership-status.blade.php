<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $siteName }} - Membership Update</title>
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
                                {{ $membership->customer->name ?? 'Hi' }}, your membership is {{ strtolower($statusLabel) }}.
                            </h1>

                            @if ($status === 'pending')
                                <p style="color:#52525b; font-size:15px; line-height:1.6; margin:12px 0 24px 0;">
                                    We have received your membership enrollment request for
                                    <strong>{{ $membership->program->name ?? 'our membership program' }}</strong>.
                                    Our team will review it shortly and activate your membership. Here are the details of your request:
                                </p>
                            @elseif ($status === 'active')
                                <p style="color:#52525b; font-size:15px; line-height:1.6; margin:12px 0 24px 0;">
                                    Great news! Your membership in
                                    <strong>{{ $membership->program->name ?? 'our membership program' }}</strong>
                                    is now active. Your digital membership card is attached as a <strong>PDF</strong> below —
                                    download and print it, or simply show it on your phone, to enjoy your exclusive benefits!
                                </p>
                                <table role="presentation" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                                    <tr>
                                        <td style="background-color:#FFF3E0; border:1px solid #FF9000; color:#313639; font-size:12px; font-weight:bold; padding:10px 16px; border-radius:8px;">
                                            📎 Attachment: <span style="color:#FF9000;">QAC-Membership-Card-{{ $membership->membership_number }}.pdf</span>
                                        </td>
                                    </tr>
                                </table>
                            @elseif ($status === 'expired')
                                <p style="color:#52525b; font-size:15px; line-height:1.6; margin:12px 0 24px 0;">
                                    Your membership has expired. Renew your membership to continue enjoying your benefits.
                                    Contact us and we will be happy to help you renew.
                                </p>
                            @elseif ($status === 'cancelled')
                                <p style="color:#52525b; font-size:15px; line-height:1.6; margin:12px 0 24px 0;">
                                    Your membership has been cancelled. If this was done in error or you would like to rejoin,
                                    please contact us and we will be happy to help.
                                </p>
                            @endif

                            {{-- Status badge --}}
                            <table role="presentation" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                                <tr>
                                    <td style="background-color:#FFF3E0; border:1px solid #FF9000; color:#313639; font-size:12px; font-weight:bold; text-transform:uppercase; letter-spacing:1px; padding:8px 18px; border-radius:999px;">
                                        Status: {{ $statusLabel }}
                                    </td>
                                </tr>
                            </table>

                            @if ($status === 'active')
                                {{-- Membership Card --}}
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px; border-radius:16px; overflow:hidden; border:1px solid #e4e4e7; box-shadow:0 8px 24px rgba(49,54,57,0.12);">
                                    <tr>
                                        <td style="background-color:#313639; padding:18px 24px;">
                                            <table role="presentation" width="100%">
                                                <tr>
                                                    <td>
                                                        <span style="color:#ffffff; font-size:17px; font-weight:bold; letter-spacing:1px;">
                                                            <span style="color:#FF9000;">QAC</span> HOUSTON
                                                        </span>
                                                    </td>
                                                    <td align="right">
                                                        <span style="color:#FF9000; font-size:10px; font-weight:bold; letter-spacing:2px;">MEMBERSHIP CARD</span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:26px 28px 22px 28px; background-color:#ffffff;">
                                            <table role="presentation" width="100%">
                                                <tr>
                                                    <td width="72%">
                                                        <div style="color:#71717a; font-size:10px; text-transform:uppercase; letter-spacing:1px; margin-bottom:4px;">Member</div>
                                                        <div style="color:#313639; font-size:20px; font-weight:bold;">{{ $membership->customer->name }}</div>
                                                    </td>
                                                    <td align="right">
                                                        <div style="color:#71717a; font-size:10px; text-transform:uppercase; letter-spacing:1px; margin-bottom:4px;">Member Since</div>
                                                        <div style="color:#313639; font-size:13px; font-weight:bold;">{{ $membership->start_date ? \Carbon\Carbon::parse($membership->start_date)->format('M j, Y') : '—' }}</div>
                                                    </td>
                                                </tr>
                                            </table>

                                            <table role="presentation" width="100%" style="margin-top:20px;">
                                                <tr>
                                                    <td width="50%">
                                                        <div style="color:#71717a; font-size:10px; text-transform:uppercase; letter-spacing:1px; margin-bottom:4px;">Membership No.</div>
                                                        <div style="color:#313639; font-size:14px; font-weight:bold; letter-spacing:1px;">{{ $membership->membership_number }}</div>
                                                    </td>
                                                    <td width="50%">
                                                        <div style="color:#71717a; font-size:10px; text-transform:uppercase; letter-spacing:1px; margin-bottom:4px;">Valid Through</div>
                                                        <div style="color:#313639; font-size:14px; font-weight:bold;">{{ $membership->end_date ? \Carbon\Carbon::parse($membership->end_date)->format('M j, Y') : 'Lifetime' }}</div>
                                                    </td>
                                                </tr>
                                            </table>

                                            <table role="presentation" width="100%" style="margin-top:18px;">
                                                <tr>
                                                    <td style="background-color:#FFF3E0; border-left:4px solid #FF9000; border-radius:8px; padding:12px 16px;">
                                                        <div style="color:#71717a; font-size:10px; text-transform:uppercase; letter-spacing:1px; margin-bottom:2px;">Program</div>
                                                        <div style="color:#313639; font-size:14px; font-weight:bold;">
                                                            {{ $membership->program->name ?? ($membership->tier ? ucfirst($membership->tier) : '—') }}
                                                            @if ($membership->program?->price)
                                                                &nbsp;·&nbsp;<span style="color:#FF9000;">{{ $membership->program->price }}</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>

                                            {{-- Barcode --}}
                                            <table role="presentation" width="100%" style="margin-top:22px;">
                                                <tr>
                                                    <td align="center">
                                                        <div style="display:inline-block;">
                                                            <span style="display:inline-block; width:1px; height:34px; background:#313639; margin:0 1px;"></span><span style="display:inline-block; width:3px; height:34px; background:#313639; margin:0 1px;"></span><span style="display:inline-block; width:1px; height:34px; background:#313639; margin:0 1px;"></span><span style="display:inline-block; width:2px; height:34px; background:#313639; margin:0 1px;"></span><span style="display:inline-block; width:1px; height:34px; background:#313639; margin:0 1px;"></span><span style="display:inline-block; width:3px; height:34px; background:#313639; margin:0 1px;"></span><span style="display:inline-block; width:1px; height:34px; background:#313639; margin:0 1px;"></span><span style="display:inline-block; width:2px; height:34px; background:#313639; margin:0 1px;"></span><span style="display:inline-block; width:3px; height:34px; background:#313639; margin:0 1px;"></span><span style="display:inline-block; width:1px; height:34px; background:#313639; margin:0 1px;"></span><span style="display:inline-block; width:1px; height:34px; background:#313639; margin:0 1px;"></span><span style="display:inline-block; width:2px; height:34px; background:#313639; margin:0 1px;"></span><span style="display:inline-block; width:3px; height:34px; background:#313639; margin:0 1px;"></span><span style="display:inline-block; width:1px; height:34px; background:#313639; margin:0 1px;"></span><span style="display:inline-block; width:2px; height:34px; background:#313639; margin:0 1px;"></span><span style="display:inline-block; width:1px; height:34px; background:#313639; margin:0 1px;"></span><span style="display:inline-block; width:3px; height:34px; background:#313639; margin:0 1px;"></span><span style="display:inline-block; width:1px; height:34px; background:#313639; margin:0 1px;"></span><span style="display:inline-block; width:2px; height:34px; background:#313639; margin:0 1px;"></span><span style="display:inline-block; width:1px; height:34px; background:#313639; margin:0 1px;"></span><span style="display:inline-block; width:3px; height:34px; background:#313639; margin:0 1px;"></span><span style="display:inline-block; width:1px; height:34px; background:#313639; margin:0 1px;"></span><span style="display:inline-block; width:2px; height:34px; background:#313639; margin:0 1px;"></span><span style="display:inline-block; width:1px; height:34px; background:#313639; margin:0 1px;"></span><span style="display:inline-block; width:3px; height:34px; background:#313639; margin:0 1px;"></span><span style="display:inline-block; width:2px; height:34px; background:#313639; margin:0 1px;"></span><span style="display:inline-block; width:1px; height:34px; background:#313639; margin:0 1px;"></span><span style="display:inline-block; width:2px; height:34px; background:#313639; margin:0 1px;"></span><span style="display:inline-block; width:1px; height:34px; background:#313639; margin:0 1px;"></span><span style="display:inline-block; width:3px; height:34px; background:#313639; margin:0 1px;"></span>
                                                        </div>
                                                        <div style="color:#71717a; font-size:10px; letter-spacing:4px; margin-top:6px;">{{ $membership->membership_number }}</div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            @endif

                            {{-- Details --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#fafafa; border:1px solid #e4e4e7; border-radius:8px;">
                                <tr>
                                    <td style="padding:20px 24px; border-bottom:1px solid #e4e4e7; width:160px; color:#71717a; font-size:13px;">Program</td>
                                    <td style="padding:20px 24px; border-bottom:1px solid #e4e4e7; color:#313639; font-size:14px; font-weight:bold;">
                                        {{ $membership->program->name ?? ($membership->tier ? ucfirst($membership->tier) : '—') }}
                                    </td>
                                </tr>
                                @if ($membership->program?->price)
                                <tr>
                                    <td style="padding:20px 24px; border-bottom:1px solid #e4e4e7; color:#71717a; font-size:13px;">Price</td>
                                    <td style="padding:20px 24px; border-bottom:1px solid #e4e4e7; color:#313639; font-size:14px; font-weight:bold;">
                                        {{ $membership->program->price }}
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td style="padding:20px 24px; border-bottom:1px solid #e4e4e7; color:#71717a; font-size:13px;">Start Date</td>
                                    <td style="padding:20px 24px; border-bottom:1px solid #e4e4e7; color:#313639; font-size:14px; font-weight:bold;">
                                        {{ $membership->start_date ? \Carbon\Carbon::parse($membership->start_date)->format('F j, Y') : '—' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:20px 24px; color:#71717a; font-size:13px;">End Date</td>
                                    <td style="padding:20px 24px; color:#313639; font-size:14px; font-weight:bold;">
                                        {{ $membership->end_date ? \Carbon\Carbon::parse($membership->end_date)->format('F j, Y') : '—' }}
                                    </td>
                                </tr>
                            </table>

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
