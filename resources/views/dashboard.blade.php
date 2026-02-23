<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <style>
        body { margin:0; font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial; background: #0b1220; color: rgba(255,255,255,.92); }
        .wrap { min-height: 100vh; display:grid; place-items:center; padding: 28px 14px; }
        .card { width:100%; max-width: 720px; border-radius: 18px; border: 1px solid rgba(255,255,255,.12); background: rgba(255,255,255,.06); padding: 20px; }
        .top { display:flex; align-items:center; justify-content:space-between; gap: 12px; }
        .muted { color: rgba(255,255,255,.65); font-size: 13px; }
        .logout { border: 0; background: transparent; color: #7cfdff; cursor:pointer; padding: 0; font: inherit; }
        .logout:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <div class="top">
                <div>
                    <div style="font-weight: 700;">Dashboard</div>
                    <div class="muted">{{ auth()->user()->email ?? '' }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="logout" type="submit">Logout</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
