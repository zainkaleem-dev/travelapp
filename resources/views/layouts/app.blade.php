<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles

        <style>
        :root { --bg: #070a12; --card: rgba(255,255,255,.06); --line: rgba(255,255,255,.12); --text: rgba(255,255,255,.92); --muted: rgba(255,255,255,.65); --accent: #7cfdff; --danger: #ff6b8a; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial; color: var(--text); background:
            radial-gradient(900px 520px at 20% 25%, rgba(124, 253, 255, .18), transparent 60%),
            radial-gradient(900px 520px at 85% 70%, rgba(99, 102, 241, .18), transparent 60%),
            var(--bg);
        }
        .wrap { min-height: 100vh; display: grid; place-items: center; padding: 28px 14px; }
        .card { width: 100%; max-width: 430px; border-radius: 18px; border: 1px solid rgba(90, 165, 255, .45); background: #142D5B; box-shadow: 0 20px 55px rgba(0,0,0,.45); padding: 22px; backdrop-filter: blur(10px); }
        .top { display: flex; align-items: baseline; justify-content: space-between; gap: 12px; margin-bottom: 8px; }
        h1 { margin: 0; font-size: 18px; letter-spacing: .2px; }
        .muted { color: var(--muted); font-size: 13px; }
        label { display: block; margin: 14px 0 6px; font-size: 13px; color: var(--muted); }
        input[type="text"], input[type="email"], input[type="password"] { width: 100%; padding: 12px 12px; border-radius: 12px; border: 1px solid var(--line); background: rgba(0,0,0,.18); color: var(--text); outline: none; }
        input:focus { border-color: rgba(124, 253, 255, .55); box-shadow: 0 0 0 4px rgba(124, 253, 255, .13); }
        .row { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-top: 14px; }
        .btn { width: 100%; margin-top: 16px; padding: 12px 14px; border-radius: 12px; border: 1px solid rgba(124, 253, 255, .35); background: linear-gradient(180deg, rgba(124, 253, 255, .24), rgba(124, 253, 255, .10)); color: var(--text); cursor: pointer; font-weight: 650; letter-spacing: .2px; }
        .btn[disabled] { opacity: .55; cursor: not-allowed; }
        .link { color: var(--accent); text-decoration: none; }
        .link:hover { text-decoration: underline; }
        .error { margin-top: 10px; padding: 10px 12px; border-radius: 12px; border: 1px solid rgba(255, 107, 138, .35); background: rgba(255, 107, 138, .10); color: rgba(255, 235, 239, .95); font-size: 13px; }
    </style>

    </head>
    <body class="wrap">
        {{ $slot }}

        @livewireScripts
    </body>
</html>
