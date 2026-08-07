<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/svg+xml" href="/logomark.svg">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Something Went Wrong — RICON</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    {{-- Self-contained on purpose: no @vite, no route() — this page must render even when the app is degraded. --}}
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #0a0a0a;
            color: #ffffff;
            font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .container {
            text-align: center;
            max-width: 36rem;
        }

        .logo {
            margin-bottom: 3rem;
        }

        .code {
            color: #f97316;
            font-size: 4.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .message {
            color: #9ca3af;
            font-size: 1rem;
            line-height: 1.65;
            margin-bottom: 2.5rem;
        }

        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 700;
            color: #ffffff;
            background-color: #ea580c;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: background-color 0.15s ease;
            margin-bottom: 3rem;
        }

        .btn:hover {
            background-color: #c2410c;
        }

        .contact {
            color: #6b7280;
            font-size: 0.875rem;
            line-height: 1.65;
        }

        .contact a {
            color: #f97316;
            text-decoration: none;
        }

        .contact a:hover {
            color: #fb923c;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">
            <a href="/"><img src="/ricon-logo.svg" alt="Ricon"></a>
        </div>
        <p class="code">500</p>
        <h1>Something Went Wrong</h1>
        <p class="message">
            We hit an unexpected problem on our end. If this happened while you were registering or submitting a payment,
            don't worry. Reach out to us and we'll make sure nothing is lost.
        </p>
        <a href="/" class="btn">Back to Home</a>
        <p class="contact">
            Contact us at
            <a href="mailto:info@ricon.ph">info@ricon.ph</a>
            or message us on
            <a href="https://www.facebook.com/profile.php?id=61585439769463" target="_blank" rel="noopener noreferrer">Facebook</a>
            and we'll sort it out.
        </p>
    </div>
</body>

</html>
