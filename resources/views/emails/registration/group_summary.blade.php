<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Group Registration Summary</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }

        .wrapper {
            max-width: 640px;
            margin: 40px auto !important;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .header {
            background-color: #1a1a2e;
            padding: 40px 32px !important;
            text-align: center;
        }

        .header h1 {
            color: #ffffff;
            font-size: 22px;
            margin: 0 0 4px;
        }

        .header p {
            color: #a0aec0;
            font-size: 14px;
            margin: 0;
        }

        .badge {
            display: inline-block;
            background-color: #22c55e;
            color: #ffffff;
            font-size: 13px;
            font-weight: bold;
            padding: 6px 16px;
            border-radius: 999px;
            margin: 24px auto 0;
        }

        .badge-pending {
            background-color: #f59e0b;
        }

        .body {
            padding: 32px !important;
        }

        .greeting {
            font-size: 18px;
            font-weight: bold;
            color: #1a1a2e;
            margin-bottom: 8px;
        }

        .text {
            font-size: 15px;
            color: #4a5568;
            line-height: 1.7;
            margin-bottom: 24px;
        }

        .ref-box {
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            border-radius: 8px;
            padding: 24px;
            text-align: center;
            margin-bottom: 24px;
        }

        .ref-label {
            color: #a0aec0;
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .ref-code {
            color: #ffffff;
            font-size: 34px;
            font-weight: bold;
            line-height: 1;
            letter-spacing: 2px;
        }

        .card {
            background: #f8fafc;
            border-radius: 8px;
            padding: 20px 24px;
            margin-bottom: 24px;
        }

        .card h3 {
            font-size: 13px;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 0 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .roster th {
            font-size: 11px;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-align: left;
            padding: 0 0 10px;
            border-bottom: 1px solid #e2e8f0;
        }

        .roster td {
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
            color: #1a1a2e;
            vertical-align: top;
        }

        .roster tr:last-child td {
            border-bottom: none;
        }

        .muted {
            color: #718096;
            font-size: 12px;
        }

        .bib {
            font-weight: bold;
            color: #1a1a2e;
            white-space: nowrap;
        }

        .pill-approved {
            display: inline-block;
            background: #dcfce7;
            color: #15803d;
            font-size: 11px;
            font-weight: bold;
            padding: 3px 10px;
            border-radius: 999px;
        }

        .pill-rejected {
            display: inline-block;
            background: #fee2e2;
            color: #b91c1c;
            font-size: 11px;
            font-weight: bold;
            padding: 3px 10px;
            border-radius: 999px;
        }

        .detail-table tr td {
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .detail-table tr:last-child td {
            border-bottom: none;
        }

        .detail-label {
            color: #718096;
            width: 55%;
        }

        .detail-value {
            color: #1a1a2e;
            font-weight: bold;
            text-align: right;
        }

        .total-row td {
            padding-top: 14px !important;
            font-size: 16px;
        }

        .footer {
            background: #f8fafc;
            padding: 24px 32px;
            text-align: center;
            font-size: 12px;
            color: #a0aec0;
            border-top: 1px solid #e2e8f0;
        }

        .footer a {
            color: #718096;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="header">
            <h1>The Great Cordillera 100</h1>
            <p>Group Registration Summary</p>
            @if($approved->count() === 0)
            <div class="badge badge-pending">Awaiting confirmation</div>
            @elseif($approved->count() === $group->participant_count)
            <div class="badge">✓ All {{ $group->participant_count }} Confirmed</div>
            @else
            <div class="badge badge-pending">{{ $approved->count() }} of {{ $group->participant_count }} Confirmed</div>
            @endif
        </div>

        <div class="body">
            <div class="greeting">Hi {{ $group->organizer_name ?? 'there' }}! 👋</div>
            <p class="text">
                @if($approved->count() === 0)
                    {{-- Only reachable when an admin sends the recap before approving anyone. --}}
                    Here is a summary of your group registration. None of the
                    <strong>{{ $group->participant_count }}</strong> entries are confirmed yet.
                    We will email each participant, and you, once they are.
                @elseif($approved->count() === $group->participant_count)
                    Your group registration has been reviewed. All
                    <strong>{{ $approved->count() }}</strong> participants are confirmed and their
                    bib numbers are below. Each participant has also been emailed their own
                    confirmation directly.
                @else
                    Your group registration has been reviewed.
                    <strong>{{ $approved->count() }}</strong> of {{ $group->participant_count }}
                    participants are confirmed. Anything not confirmed is flagged in the list below.
                    Each confirmed participant has also been emailed directly.
                @endif
            </p>

            <div class="ref-box">
                <div class="ref-label">Group Reference</div>
                <div class="ref-code">{{ $group->reference_code }}</div>
            </div>

            <div class="card">
                <h3>Participants</h3>
                <table class="roster">
                    <tr>
                        <th>Participant</th>
                        <th>Category</th>
                        <th>Bib</th>
                        <th style="text-align:right;">Status</th>
                    </tr>
                    @foreach($members as $member)
                    <tr>
                        <td>
                            {{ $member->first_name }} {{ $member->last_name }}
                            <div class="muted">{{ $member->email }} &middot; Shirt {{ $member->shirt_size }}</div>
                        </td>
                        <td>{{ $member->raceCategory?->name ?? '—' }}</td>
                        <td class="bib">{{ $member->formatted_bib ?? '—' }}</td>
                        <td style="text-align:right;">
                            @if($member->status === 'approved')
                            <span class="pill-approved">Confirmed</span>
                            @else
                            <span class="pill-rejected">Not confirmed</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>

            <div class="card">
                <h3>Payment</h3>
                <table class="detail-table">
                    <tr>
                        <td class="detail-label">Method</td>
                        <td class="detail-value">{{ $group->payment_method ?: 'Not specified' }}</td>
                    </tr>
                    @if($group->payment_reference)
                    <tr>
                        <td class="detail-label">Reference</td>
                        <td class="detail-value">{{ $group->payment_reference }}</td>
                    </tr>
                    @endif
                    @if($group->verified_at)
                    <tr>
                        <td class="detail-label">Payment received</td>
                        <td class="detail-value">{{ $group->verified_at->format('F j, Y') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="detail-label">Subtotal ({{ $group->participant_count }} entries)</td>
                        <td class="detail-value">₱{{ number_format($group->subtotal, 2) }}</td>
                    </tr>
                    @if($group->discount_total > 0)
                    <tr>
                        <td class="detail-label">
                            Group discount ({{ rtrim(rtrim(number_format($group->group_discount_percentage, 2), '0'), '.') }}%)
                        </td>
                        <td class="detail-value" style="color:#15803d;">−₱{{ number_format($group->discount_total, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="total-row">
                        <td class="detail-label"><strong>Total</strong></td>
                        <td class="detail-value">₱{{ number_format($group->total_due, 2) }}</td>
                    </tr>
                </table>
            </div>

            <p class="text" style="margin-bottom:0;">
                Please share the bib numbers above with your team. If anything looks wrong,
                reply to this email quoting <strong>{{ $group->reference_code }}</strong> and we will sort it out.
            </p>
        </div>

        <div class="footer">
            <p>Questions? Contact us at <a href="mailto:riconph1@gmail.com">riconph1@gmail.com</a></p>
            <p>The Great Cordillera 100 Ultra Trail &copy; {{ date('Y') }}</p>
        </div>
    </div>
</body>

</html>
