<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registration Update</title>
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
            background-color: #ef4444;
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

        .reason-box {
            background: #fff5f5;
            border-left: 4px solid #ef4444;
            border-radius: 4px;
            padding: 16px 20px;
            margin-bottom: 24px;
        }

        .reason-box h3 {
            font-size: 13px;
            color: #e53e3e;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 0 8px;
        }

        .reason-box p {
            font-size: 14px;
            color: #4a5568;
            margin: 0;
            line-height: 1.6;
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
            <h1>The Great Cordileera 100</h1>
            <p>Race Registration System</p>
            <div class="badge">Registration Update</div>
        </div>

        <div class="body">
            <div class="greeting">Hi {{ $registration->first_name }},</div>
            <p class="text">
                Thank you for registering for the <strong>{{ $category->name }}</strong>. After reviewing your registration, we regret to inform you that we were unable to confirm your slot at this time.
            </p>

            @if ($registration->admin_notes)
            <div class="reason-box">
                <h3>Reason</h3>
                <p>{{ $registration->admin_notes }}</p>
            </div>
            @endif

            <div class="details-card">
                <h3>Your Submission</h3>
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
                        <td class="detail-label">Submitted On</td>
                        <td class="detail-value">{{ $registration->created_at->format('F j, Y') }}</td>
                    </tr>
                </table>
            </div>

            <p class="text">
                If you believe this is a mistake or would like to clarify, please don't hesitate to reach out to us by replying to this email. We'd be happy to help.
            </p>

            <p class="text">Thank you for your interest and we hope to see you in future events! 🙏</p>
        </div>

        <div class="footer" style="margin-top:16px;">
            <p>© {{ date('Y') }} The Great Cordillera 100. All rights reserved.</p>
            <p>Questions? Contact us at <a href="mailto:riconph1@gmail.com">riconph1@gmail.com</a></p>
        </div>
    </div>
</body>

</html>