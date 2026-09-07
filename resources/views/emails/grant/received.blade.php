<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TGC100 Grant Application Received</title>
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
            background-color: #1a1a1a;
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
            background-color: #f97316;
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
            color: #1a1a1a;
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
            color: #1a1a1a;
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
            <h1>TGC100 Grant</h1>
            <p>The Great Cordillera 100</p>
            <div class="badge">Application Received</div>
        </div>

        <div class="body">
            <div class="greeting">Hey {{ $application->full_name }}!</div>
            <p class="text">
                Thanks for applying for the <strong>TGC100 Grant</strong>. We've received your application and it's in the queue for review.
            </p>

            <div class="details-card">
                <h3>What Happens Next</h3>
                <table class="detail-table">
                    <tr>
                        <td class="detail-label">Applications close</td>
                        <td class="detail-value">September 13, 11:59 PM PHT</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Interview invite (if shortlisted)</td>
                        <td class="detail-value">Within 2-3 business days</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Grantee announced</td>
                        <td class="detail-value">September 18</td>
                    </tr>
                </table>
            </div>

            <p class="text">
                Keep an eye on your inbox, including your spam folder, for updates from us. All applicants are notified of the outcome, whether selected or not.
            </p>

            <p class="text">Thanks for putting yourself forward. Good luck.</p>
        </div>

        <div class="footer" style="margin-top:16px;">
            <p>&copy; {{ date('Y') }} RiCON. All rights reserved.</p>
            <p>Questions? Contact us at <a href="mailto:partnership@ricon.ph">partnership@ricon.ph</a></p>
        </div>
    </div>
</body>

</html>
