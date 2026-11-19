<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title')</title>
    <style>
        body { margin: 0; padding: 0; background-color: #f4f4f4; font-family: Arial, sans-serif; }
        .wrapper { max-width: 600px; margin: 40px auto !important; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); }
        .header { background-color: #0a0a0a; padding: 36px 32px !important; text-align: center; }
        .header h1 { color: #ffffff; font-size: 22px; margin: 0 0 4px; }
        .header p { color: #a0aec0; font-size: 14px; margin: 0; }
        .badge { display: inline-block; color: #ffffff; font-size: 13px; font-weight: bold; padding: 6px 16px; border-radius: 999px; margin: 20px auto 0; }
        .body { padding: 32px !important; }
        .greeting { font-size: 18px; font-weight: bold; color: #1a1a2e; margin-bottom: 8px; }
        .text { font-size: 15px; color: #4a5568; line-height: 1.7; margin: 0 0 20px; }
        .highlight { background: #0a0a0a; border-radius: 8px; padding: 22px; text-align: center; margin-bottom: 24px; }
        .highlight-label { color: #a0aec0; font-size: 12px; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 8px; }
        .highlight-value { color: #ffffff; font-size: 28px; font-weight: bold; letter-spacing: 1px; }
        .card { background: #f8fafc; border-radius: 8px; padding: 20px 24px; margin-bottom: 24px; }
        .card h3 { font-size: 13px; color: #718096; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 12px; }
        .table { width: 100%; border-collapse: collapse; font-size: 14px; }
        .table td { padding: 8px 0; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        .table tr:last-child td { border-bottom: none; }
        .muted { color: #718096; }
        .right { text-align: right; color: #1a1a2e; font-weight: bold; white-space: nowrap; }
        .total td { font-size: 15px; color: #1a1a2e; font-weight: bold; }
        .cta { text-align: center; margin-bottom: 24px; }
        .cta a { display: inline-block; background-color: #ea580c; color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 6px; font-size: 15px; font-weight: bold; }
        .footer { background: #f8fafc; padding: 24px 32px; text-align: center; font-size: 12px; color: #a0aec0; border-top: 1px solid #e2e8f0; }
        .footer a { color: #718096; text-decoration: none; }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="header">
            <h1>TGC100 Merch</h1>
            <p>The Great Cordillera 100</p>
            @yield('badge')
        </div>

        <div class="body">
            @yield('content')
        </div>

        <div class="footer">
            <p>Order reference: <strong>{{ $order->reference }}</strong></p>
            <p>Questions about your order? Email <a href="mailto:{{ config('shop.contact_email') }}">{{ config('shop.contact_email') }}</a> and quote your reference.</p>
            <p>&copy; {{ date('Y') }} RiCON. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
