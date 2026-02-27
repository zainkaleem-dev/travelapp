{{-- resources/views/components/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Book a Flight' }}</title>

    {{-- Tailwind CDN (replace with Vite + compiled CSS in production) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        serif: ['Lora', 'serif'],
                    },
                    colors: {
                        brand: { 50: '#eff6ff', 100: '#dbeafe', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8' },
                        accent: '#f97316',
                        ek: { red: '#cc0000', darkred: '#a80000' },
                    }
                }
            }
        }
    </script>

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Lora:wght@400;600&display=swap"
        rel="stylesheet">

    @livewireStyles

    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f2f2f2;
        }

        /* ── Tab underline ── */
        .trip-tab {
            position: relative;
            padding: 10px 0;
            margin-right: 24px;
            font-size: 13px;
            color: #555;
            cursor: pointer;
            background: none;
            border: none;
            outline: none;
            transition: color .15s;
            white-space: nowrap;
        }

        .trip-tab::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: #1a56db;
            border-radius: 2px;
            transform: scaleX(0);
            transition: transform .2s;
        }

        .trip-tab.active {
            color: #1a56db;
            font-weight: 600;
        }

        .trip-tab.active::after {
            transform: scaleX(1);
        }

        /* ── Input field ── */
        .field-wrap {
            position: relative;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            padding: 10px 14px 8px;
            background: #fff;
            transition: border-color .15s, box-shadow .15s;
        }

        .field-wrap:focus-within {
            border-color: #1a56db;
            box-shadow: 0 0 0 2px rgba(26, 86, 219, .12);
        }

        .field-label {
            display: block;
            font-size: 10px;
            color: #9ca3af;
            letter-spacing: .02em;
            margin-bottom: 2px;
            text-transform: capitalize;
        }

        .field-input {
            width: 100%;
            font-size: 13px;
            font-weight: 600;
            color: #111827;
            background: transparent;
            border: none;
            outline: none;
            padding: 0;
        }

        .field-input::placeholder {
            color: #9ca3af;
            font-weight: 400;
        }

        /* Date inputs – show placeholder colour until filled */
        .date-input:not([value]):not(:focus) {
            color: #9ca3af;
            font-weight: 400;
        }

        .date-input:focus,
        .date-input[value]:not([value=""]) {
            color: #111827;
            font-weight: 600;
        }

        /* Validation error */
        .field-error {
            display: block;
            font-size: 10px;
            color: #ef4444;
            margin-top: 2px;
        }

        /* clear × button */
        .field-clear {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #d1d5db;
            color: #6b7280;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: none;
            line-height: 1;
            transition: background .15s;
        }

        .field-clear:hover {
            background: #9ca3af;
            color: #fff;
        }

        /* select ▼ */
        .field-select {
            width: 100%;
            font-size: 13px;
            font-weight: 600;
            color: #111827;
            background: transparent;
            border: none;
            outline: none;
            appearance: none;
            -webkit-appearance: none;
            cursor: pointer;
            padding: 0;
        }

        .select-arrow {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #6b7280;
        }

        /* ── Search button ── */
        .btn-search {
            background: #2563eb;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background .15s, transform .1s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            height: 100%;
            min-height: 56px;
            width: 100%;
        }

        .btn-search:hover {
            background: #1d4ed8;
        }

        .btn-search:active {
            transform: scale(.98);
        }

        .btn-search:disabled {
            opacity: .7;
            cursor: not-allowed;
        }

        /* ── Promo section ── */
        .promo-toggle {
            font-size: 12px;
            color: #374151;
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            user-select: none;
            background: none;
            border: none;
        }

        .promo-toggle svg {
            transition: transform .2s;
        }

        .promo-toggle.open svg {
            transform: rotate(180deg);
        }

        .promo-input {
            display: none;
            flex-direction: column;
            gap: 8px;
            margin-top: 12px;
        }

        .promo-input.open {
            display: flex;
        }

        /* ── Multi-city ── */
        .mc-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 32px;
            gap: 8px;
            align-items: center;
        }

        .mc-remove {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 1px solid #e5e7eb;
            background: #fff;
            color: #9ca3af;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all .15s;
            flex-shrink: 0;
        }

        .mc-remove:hover {
            border-color: #ef4444;
            color: #ef4444;
        }

        /* Add flight */
        .btn-add-flight {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: #374151;
            font-weight: 500;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px 0;
            transition: color .15s;
        }

        .btn-add-flight:hover {
            color: #1a56db;
        }

        .btn-add-flight .add-icon {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            border: 1.5px solid #d1d5db;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            line-height: 1;
            color: #6b7280;
            transition: all .15s;
        }

        .btn-add-flight:hover .add-icon {
            border-color: #1a56db;
            color: #1a56db;
        }

        /* login link */
        .login-link {
            font-size: 12px;
            color: #374151;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .login-link a {
            color: #1a56db;
            text-decoration: underline;
            cursor: pointer;
        }

        /* hero */
        .hero-label {
            font-size: 11px;
            letter-spacing: .12em;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .hero-title {
            font-family: 'Lora', serif;
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: 600;
            color: #111827;
            line-height: 1.15;
            margin-bottom: 14px;
        }

        .hero-sub {
            font-size: 14px;
            color: #4b5563;
            max-width: 480px;
            line-height: 1.65;
            margin: 0 auto;
        }

        /* card */
        .search-card {
            background: #fff;
            border-radius: 6px;
            box-shadow: 0 2px 20px rgba(0, 0, 0, .08);
            padding: 24px;
            max-width: 960px;
            margin: 0 auto;
        }

        /* Navbar */
        nav {
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            position: sticky;
            top: 0;
            z-index: 50;
        }
    </style>

</head>

<body>

    {{-- ── Navbar ── --}}
    <nav>
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between h-12">
            <div class="flex items-center gap-5">
                <button
                    class="flex items-center gap-1.5 text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back
                </button>
                <button
                    class="flex items-center gap-1.5 text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors">
                    Next
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
            <div class="flex-1 max-w-sm mx-6">
                <input type="text" placeholder="Search…"
                    class="w-full px-3 py-1.5 text-sm border border-gray-200 rounded-full bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400">
            </div>
            <div class="flex items-center gap-3">
                <div
                    class="w-8 h-8 rounded-full bg-orange-400 flex items-center justify-center text-white text-xs font-bold">
                    U</div>
                <button class="text-sm font-medium text-gray-600 flex items-center gap-1">
                    English
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="px-3 py-1.5 text-sm font-semibold bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="px-3 py-1.5 text-sm font-semibold bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Login / Register
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- ── Step bar ── --}}
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center text-xs text-gray-500 py-1.5 gap-2">
                <a href="/" class="hover:text-blue-600">Home</a>
                <span>/</span>
                <span>Flight Tickets</span>
                <span>/</span>
                <span>Book a Flight</span>
            </div>
            <div class="flex items-center">
                <div class="flex items-center gap-1 px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-t">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z" />
                    </svg>
                    Book Flight
                </div>
                <div class="px-4 py-2 text-gray-400 text-xs">Passenger Details</div>
                <div class="px-4 py-2 text-gray-400 text-xs">Additional Services</div>
                <div class="px-4 py-2 text-gray-400 text-xs">Choice Seat</div>
                <div class="px-4 py-2 text-gray-400 text-xs ml-auto">Payment</div>
            </div>
        </div>
    </div>

    {{-- ── Hero ── --}}
    <div class="py-10 text-center px-4">
        <p class="hero-label">Book</p>
        <h1 class="hero-title">Book a flight</h1>
        <p class="hero-sub">Search for flights and book online. See our routes and schedules, and discover more about
            the experience you can look forward to on board.</p>
    </div>

    {{-- ── Page content (Livewire slot) ── --}}
    <div class="px-4 pb-16">
        {{ $slot }}
    </div>

    @livewireScripts
</body>

</html>
