<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'FlightBook' }}</title>

    {{-- Tailwind CSS – replace with Vite build in production --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .auth-shell {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 18px 12px;
        }

        .auth-card {
            width: 100%;
            max-width: 440px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 14px 40px rgba(15, 23, 42, 0.08);
        }

        .auth-card__header {
            padding: 16px 16px 10px;
        }

        .auth-card__title {
            margin: 0;
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
        }

        .auth-card__subtitle {
            margin-top: 6px;
            color: #64748b;
            font-size: 12px;
        }

        .auth-card__body {
            padding: 0 16px 16px;
        }

        .auth-card__footer {
            padding: 12px 16px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            font-size: 11px;
            color: #64748b;
        }

        .input-field {
            width: 100%;
            padding: 9px 12px 9px 36px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 12px;
            color: #1e293b;
            background: #f8fafc;
            transition: all 0.15s ease;
        }

        .input-field::placeholder {
            color: #94a3b8;
        }

        .input-field:focus {
            outline: none;
            border-color: #6366f1;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .login-btn {
            background: linear-gradient(135deg, #2ab4c0 0%, #6366f1 100%);
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.18), transparent);
            transition: left 0.4s ease;
        }

        .login-btn:hover::before {
            left: 100%;
        }

        .login-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.28);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .login-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }


        .flight-dot-sm {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #6366f1;
            flex-shrink: 0;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fu1 {
            animation: fadeUp 0.35s 0.05s ease both;
        }

        .fu2 {
            animation: fadeUp 0.35s 0.10s ease both;
        }

        .fu3 {
            animation: fadeUp 0.35s 0.15s ease both;
        }

        .fu4 {
            animation: fadeUp 0.35s 0.20s ease both;
        }

        .fu5 {
            animation: fadeUp 0.35s 0.25s ease both;
        }

        .fu6 {
            animation: fadeUp 0.35s 0.30s ease both;
        }

        .fu7 {
            animation: fadeUp 0.35s 0.35s ease both;
        }

        .fu8 {
            animation: fadeUp 0.35s 0.40s ease both;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin {
            animation: spin 0.8s linear infinite;
        }

        input[type="checkbox"] {
            accent-color: #6366f1;
        }
    </style>

    @livewireStyles
</head>

<body class="bg-slate-100 min-h-screen flex flex-col text-xs">
    {{ $slot }}
    @livewireScripts
</body>

</html>