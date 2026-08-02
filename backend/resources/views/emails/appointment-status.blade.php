<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? $siteName . ' - Appointment Update' }}</title>
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
                                {{ $status === 'pending' ? 'Thank you, ' . ($appointment->customer->name ?? '') . '!' : ($appointment->customer->name ?? 'Hi') . ', your appointment is ' . strtolower($statusLabel) . '.' }}
                            </h1>

                            @if ($status === 'pending')
                                <p style="color:#52525b; font-size:15px; line-height:1.6; margin:12px 0 24px 0;">
                                    We have received your appointment request. Our team will review it shortly and confirm your booking.
                                    Here are the details of your request:
                                </p>
                            @elseif ($status === 'confirmed')
                                <p style="color:#52525b; font-size:15px; line-height:1.6; margin:12px 0 24px 0;">
                                    Great news! Your appointment has been confirmed. We look forward to seeing you at
                                    {{ $siteName }}. Here are your appointment details:
                                </p>
                            @elseif ($status === 'cancelled')
                                <p style="color:#52525b; font-size:15px; line-height:1.6; margin:12px 0 24px 0;">
                                    Your appointment has been cancelled. If this was done in error or you would like to reschedule,
                                    please contact us and we will be happy to help.
                                </p>
                            @elseif ($status === 'completed')
                                <p style="color:#52525b; font-size:15px; line-height:1.6; margin:12px 0 24px 0;">
                                    Thank you for visiting {{ $siteName }}! Your appointment has been completed.
                                    We appreciate your business and hope to see you again.
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

                            {{-- Details --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#fafafa; border:1px solid #e4e4e7; border-radius:8px;">
                                <tr>
                                    <td style="padding:20px 24px; border-bottom:1px solid #e4e4e7; width:160px; color:#71717a; font-size:13px;">Service</td>
                                    <td style="padding:20px 24px; border-bottom:1px solid #e4e4e7; color:#313639; font-size:14px; font-weight:bold;">
                                        {{ $appointment->service->name ?? 'General Service' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:20px 24px; border-bottom:1px solid #e4e4e7; color:#71717a; font-size:13px;">Vehicle</td>
                                    <td style="padding:20px 24px; border-bottom:1px solid #e4e4e7; color:#313639; font-size:14px; font-weight:bold;">
                                        {{ $appointment->vehicle ? ($appointment->vehicle->make . ' ' . $appointment->vehicle->model . ($appointment->vehicle->year ? ' ' . $appointment->vehicle->year : '')) : '—' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:20px 24px; border-bottom:1px solid #e4e4e7; color:#71717a; font-size:13px;">Date</td>
                                    <td style="padding:20px 24px; border-bottom:1px solid #e4e4e7; color:#313639; font-size:14px; font-weight:bold;">
                                        {{ $appointment->requested_date ? \Carbon\Carbon::parse($appointment->requested_date)->format('l, F j, Y') : '—' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:20px 24px; border-bottom:1px solid #e4e4e7; color:#71717a; font-size:13px;">Time</td>
                                    <td style="padding:20px 24px; border-bottom:1px solid #e4e4e7; color:#313639; font-size:14px; font-weight:bold;">
                                        {{ $appointment->requested_time ? \Carbon\Carbon::parse($appointment->requested_time)->format('g:i A') : '—' }}
                                    </td>
                                </tr>
                                @if ($appointment->notes)
                                <tr>
                                    <td style="padding:20px 24px; color:#71717a; font-size:13px; vertical-align:top;">Notes</td>
                                    <td style="padding:20px 24px; color:#313639; font-size:14px;">{{ $appointment->notes }}</td>
                                </tr>
                                @endif
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
