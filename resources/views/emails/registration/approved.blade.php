<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registration Confirmed</title>
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

        .header img {
            width: 60px;
            margin-bottom: 16px;
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

        .bib-box {
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            border-radius: 8px;
            padding: 24px;
            text-align: center;
            margin-bottom: 24px;
        }

        .bib-label {
            color: #a0aec0;
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .bib-number {
            color: #ffffff;
            font-size: 52px;
            font-weight: bold;
            line-height: 1;
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
            background-color: #e85d04;
            color: #ffffff;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 6px;
            font-size: 15px;
            font-weight: bold;
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
            <p>Race Registration System</p>
            <div class="badge">✓ Registration Confirmed</div>
        </div>

        <div class="body">
            <div class="greeting">Hey {{ $registration->first_name }}! 👋</div>
            <p class="text">
                Great news! Your registration for the <strong>{{ $category->name }}</strong> has been reviewed and <strong>confirmed</strong>. You're officially in the race!
            </p>

            <div class="bib-box">
                <div class="bib-label">Your Race Bib Number</div>
                <div class="bib-number">#{{ $registration->formatted_bib }}</div>
            </div>

            <div class="details-card">
                <h3>Registration Details</h3>
                <table class="detail-table">
                    <tr>
                        <td class="detail-label">Name</td>
                        <td class="detail-value">{{ $registration->first_name }} {{ $registration->last_name }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Race Category</td>
                        <td class="detail-value">{{ $category->name }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Distance</td>
                        <td class="detail-value">{{ $category->distance_km }} KM</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Shirt Size</td>
                        <td class="detail-value">{{ $registration->shirt_size }}</td>
                    </tr>
                </table>
            </div>

            <p class="text">
                Please keep this email for your records. Present your bib number on race day. We'll send more details about the event as the date approaches.
            </p>

            <p class="text">See you at the finish line! 🏔️</p>
        </div>

        <div class="footer" style="margin-top:16px;">
            <p>© {{ date('Y') }} Ricon. All rights reserved.</p>
            <p>If you have questions, contact us at <a href="mailto:riconph1@gmail.com>riconph1@gmail.com</a></p>
        </div>
    </div>
</body>

</html>