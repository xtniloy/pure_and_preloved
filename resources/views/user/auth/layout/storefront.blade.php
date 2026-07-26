<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Account') — Pure &amp; Preloved</title>

    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon/favicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Muli:wght@300;400;500;600;700;800&family=Playfair+Display:wght@500;600;700&display=swap">

    <link rel="stylesheet" href="{{ asset('assets/css/vendor/bootstrap.min.css') }}">

    <style>
        :root {
            --auth-teal: #0f766f;
            --auth-teal-dark: #0c5f59;
            --auth-ink: #1c1f22;
            --auth-muted: #8b9297;
            --auth-border: #e4e8ea;
        }

        body.auth-body {
            font-family: 'Muli', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #eef1f2;
            color: var(--auth-ink);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
            margin: 0;
        }

        .auth-shell {
            width: 100%;
            max-width: 940px;
            background: #ffffff;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 24px 60px -20px rgba(16, 40, 38, 0.28);
            display: flex;
        }

        /* ---- Brand side ---- */
        .auth-aside {
            flex: 1 1 44%;
            background: linear-gradient(150deg, var(--auth-teal) 0%, var(--auth-teal-dark) 100%);
            color: #ffffff;
            padding: 48px 44px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            gap: 34px;
            position: relative;
        }

        .auth-aside::after {
            content: "";
            position: absolute;
            right: -70px;
            bottom: -70px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
        }

        .auth-aside-brand {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: .3px;
        }

        .auth-aside-headline {
            font-family: 'Playfair Display', serif;
            font-size: 30px;
            line-height: 1.25;
            font-weight: 600;
            margin: 0 0 12px;
        }

        .auth-aside-sub {
            font-size: 14.5px;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.82);
            margin: 0;
        }

        .auth-points {
            list-style: none;
            padding: 0;
            margin: auto 0 0;
            display: grid;
            gap: 12px;
            position: relative;
            z-index: 1;
        }

        .auth-points li {
            display: flex;
            align-items: center;
            gap: 11px;
            font-size: 13.5px;
            color: rgba(255, 255, 255, 0.92);
        }

        .auth-points svg {
            flex: none;
            width: 18px;
            height: 18px;
        }

        /* ---- Form side ---- */
        .auth-main {
            flex: 1 1 56%;
            padding: 46px 48px;
            display: flex;
            flex-direction: column;
        }

        .auth-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .auth-logo img {
            height: 40px;
            width: auto;
        }

        .auth-back {
            font-size: 13px;
            font-weight: 600;
            color: var(--auth-muted);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .auth-back:hover { color: var(--auth-teal); }

        .auth-head h1 {
            font-size: 25px;
            font-weight: 800;
            margin: 0 0 6px;
            color: var(--auth-ink);
        }

        .auth-head p {
            font-size: 14px;
            color: var(--auth-muted);
            margin: 0 0 26px;
        }

        .auth-label {
            font-size: 13px;
            font-weight: 700;
            color: var(--auth-ink);
            margin-bottom: 7px;
            display: block;
        }

        .auth-field { position: relative; margin-bottom: 20px; }

        .auth-field .field-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #aab1b6;
            pointer-events: none;
        }

        .auth-input {
            width: 100%;
            height: 50px;
            border: 1px solid var(--auth-border);
            border-radius: 11px;
            padding: 0 16px 0 44px;
            font-size: 14.5px;
            color: var(--auth-ink);
            background: #fafbfb;
            transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
            outline: none;
        }

        .auth-input::placeholder { color: #b4bbc0; }

        .auth-input:focus {
            border-color: var(--auth-teal);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(15, 118, 111, 0.12);
        }

        .auth-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            padding: 6px;
            cursor: pointer;
            color: #aab1b6;
            line-height: 0;
        }

        .auth-toggle:hover { color: var(--auth-teal); }

        .auth-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .auth-check {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13.5px;
            color: #5c6469;
            cursor: pointer;
            user-select: none;
        }

        .auth-check input {
            width: 16px;
            height: 16px;
            accent-color: var(--auth-teal);
            cursor: pointer;
        }

        .auth-link {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--auth-teal);
            text-decoration: none;
        }

        .auth-link:hover { color: var(--auth-teal-dark); text-decoration: underline; }

        .auth-btn {
            width: 100%;
            height: 52px;
            border: none;
            border-radius: 11px;
            background: var(--auth-ink);
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: .3px;
            cursor: pointer;
            transition: background .2s ease, transform .05s ease;
        }

        .auth-btn:hover { background: var(--auth-teal); }
        .auth-btn:active { transform: translateY(1px); }

        .auth-foot {
            margin-top: 26px;
            text-align: center;
            font-size: 14px;
            color: #6b7075;
        }

        .auth-foot a { font-weight: 700; color: var(--auth-teal); text-decoration: none; }
        .auth-foot a:hover { color: var(--auth-teal-dark); text-decoration: underline; }

        /* Validation messages */
        .auth-alert {
            border-radius: 11px;
            padding: 12px 15px;
            font-size: 13.5px;
            margin-bottom: 22px;
            border: 1px solid transparent;
        }
        .auth-alert-danger { background: #fdecea; border-color: #f5c6c2; color: #b02a1c; }
        .auth-alert-success { background: #e9f4f3; border-color: #b9ddd9; color: #0c5f59; }
        .auth-alert-info { background: #eef4fb; border-color: #cfe0f2; color: #2c5a86; }
        .auth-alert ul { margin: 0; padding-left: 18px; }

        /* Single-column variant (no brand aside) — used by confirmation pages */
        .auth-shell--single { max-width: 520px; }
        .auth-shell--single .auth-main { flex-basis: 100%; }

        /* Secondary / outline button */
        .auth-btn-outline {
            background: transparent;
            border: 1px solid var(--auth-border);
            color: var(--auth-ink);
        }
        .auth-btn-outline:hover { background: var(--auth-ink); border-color: var(--auth-ink); color: #fff; }

        /* Success / confirmation content */
        .auth-center { text-align: center; }
        .auth-success-icon {
            width: 74px;
            height: 74px;
            border-radius: 50%;
            background: var(--auth-teal);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 22px;
        }
        .auth-note {
            background: #f6f8f8;
            border: 1px solid var(--auth-border);
            border-radius: 11px;
            padding: 14px 16px;
            font-size: 13px;
            color: #5c6469;
            margin-bottom: 22px;
        }
        .auth-countdown { font-weight: 800; color: var(--auth-teal); }
        .auth-help {
            font-size: 12.5px;
            color: var(--auth-muted);
            margin: -8px 0 20px;
        }
        .auth-btn.auth-btn-inline { width: auto; padding: 0 26px; height: 46px; }

        @media (max-width: 860px) {
            .auth-aside { display: none; }
            .auth-shell { max-width: 460px; }
            .auth-main { padding: 40px 32px; }
        }

        @media (max-width: 400px) {
            .auth-main { padding: 32px 22px; }
        }
    </style>
    @stack('styles')
</head>
<body class="auth-body">

    @yield('content')

    <script>
        // Shared password show/hide toggle for any [data-toggle-password] button
        document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var input = document.getElementById(btn.getAttribute('data-toggle-password'));
                if (!input) return;
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
