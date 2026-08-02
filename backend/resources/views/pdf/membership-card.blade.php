<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $siteName ?? 'QAC Houston' }} - Membership Card</title>
    <style>
        * { margin: 0; padding: 0; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #313639; }
        .page { padding: 24px 28px; }

        .brand { text-align: center; margin-bottom: 20px; }
        .brand-name { font-size: 26px; font-weight: bold; letter-spacing: 2px; color: #313639; }
        .brand-name .accent { color: #FF9000; }
        .brand-tag { font-size: 11px; color: #71717a; letter-spacing: 3px; margin-top: 2px; }

        .card {
            width: 100%;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid #d4d4d8;
        }
        .card-head {
            background-color: #313639;
            padding: 16px 26px;
        }
        .card-head table { width: 100%; }
        .card-brand { color: #ffffff; font-size: 18px; font-weight: bold; letter-spacing: 1px; }
        .card-brand .accent { color: #FF9000; }
        .card-flag { color: #FF9000; font-size: 9px; font-weight: bold; letter-spacing: 2px; text-align: right; }
        .card-body { padding: 24px 26px; background-color: #ffffff; }

        .field { margin-bottom: 14px; }
        .field-label { font-size: 8px; color: #71717a; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 3px; }
        .field-value { font-size: 15px; font-weight: bold; color: #313639; }

        .member { font-size: 21px; font-weight: bold; color: #313639; }
        .program {
            background-color: #FFF3E0;
            border-left: 4px solid #FF9000;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            font-weight: bold;
            color: #313639;
        }
        .program .price { color: #FF9000; }
        .program-label { font-size: 8px; color: #71717a; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 2px; }

        .barcode {
            text-align: center;
            margin-top: 18px;
        }
        .barcode-bar {
            display: inline-block;
            background-color: #313639;
            height: 34px;
            margin: 0 1px;
        }
        .barcode-num { font-size: 9px; letter-spacing: 4px; color: #71717a; margin-top: 5px; }

        .notes {
            margin-top: 26px;
            border-top: 1px solid #e4e4e7;
            padding-top: 18px;
        }
        .notes-title { font-size: 12px; font-weight: bold; color: #313639; margin-bottom: 6px; }
        .notes p { font-size: 11px; color: #52525b; line-height: 1.7; }

        .contact { text-align: center; margin-top: 22px; font-size: 10px; color: #71717a; }
    </style>
</head>
<body>
    <div class="page">
        {{-- Brand --}}
        <div class="brand">
            <div class="brand-name"><span class="accent">QAC</span> HOUSTON</div>
            <div class="brand-tag">QUALITY AUTOMOTIVE CENTER</div>
        </div>

        {{-- Membership Card --}}
        <div class="card">
            <div class="card-head">
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td class="card-brand"><span class="accent">QAC</span> HOUSTON</td>
                        <td class="card-flag">MEMBERSHIP CARD</td>
                    </tr>
                </table>
            </div>
            <div class="card-body">
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tr>
                        <td width="70%" valign="top">
                            <div class="field-label">Member</div>
                            <div class="member">{{ $membership->customer->name ?? '—' }}</div>
                        </td>
                        <td align="right" valign="top">
                            <div class="field-label">Member Since</div>
                            <div class="field-value">
                                {{ $membership->start_date ? \Carbon\Carbon::parse($membership->start_date)->format('M j, Y') : '—' }}
                            </div>
                        </td>
                    </tr>
                </table>

                <table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-top:18px;">
                    <tr>
                        <td width="50%" valign="top">
                            <div class="field-label">Membership No.</div>
                            <div class="field-value" style="letter-spacing:1px;">{{ $membership->membership_number }}</div>
                        </td>
                        <td width="50%" valign="top">
                            <div class="field-label">Valid Through</div>
                            <div class="field-value">{{ $membership->end_date ? \Carbon\Carbon::parse($membership->end_date)->format('M j, Y') : 'Lifetime' }}</div>
                        </td>
                    </tr>
                </table>

                <div style="margin-top:18px;">
                    <div class="program">
                        <div class="program-label">Program</div>
                        {{ $membership->program->name ?? ($membership->tier ? ucfirst($membership->tier) : '—') }}
                        @if ($membership->program?->price)
                            &nbsp;·&nbsp;<span class="price">{{ $membership->program->price }}</span>
                        @endif
                    </div>
                </div>

                {{-- Barcode --}}
                <div class="barcode">
                    @php
                        $bars = [1,3,1,2,1,3,1,2,3,1,1,2,3,1,2,1,3,1,2,1,3,1,2,1,3,2,1,2,1,3];
                    @endphp
                    @foreach ($bars as $w)
                        <span class="barcode-bar" style="width:{{ $w }}px;"></span>
                    @endforeach
                    <div class="barcode-num">{{ $membership->membership_number }}</div>
                </div>
            </div>
        </div>

        {{-- Notes --}}
        <div class="notes">
            <div class="notes-title">How to use your membership</div>
            <p>
                Present this card (printed or on your phone) at QAC Houston to enjoy your exclusive membership benefits.
                Questions? Call us at {{ $contactPhone ?? '(832) 896-3865' }}
                or email {{ $contactEmail ?? 'hello@qachouston.com' }}.
            </p>
        </div>

        <div class="contact">
            &copy; {{ date('Y') }} {{ $siteName ?? 'QAC Houston' }}{{ !empty($address) ? ' | ' . $address : '' }}
        </div>
    </div>
</body>
</html>
