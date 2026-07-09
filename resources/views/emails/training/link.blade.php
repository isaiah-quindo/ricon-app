<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Your Training Program</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }

        .wrapper {
            max-width: 600px;
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
            background-color: #F04C24;
            color: #ffffff;
            font-size: 13px;
            font-weight: bold;
            padding: 6px 16px;
            border-radius: 999px;
            margin: 24px auto 0;
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

        .details-card {
            background: #f8fafc;
            border-radius: 8px;
            padding: 20px 24px;
            margin-bottom: 24px;
        }

        .details-card h3 {
            font-size: 13px;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 0 16px;
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
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
            width: 50%;
        }

        .detail-value {
            color: #1a1a2e;
            font-weight: bold;
            text-align: right;
        }

        .cta {
            text-align: center;
            margin-bottom: 24px;
        }

        .cta a {
            display: inline-block;
            background-color: #F04C24;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 6px;
            font-size: 15px;
            font-weight: bold;
        }

        .link-fallback {
            font-size: 12px;
            color: #718096;
            word-break: break-all;
            text-align: center;
            margin-bottom: 24px;
        }

        .link-fallback a {
            color: #F04C24;
        }

        .upsell {
            background: #1a1a2e;
            border-radius: 8px;
            padding: 24px;
            text-align: center;
            margin-bottom: 24px;
        }

        .upsell-eyebrow {
            color: #f5a623;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin: 0 0 8px;
        }

        .upsell-title {
            color: #ffffff;
            font-size: 18px;
            margin: 0 0 10px;
        }

        .upsell-text {
            color: #a0aec0;
            font-size: 13px;
            line-height: 1.7;
            margin: 0 0 18px;
        }

        .upsell-btn {
            display: inline-block;
            background-color: #F04C24;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 26px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .upsell-note {
            color: #718096;
            font-size: 12px;
            margin: 0;
        }

        .upsell-note a {
            color: #f5a623;
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
            <h1>TGC Training Program</h1>
            <p>24 weeks. One mountain.</p>
            <div class="badge">Your Personal Access Link</div>
        </div>

        <div class="body">
            <div class="greeting">Hey {{ $signup->first_name }}! 👋</div>
            <p class="text">
                You're in. You've joined the 24-week <strong>{{ $signup->plan === 'tgc100k' ? '100K' : '60K' }}</strong> program. The whole community trains on one shared calendar, and the plan is currently on <strong>Week {{ \App\Models\TrainingSignup::currentProgramWeek() }} of 24</strong>.
            </p>

            <div class="details-card">
                <h3>Your Program</h3>
                <table class="detail-table">
                    <tr>
                        <td class="detail-label">Distance</td>
                        <td class="detail-value">{{ $signup->plan === 'tgc100k' ? '100K' : '60K' }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Joined</td>
                        <td class="detail-value">{{ $signup->started_on->format('F j, Y') }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Current Week</td>
                        <td class="detail-value">Week {{ \App\Models\TrainingSignup::currentProgramWeek() }} of 24</td>
                    </tr>
                </table>
            </div>

            <div class="cta">
                <a href="{{ $signup->program_url }}"
                    style="display:inline-block; background-color:#F04C24; color:#ffffff !important; text-decoration:none; padding:14px 32px; border-radius:6px; font-size:15px; font-weight:bold;">Open My Training Program</a>
            </div>

            <p class="link-fallback">
                Or copy this link: <a href="{{ $signup->program_url }}">{{ $signup->program_url }}</a>
            </p>

            <p class="text">
                <strong>Bookmark this link</strong>. It's your personal access to the program. All 24 weeks are open, and the current week is always highlighted based on the program calendar.
            </p>

            {{-- Premium upsell --}}
            <div class="upsell">
                <p class="upsell-eyebrow">Want more than the free plan?</p>
                <h3 class="upsell-title">Go premium with Edify Endurance</h3>
                <p class="upsell-text">
                    The free program is one shared calendar for everyone. If you joined late, or want a plan built around your current fitness, your schedule, and your goal race, the coaches behind this program offer premium, personalized training plans.
                </p>
                <a class="upsell-btn" href="mailto:edifyendurance@gmail.com?subject=Personalized%20Training%20Plan%20Inquiry"
                    style="display:inline-block; background-color:#F04C24; color:#ffffff !important; text-decoration:none; padding:12px 26px; border-radius:6px; font-size:14px; font-weight:bold;">Ask About Personalized Plans</a>
                <p class="upsell-note">Email <a href="mailto:edifyendurance@gmail.com">edifyendurance@gmail.com</a> for rates.</p>
            </div>

            <p class="text">See you on the trails! 🏔️</p>
        </div>

        <div class="footer" style="margin-top:16px;">
            <p>© {{ date('Y') }} Ricon. All rights reserved.</p>
            <p>If you have questions, contact us at <a href="mailto:riconph1@gmail.com">riconph1@gmail.com</a></p>
        </div>
    </div>
</body>

</html>
