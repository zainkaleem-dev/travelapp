<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        :root { --primary: #002d5b; --panel: #142d5b; --soft: #f5faff; --border: #d5dde7; --text: #0f2e54; --muted: #6d7f95; --accent: #ffc104; --danger: #d84d4d; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial; background: #efeeee; color: var(--text); }
        .auth-band { background: var(--primary); min-height: 100vh; padding: 38px 16px 46px; display: grid; align-items: center; }
        .wrap { max-width: 1120px; margin: 0 auto; display: grid; place-items: center; }
        .card { width: 100%; max-width: 460px; border-radius: 14px; border: 1px solid #0f4f8d; background: var(--panel); box-shadow: 0 16px 40px rgba(0, 0, 0, 0.28); padding: 22px; color: #fff; }
        .top { display: flex; align-items: baseline; justify-content: space-between; gap: 12px; margin-bottom: 8px; }
        h1 { margin: 0; font-size: 31px; letter-spacing: .2px; color: #fff; }
        .muted { color: #c9d6e6; font-size: 15px; }
        label { display: block; margin: 14px 0 6px; font-size: 15px; color: #dce5ef; }
        input[type="text"], input[type="email"], input[type="password"] { width: 100%; padding: 12px 12px; border-radius: 10px; border: 1px solid #245487; background: #0d3765; color: #fff; outline: none; }
        input:focus { border-color: #6ea3db; box-shadow: 0 0 0 3px rgba(110, 163, 219, .22); }
        .row { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-top: 14px; }
        .btn { width: 100%; margin-top: 16px; padding: 12px 14px; border-radius: 10px; border: 1px solid #0b5a9f; background: linear-gradient(180deg, #0f7ea8, #0c5f86); color: #fff; cursor: pointer; font-weight: 700; letter-spacing: .2px; }
        .btn[disabled] { opacity: .55; cursor: not-allowed; }
        .link { color: #17e7ff; text-decoration: none; font-weight: 600; }
        .link:hover { text-decoration: underline; }
        .error { margin-top: 10px; padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(216, 77, 77, .5); background: rgba(216, 77, 77, .15); color: #ffdede; font-size: 13px; }

        @media (max-width: 768px) {
            .auth-band { padding: 24px 12px 32px; }
        }
    </style>
</head>
<body>
    <main class="auth-band">
        <div class="wrap">
            {{ $slot }}
        </div>
    </main>

    @livewireScripts
</body>
</html>
