{{-- resources/views/components/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

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
                        gray: { 50: '#c8cfd6', 500: '#6b6d80' },
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
        /* Remove NProgress busy cursor and hide the progress bar */
        html.nprogress-busy {
            cursor: default !important;
        }

        #nprogress {
            display: none !important;
        }

        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f2f2f2;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ── Trip type tabs ── */
        .trip-tabs {
            display: inline-flex;
            background: #e5e7eb;
            padding: 4px;
            border-radius: 8px;
            margin-bottom: 24px;
        }

        .trip-tab {
            padding: 8px 20px;
            font-size: 13px;
            font-weight: 600;
            color: black;
            cursor: pointer;
            border: none;
            background: transparent;
            border-radius: 6px;
            transition: all 0.2s ease;
            white-space: nowrap;
            outline: none;
        }

        .trip-tab:hover {
            color: #4b5563;
        }

        .trip-tab.active {
            background: #fff;
            color: #2ab4c0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
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
            border-color: #2ab4c0;
            box-shadow: 0 0 0 2px rgba(42, 180, 192, .12);
        }

        .field-label {
            display: block;
            font-size: 10px;
            color: black;
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
	            color: black;
	            font-weight: 400;
	        }

	        /* Right-side icons inside inputs (From/To) */
	        .field-wrap.has-icon-right {
	            padding-right: 46px;
	        }

	        .field-wrap.has-icon-right.has-clear {
	            padding-right: 76px;
	        }

	        .field-icon {
	            position: absolute;
	            top: 50%;
	            transform: translateY(-50%);
	            display: flex;
	            align-items: center;
	            justify-content: center;
	            width: 22px;
	            height: 22px;
	            pointer-events: none;
	        }

	        .field-icon.right {
	            right: 12px;
	        }

	        .field-icon svg {
	            width: 20px;
	            height: 20px;
	        }

        /* Date inputs – show placeholder colour until filled */
        .date-input:not(.has-val):not(:focus) {
            color: #9ca3af;
            font-weight: 400;
        }

        .date-input:focus,
        .date-input.has-val {
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
	            width: 22px;
	            height: 22px;
	            border-radius: 50%;
	            background: #d1d5db;
	            color: #6b7280;
	            font-size: 0;
	            cursor: pointer;
            border: none;
            transition: background .15s;
        }

	        .field-clear::before,
	        .field-clear::after {
	            content: '';
	            position: absolute;
	            top: 50%;
	            left: 50%;
	            width: 12px;
	            height: 2px;
	            background: currentColor;
	            border-radius: 999px;
	            transform: translate(-50%, -50%) rotate(45deg);
	        }

        .field-clear::after {
            transform: translate(-50%, -50%) rotate(-45deg);
        }

	        .field-clear:hover {
	            background: #9ca3af;
	            color: #fff;
	        }

	        .field-wrap.has-icon-right.has-clear .field-clear {
	            right: 44px;
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
            background: #2ab4c0;
            color: #fff;
            font-weight: 600;
            font-size: 13px;
            border: none;
            border-radius: 999px;
            cursor: pointer;
            transition: background .15s, transform .1s, box-shadow .15s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            height: 34px;
            padding: 0 18px;
            white-space: nowrap;
        }

        .btn-search:hover {
            background: #239ea9;
            box-shadow: 0 4px 10px rgba(35, 158, 169, .45);
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

        @media (min-width: 768px) {
            .mc-row {
                column-gap: 48px;
            }
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
            color: #2ab4c0;
        }

	        .btn-add-flight .add-icon {
	            width: 26px;
	            height: 26px;
	            border-radius: 50%;
	            border: 1.5px solid #d1d5db;
	            display: inline-block;
	            position: relative;
	            color: #6b7280;
	            transition: all .15s;
	        }

	        .btn-add-flight .add-icon::before {
	            content: '';
	            position: absolute;
	            top: 50%;
	            left: 50%;
	            width: 10px;
	            height: 2px;
	            background: currentColor;
	            border-radius: 999px;
	            transform: translate(-50%, -50%);
	        }

	        .btn-add-flight .add-icon::after {
	            content: '';
	            position: absolute;
	            top: 50%;
	            left: 50%;
	            width: 2px;
	            height: 10px;
	            background: currentColor;
	            border-radius: 999px;
	            transform: translate(-50%, -50%);
	        }

        .btn-add-flight:hover .add-icon {
            border-color: #2ab4c0;
            color: #2ab4c0;
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
            color: #2ab4c0;
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

        /* Keep Return panel second-row fields visually equal height */
        .return-second-row > .field-wrap {
            min-height: 60px;
        }

        .oneway-second-row > .field-wrap {
            min-height: 60px;
        }

        .multi-second-row > .field-wrap {
            min-height: 60px;
        }

        /* Navbar */
        nav {
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        /* Remove number input spinners */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        /* Hide scrollbars utility */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

	        .no-scrollbar {
	            -ms-overflow-style: none;
	            /* IE and Edge */
	            scrollbar-width: none;
	            /* Firefox */
	        }

	        /* Hide Livewire navigate top progress bar (one-time toggle via html[data-hide-nprogress]) */
	        html[data-hide-nprogress="1"] #nprogress {
	            display: none !important;
	        }
	    </style>

</head>

<body x-data="{ searchOpen: false }">

    {{-- ── Navbar ── --}}
    <nav class="relative z-50 bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 py-3 flex items-center justify-between gap-4">
            <a href="{{ route('flights.search') }}" class="text-sm font-semibold text-gray-800 whitespace-nowrap">
                Corporate Company Logo
            </a>

            <div class="flex items-center gap-3">
                <div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false" @click.outside="open = false">
                    <button type="button" @click="open = !open" :aria-expanded="open.toString()"
                        class="flex items-center gap-2 rounded-full border border-gray-200 bg-white px-3 py-1.5 hover:border-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-200 focus:ring-offset-0">
                        <span class="text-xs font-semibold text-gray-700">
                            {{ app()->getLocale() === 'ar' ? 'AR' : 'EN' }}
                        </span>
                        <svg class="w-3.5 h-3.5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-cloak x-show="open" x-transition.origin.top.right
                        class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-xl shadow-lg z-[9999] overflow-hidden">
                        <a href="{{ route('lang.switch', 'en') }}"
                            class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 {{ app()->getLocale() === 'en' ? 'font-semibold text-[#2ab4c0]' : '' }}">
                            English
                        </a>
                        <a href="{{ route('lang.switch', 'ar') }}"
                            class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 {{ app()->getLocale() === 'ar' ? 'font-semibold text-[#2ab4c0]' : '' }}">
                            العربية
                        </a>
                    </div>
                </div>

                @php
                    $navCurrency = session('currency', config('currencies.default', 'USD'));
                    $navCurrencyOptions = (array) config('currencies.options', []);
                @endphp
                <div class="relative" x-data="{ open: false, q: '' }" @keydown.escape.window="open = false" @click.outside="open = false">
                    <button type="button" @click="open = !open" :aria-expanded="open.toString()"
                        class="flex items-center gap-2 rounded-full border border-gray-200 bg-white px-3 py-1.5 hover:border-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-200 focus:ring-offset-0">
                        <span class="text-xs font-semibold text-gray-700">{{ $navCurrency }}</span>
                        <svg class="w-3.5 h-3.5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-cloak x-show="open" x-transition.origin.top.right
                        class="absolute right-0 mt-2 w-72 bg-white border border-gray-200 rounded-xl shadow-lg z-[9999] overflow-hidden">
                        <div class="p-3 border-b border-gray-100">
                            <input type="text" x-model="q" placeholder="Search currency..."
                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-200"
                                @keydown.enter.prevent="
                                    const code = (q || '').trim().toUpperCase();
                                    if (/^[A-Z]{3}$/.test(code)) window.location.href = '{{ url('/currency') }}/' + encodeURIComponent(code);
                                ">
                            <p class="mt-1 text-[11px] text-gray-500">Type 3-letter code and press Enter.</p>
                        </div>
                        <div class="max-h-72 overflow-auto">
                            @foreach($navCurrencyOptions as $code => $label)
                                <a href="{{ url('/currency') }}/{{ $code }}"
                                    x-show="q === '' || '{{ $code }} {{ $label }}'.toLowerCase().includes(q.toLowerCase())"
                                    class="block px-4 py-2.5 text-sm hover:bg-gray-50 {{ $navCurrency === $code ? 'font-semibold text-[#2ab4c0]' : 'text-gray-700' }}">
                                    <span class="font-semibold">{{ $code }}</span>
                                    <span class="text-gray-500">— {{ $label }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                @auth
                    <div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false"
                        @click.outside="open = false">
                        <button type="button" @click="open = !open" :aria-expanded="open.toString()"
                            class="flex items-center gap-2 rounded-full border border-gray-200 bg-white pl-1.5 pr-2 py-1 hover:border-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-200 focus:ring-offset-0">
                            <div
                                class="w-8 h-8 rounded-full bg-orange-400 flex items-center justify-center text-white text-xs font-bold">
                                {{ strtoupper(substr(auth()->user()->display_name ?: (auth()->user()->email ?? 'U'), 0, 1)) }}
                            </div>
                            <svg class="w-3.5 h-3.5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-cloak x-show="open" x-transition.origin.top.right
                            class="absolute right-0 mt-2 w-60 bg-white border border-gray-200 rounded-xl shadow-lg z-[9999] overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-800 truncate">
                                    {{ auth()->user()->display_name ?: 'User' }}
                                </p>
                                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                            </div>

                            <a href="{{ route('profile') }}"
                                class="w-full px-4 py-2.5 text-sm text-left text-gray-700 hover:bg-gray-50 flex items-center gap-2 transition-colors">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5.121 17.804A9 9 0 1118.88 17.8M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Profile
                            </a>

                            <a href="{{ route('settings') }}" 
                                class="w-full px-4 py-2.5 text-sm text-left text-gray-700 hover:bg-gray-50 flex items-center gap-2 transition-colors"> 
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15.75a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19.5 12a7.5 7.5 0 0 1-.2 1.7l2 1.6-2 3.4-2.4-1a7.6 7.6 0 0 1-2.9 1.7L13 22H11l-.8-2.6a7.6 7.6 0 0 1-2.9-1.7l-2.4 1-2-3.4 2-1.6A7.5 7.5 0 0 1 4.5 12c0-.6.07-1.15.2-1.7l-2-1.6 2-3.4 2.4 1a7.6 7.6 0 0 1 2.9-1.7L11 2h2l.8 2.6a7.6 7.6 0 0 1 2.9 1.7l2.4-1 2 3.4-2 1.6c.13.55.2 1.1.2 1.7z" />
                                </svg>
                                Settings 
                            </a> 
 
                            @if (auth()->check() && !(bool) (auth()->user()->is_super_admin ?? false) && (int) (auth()->user()->company_id ?? 0) > 0 && method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('company_admin'))
                                <a href="{{ route('company.companies.index') }}" 
                                    class="w-full px-4 py-2.5 text-sm text-left text-gray-700 hover:bg-gray-50 flex items-center gap-2 transition-colors"> 
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"> 
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M3 7h18M3 12h18M3 17h18" /> 
                                    </svg> 
                                    Companies 
                                </a> 
                            @endif
 
                            <a href="{{ route('corporate.settings') }}" 
                                class="w-full px-4 py-2.5 text-sm text-left text-gray-700 hover:bg-gray-50 flex items-center gap-2 transition-colors"> 
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 21h18M4 21V7a2 2 0 0 1 2-2h3v16M10 21V3h8a2 2 0 0 1 2 2v16" />
                                </svg>
                                Corporate Settings
                            </a>

                            <a href="{{ route('tmc.settings') }}"
                                class="w-full px-4 py-2.5 text-sm text-left text-gray-700 hover:bg-gray-50 flex items-center gap-2 transition-colors">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7h8M8 11h8M8 15h8M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                TMC Settings
                            </a>

                            <a href="{{ route('superadmin.settings') }}"
                                class="w-full px-4 py-2.5 text-sm text-left text-gray-700 hover:bg-gray-50 flex items-center gap-2 transition-colors">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 3l7 4v6c0 5-3.5 9-7 10-3.5-1-7-5-7-10V7l7-4z" />
                                </svg>
                                Super Admin Settings
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full px-4 py-2.5 text-sm text-left text-red-600 hover:bg-red-50 flex items-center gap-2 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                        class="px-2.5 py-1.5 sm:px-3 text-xs sm:text-sm font-semibold bg-[#2ab4c0] text-white rounded-md hover:bg-[#239ea9] transition-colors whitespace-nowrap">
                        Login / Register
                    </a>
                @endauth
            </div>
        </div>

        @php 
            $hideMainNavForSuperAdmin = auth()->check() 
                && (bool) (auth()->user()->is_super_admin ?? false) 
                && request()->is('super-admin*'); 
 
            $hideMainNavForCompanyAdmin = auth()->check()
                && !(bool) (auth()->user()->is_super_admin ?? false)
                && request()->is('company*');
 
          @endphp 
 
        @unless ($hideMainNavForSuperAdmin || $hideMainNavForCompanyAdmin) 
            <div class="max-w-7xl mx-auto px-3 sm:px-4"> 
                <div class="flex items-center gap-0 overflow-x-auto no-scrollbar text-xs font-semibold"> 
                <a href="{{ route('flights.search') }}" 
                    class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 {{ request()->routeIs('flights.search') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:text-gray-900' }} rounded-t transition-colors whitespace-nowrap"> 
                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/>
                    </svg>
                    Book Trip
                </a>
                <a href="#"
                    class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 {{ request()->routeIs('flights.list') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:text-gray-900' }} rounded-t transition-colors whitespace-nowrap">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 .621-.504 1.125-1.125 1.125H4.875c-.621 0-1.125-.504-1.125-1.125v-4.25m16.5 0a2.25 2.25 0 00-2.25-2.25H18.75V8.25A2.25 2.25 0 0016.5 6H7.5A2.25 2.25 0 005.25 8.25V11.9h-1.5a2.25 2.25 0 00-2.25 2.25m16.5 0a2.25 2.25 0 01-2.25 2.25H5.25a2.25 2.25 0 01-2.25-2.25m13.5-3.75V11.9m-9 0V8.25"/>
                    </svg>
                    My Trip
                </a>
                <a href="#"
                    class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 {{ request()->routeIs('dashboard') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:text-gray-900' }} rounded-t transition-colors whitespace-nowrap">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-2.25v2.25m3-2.25v2.25m3-2.25v2.25m-9-4.5v2.25m3-2.25v2.25m3-2.25v2.25m3-2.25v2.25m-9-4.5v2.25m3-2.25v2.25m3-2.25v2.25m3-2.25v2.25M3.75 20.25h16.5A2.25 2.25 0 0022.5 18V6a2.25 2.25 0 00-2.25-2.25H3.75A2.25 2.25 0 001.5 6v12a2.25 2.25 0 002.25 2.25z"/>
                    </svg>
                    Dashboard
                </a>
                <a href="#"
                    class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 {{ request()->routeIs('travel.hub') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600 hover:text-gray-900' }} rounded-t transition-colors whitespace-nowrap">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6.75V15m-10.5 2.25l.75-12 4.5-2.25 4.5 2.25 4.5-2.25 .75 12-4.5 2.25-4.5-2.25-4.5 2.25z"/>
                    </svg>
                    Travel hub
                </a>
                </div>
            </div>
        @endunless
    </nav>

    {{-- ── Step bar ── --}}
    <div class="z-40 bg-transparent">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 mt-12 pt-4 pb-12 sm:pb-16">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-visible px-2 sm:px-3">
           {{--  <div class="flex items-center overflow-x-auto no-scrollbar gap-0 min-w-0"
                style="-webkit-overflow-scrolling: touch;">
                <div
                    class="flex items-center gap-1 px-3 py-2 sm:px-4 flex-shrink-0 {{ request()->is('flights-search') ? 'bg-[#2ab4c0] text-white font-semibold rounded-t' : 'text-gray-600' }} text-xs whitespace-nowrap">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z" />
                    </svg>
                    <span class="hidden sm:inline">Search Flight</span>
                    <span class="sm:hidden">Search</span>
                </div>
                <div
                    class="px-3 py-2 sm:px-4 flex-shrink-0 {{ request()->is('flights-list') ? 'bg-[#2ab4c0] text-white font-semibold rounded-t' : 'text-gray-600' }} text-xs whitespace-nowrap">
                    Flights
                </div>
                <div
                    class="px-3 py-2 sm:px-4 flex-shrink-0 {{ request()->is('additional-services') ? 'bg-[#2ab4c0] text-white font-semibold rounded-t' : 'text-gray-600' }} text-xs whitespace-nowrap">
                    <span class="hidden sm:inline">Additional Services</span>
                    <span class="sm:hidden">Services</span>
                </div>
                <div
                    class="px-3 py-2 sm:px-4 flex-shrink-0 {{ request()->is('seating') ? 'bg-[#2ab4c0] text-white font-semibold rounded-t' : 'text-gray-600' }} text-xs whitespace-nowrap">
                    Choice Seat
                </div>
                <div
                    class="px-3 py-2 sm:px-4 flex-shrink-0 {{ request()->is('passenger-details') ? 'bg-[#2ab4c0] text-white font-semibold rounded-t' : 'text-gray-600' }} text-xs whitespace-nowrap">
                    <span class="hidden sm:inline">Passenger Details</span>
                    <span class="sm:hidden">Details</span>
                </div> --}}

                {{-- Frontend-only service step bar --}}
                <div class="flex items-center overflow-x-auto no-scrollbar gap-0 min-w-0"
                    style="-webkit-overflow-scrolling: touch;">
                    @php 
                        $isSuperAdminArea = auth()->check() 
                            && (bool) (auth()->user()->is_super_admin ?? false) 
                            && request()->is('super-admin*'); 
 
                        $isCompanyAdminArea = auth()->check()
                            && !(bool) (auth()->user()->is_super_admin ?? false)
                            && request()->is('company*');
 
                      @endphp 
 
                    @if ($isSuperAdminArea) 

                        <a href="{{ route('superadmin.companies.index') }}"
                            class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 {{ request()->routeIs('superadmin.companies.index') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600' }} rounded-t text-xs whitespace-nowrap">
                            <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                            </svg>
                            Companies
                        </a>
                        <a href="{{ route('superadmin.branches') }}"
                            class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 {{ request()->routeIs('superadmin.branches') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600' }} rounded-t text-xs whitespace-nowrap">
                            <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                            </svg>
                            Branches
                        </a>
                        <a href="{{ route('superadmin.users') }}"
                            class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 {{ request()->routeIs('superadmin.users') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600' }} rounded-t text-xs whitespace-nowrap">
                            <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                            Users
                        </a>
                        <a href="{{ route('superadmin.roles') }}"
                            class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 {{ request()->routeIs('superadmin.roles') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600' }} rounded-t text-xs whitespace-nowrap">
                            <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                            Roles
                        </a>

                        @if (request()->routeIs('superadmin.companies.index') 
                            || request()->routeIs('superadmin.users') 
                            || request()->routeIs('superadmin.roles')) 
                            @livewire('admin.super-admin-company-switcher') 
                        @endif 
                        <a href="{{ route('company.companies.index') }}"
                            class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 {{ request()->routeIs('company.companies.index') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600' }} rounded-t text-xs whitespace-nowrap">
                            <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                            </svg>
                            Company
                        </a>
                        <a href="{{ route('subcompany.index') }}"
                            class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 {{ request()->routeIs('subcompany.index') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600' }} rounded-t text-xs whitespace-nowrap">
                            <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 7.5h16.5M3.75 12h16.5M3.75 16.5h10" />
                            </svg>
                            Sub Company
                        </a>
                    @else 
                    <a href="{{ route('flights.search') }}" 
                        class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 {{ (request()->is('flights-search') || request()->is('flights-list') || request()->is('additional-services') || request()->is('passenger-details')) ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600' }} rounded-t text-xs whitespace-nowrap"> 
                        <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                        </svg>
                        Flight
                    </a>
                    <a href="{{ route('hotels') }}"
                        class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 {{ request()->is('hotels') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600' }} rounded-t text-xs whitespace-nowrap">
                        <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Hotel
                    </a>
                    <a href="{{ route('cars') }}"
                        class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 {{ request()->is('cars') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600' }} rounded-t text-xs whitespace-nowrap">
                        <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h9M9 6.75h6m-7.5 0V5.25A2.25 2.25 0 0111.25 3h.75a2.25 2.25 0 012.25 2.25v1.5H18a2.25 2.25 0 012.25 2.25v7.5A2.25 2.25 0 0118 16.5h-.75M9 6.75H6.75A2.25 2.25 0 004.5 9v7.5A2.25 2.25 0 006.75 18.75H9"/>
                        </svg>
                        Car
                    </a>
                    <a href="{{ route('concierge') }}"
                        class="inline-flex items-center gap-1.5 px-3 py-2 sm:px-4 flex-shrink-0 {{ request()->is('concierge') ? 'bg-[#2ab4c0] text-white font-semibold' : 'text-gray-600' }} rounded-t text-xs whitespace-nowrap">
                        <svg class="w-3.5 h-3.5 flex-shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        Concierge
                    </a>
                    @endif 

                @php
                    $isSuperAdminArea = auth()->check()
                        && (bool) (auth()->user()->is_super_admin ?? false)
                        && request()->is('super-admin*');
                @endphp

                {{-- Right-side search toggle (frontend only) --}}
                @unless ($isSuperAdminArea || $isCompanyAdminArea)  
                    <button type="button" @click="searchOpen = !searchOpen"  
                        class="ml-auto my-1 flex items-center justify-center w-8 h-8 rounded-full border border-gray-200 bg-white text-gray-500 hover:text-[#2ab4c0] hover:border-[#2ab4c0]/60 shadow-sm flex-shrink-0">  
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"> 
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z" /> 
                        </svg> 
                    </button> 
                @endunless 
            </div>

            <div class="h-px bg-gray-100"></div>
            <div class="p-3 sm:p-4">
                <div class="flex flex-col">

    @if (in_array(request()->route()?->getName(), ['flights.list', 'additional.services', 'seating', 'passenger.details'], true))
    {{-- Form Wizard (frontend services) --}}
    @php
        $routeName = request()->route()?->getName();
        $activeStep = match ($routeName) {
            'flights.list' => 1,
            'additional.services' => 2,
            'seating' => 3,
            'passenger.details' => 4,
            default => 1,
        };

        // 4-step connector: fill up to active circle.
        // Steps are rendered in 4 equal parts (w-1/4), so step 1 circle center ~ 12.5%.
        $connectorPercent = match ($activeStep) {
            1 => 12.5,
            2 => 37.5,
            3 => 62.5,
            4 => 100,
            default => 12.5,
        };
    @endphp

    <div class="px-3 sm:px-4 lg:px-6 pt-4 pb-2 -order-1">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <div class="flex items-center justify-between gap-3 mb-3">
                <div>
                    <div class="text-sm font-bold text-gray-800">Service Wizard</div>
                    <div class="text-xs mt-0.5">
                        <span class="{{ $activeStep === 1 ? 'font-bold text-[#000000]' : 'font-normal text-gray-500' }}">Flight List</span>
                        <span class="text-gray-500"> / </span>
                        <span class="{{ $activeStep === 2 ? 'font-bold text-[#000000]' : 'font-normal text-gray-500' }}">Additional Services</span>
                        <span class="text-gray-500"> / </span>
                        <span class="{{ $activeStep === 3 ? 'font-bold text-[#000000]' : 'font-normal text-gray-500' }}">Seating</span>
                        <span class="text-gray-500"> / </span>
                        <span class="{{ $activeStep === 4 ? 'font-bold text-[#000000]' : 'font-normal text-gray-500' }}">Passenger Details</span>
                    </div>
                </div>
            </div>

            <div class="relative">
                {{-- connector line --}}
                <div class="absolute left-0 right-0 top-5 h-[2px] bg-gray-200"></div>
                <div class="absolute left-0 top-5 h-[2px] bg-[#2ab4c0]" style="width: {{ $connectorPercent }}%"></div>

                <div class="flex items-start justify-between">
                    {{-- 1. Flight List --}}
                    <div class="flex flex-col items-center w-1/4">
                        <a href="{{ route('flights.list') }}"
                            class="w-10 h-10 rounded-full border-2 flex items-center justify-center z-10 transition-colors
                            {{ $activeStep === 1 ? 'bg-[#2ab4c0] border-[#2ab4c0] text-white' : 'bg-white border-gray-200 text-gray-400' }}">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 6h16" />
                                <path d="M4 12h16" />
                                <path d="M4 18h10" />
                            </svg>
                        </a>
                        <div class="mt-2 text-[11px] {{ $activeStep === 1 ? 'font-bold text-[#2ab4c0]' : 'font-normal text-gray-600' }}">
                            Flight List
                        </div>
                    </div>

                    {{-- 2. Additional Services --}}
                    <div class="flex flex-col items-center w-1/4">
                        <div
                            class="w-10 h-10 rounded-full border-2 flex items-center justify-center z-10
                            {{ $activeStep === 2 ? 'bg-[#2ab4c0] border-[#2ab4c0] text-white' : 'bg-white border-gray-200 text-gray-400' }}">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 6H9l-2 4H20l-2 4H7l-2 4" />
                            </svg>
                        </div>
                        <div class="mt-2 text-[11px] {{ $activeStep === 2 ? 'font-bold text-[#2ab4c0]' : 'font-normal text-gray-600' }}">Additional Services</div>
                    </div>

                    {{-- 3. Seating --}}
                    <div class="flex flex-col items-center w-1/4">
                        <div
                            class="w-10 h-10 rounded-full border-2 flex items-center justify-center z-10
                            {{ $activeStep === 3 ? 'bg-[#2ab4c0] border-[#2ab4c0] text-white' : 'bg-white border-gray-200 text-gray-400' }}">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h10M7 11h10M7 15h10M7 19h10" />
                            </svg>
                        </div>
                        <div class="mt-2 text-[11px] {{ $activeStep === 3 ? 'font-bold text-[#2ab4c0]' : 'font-normal text-gray-600' }}">Seating</div>
                    </div>

                    {{-- 4. Passenger Details --}}
                    <div class="flex flex-col items-center w-1/4">
                        <div
                            class="w-10 h-10 rounded-full border-2 flex items-center justify-center z-10
                            {{ $activeStep === 4 ? 'bg-[#2ab4c0] border-[#2ab4c0] text-white' : 'bg-white border-gray-200 text-gray-400' }}">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                        </div>
                        <div class="mt-2 text-[11px] {{ $activeStep === 4 ? 'font-bold text-[#2ab4c0]' : 'font-normal text-gray-600' }}">Passenger Details</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

                {{-- Inline quick search panel (frontend only) --}}
                @unless ($isSuperAdminArea)
                    <div x-cloak x-show="searchOpen" x-transition.opacity x-transition.duration.200ms class="mt-4">
                        @livewire('quick-search')
                    </div>
                @endunless

                {{ $slot }}
            </div>
        </div>
        </div>
    </div>
    </div>

		    <script>
		        // Close on ESC
		        window.addEventListener('keydown', (e) => {
		            if (e.key === 'Escape') {
		                if (document.body.__x && document.body.__x.$data && typeof document.body.__x.$data.searchOpen !== 'undefined') {
		                    document.body.__x.$data.searchOpen = false;
		                }
		            }
		        });

	        // If we hid the progress bar for a single navigation, restore default for the next one.
	        document.addEventListener('livewire:navigated', () => {
	            document.documentElement.removeAttribute('data-hide-nprogress');
	        });
		        document.addEventListener('alpine:navigated', () => {
		            document.documentElement.removeAttribute('data-hide-nprogress');
		        });

		        // Shared helpers (used by FlightSearch + QuickSearch)
		        (() => {
		            if (!window.paxDropdown) {
		                window.paxDropdown = function (opts) {
		                    const toInt = (v) => {
		                        const n = parseInt(v, 10);
		                        return Number.isFinite(n) ? n : 0;
		                    };

		                    return {
		                        open: false,
		                        adults: opts?.adults ?? 1,
		                        children: opts?.children ?? 0,
		                        infants: opts?.infants ?? 0,

		                        get total() {
		                            return toInt(this.adults) + toInt(this.children) + toInt(this.infants);
		                        },

		                        get summary() {
		                            const parts = [];
		                            const a = toInt(this.adults);
		                            const c = toInt(this.children);
		                            const i = toInt(this.infants);

		                            if (a > 0) parts.push(`${a} ${a === 1 ? 'Adult' : 'Adults'}`);
		                            if (c > 0) parts.push(`${c} ${c === 1 ? 'Child' : 'Children'}`);
		                            if (i > 0) parts.push(`${i} ${i === 1 ? 'Infant' : 'Infants'}`);

		                            return parts.length ? parts.join(', ') : '0 Passengers';
		                        },

		                        inc(type) {
		                            const a = toInt(this.adults);
		                            const c = toInt(this.children);
		                            const i = toInt(this.infants);
		                            const total = a + c + i;

		                            if (total >= 9) return;
		                            if (type === 'adult') this.adults = a + 1;
		                            if (type === 'child') this.children = c + 1;
		                            if (type === 'infant' && i < a) this.infants = i + 1;
		                        },

		                        dec(type) {
		                            const a = toInt(this.adults);
		                            const c = toInt(this.children);
		                            const i = toInt(this.infants);

		                            if (type === 'adult') {
		                                if (a <= 1) return;
		                                const nextAdults = a - 1;
		                                this.adults = nextAdults;
		                                if (toInt(this.infants) > nextAdults) this.infants = nextAdults;
		                                return;
		                            }

		                            if (type === 'child') {
		                                if (c <= 0) return;
		                                this.children = c - 1;
		                                return;
		                            }

		                            if (type === 'infant') {
		                                if (i <= 0) return;
		                                this.infants = i - 1;
		                            }
		                        },
		                    };
		                };
		            }

		            if (!window.dateRangePicker) {
		                window.dateRangePicker = function (opts) {
		                    const pad = (n) => String(n).padStart(2, '0');
		                    const toIso = (d) => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
		                    const parseIso = (iso) => {
		                        if (!iso) return null;
		                        const [y, m, d] = iso.split('-').map(Number);
		                        return new Date(y, (m || 1) - 1, d || 1);
		                    };
		                    const fmt = (iso) => {
		                        const d = parseIso(iso);
		                        if (!d) return '';
		                        return `${pad(d.getMonth() + 1)}/${pad(d.getDate())}/${d.getFullYear()}`;
		                    };
		                    const startOfDay = (d) => new Date(d.getFullYear(), d.getMonth(), d.getDate());

		                    const wireDepKey = opts?.wireDepKey || 'returnDepDate';
		                    const wireRetKey = opts?.wireRetKey || 'returnRetDate';

		                    return {
		                        open: false,
		                        active: 'dep',
		                        dep: opts?.dep ? fmt(opts.dep) : '',
		                        ret: opts?.ret ? fmt(opts.ret) : '',
		                        depIso: opts?.dep || '',
		                        retIso: opts?.ret || '',
		                        base: null,
		                        months: [],
		                        minIso: toIso(startOfDay(new Date())),
		                        hoveredIso: null,

		                        init() {
		                            const baseIso = this.depIso || this.minIso;
		                            const baseDate = parseIso(baseIso) || new Date();
		                            this.base = new Date(baseDate.getFullYear(), baseDate.getMonth(), 1);
		                            this.months = [];
		                            this.refreshMonths();
		                        },

		                        prevMonth() {
		                            const today = new Date();
		                            const currentMonth = new Date(today.getFullYear(), today.getMonth(), 1);
		                            const prev = new Date(this.base.getFullYear(), this.base.getMonth() - 1, 1);
		                            if (prev >= currentMonth) {
		                                this.base = prev;
		                                this.refreshMonths();
		                            }
		                        },

		                        nextMonth() {
		                            this.base = new Date(this.base.getFullYear(), this.base.getMonth() + 1, 1);
		                            this.refreshMonths();
		                        },

		                        refreshMonths() {
		                            const m1 = this.buildMonth(this.base.getFullYear(), this.base.getMonth());
		                            const m2Date = new Date(this.base.getFullYear(), this.base.getMonth() + 1, 1);
		                            const m2 = this.buildMonth(m2Date.getFullYear(), m2Date.getMonth());
		                            this.months = [m1, m2];
		                        },

		                        buildMonth(year, monthIndex) {
		                            const monthStart = new Date(year, monthIndex, 1);
		                            const monthEnd = new Date(year, monthIndex + 1, 0);
		                            const daysInMonth = monthEnd.getDate();
		                            const jsDow = monthStart.getDay(); // 0=Sun
		                            const offset = (jsDow + 6) % 7; // 0=Mon

		                            const title = monthStart.toLocaleString(undefined, { month: 'long', year: 'numeric' });
		                            const cells = [];

		                            for (let i = 0; i < offset; i++) {
		                                cells.push({ key: `${year}-${monthIndex}-blank-${i}`, day: null, iso: null, disabled: true });
		                            }

		                            for (let day = 1; day <= daysInMonth; day++) {
		                                const d = new Date(year, monthIndex, day);
		                                const iso = toIso(d);
		                                cells.push({
		                                    key: iso,
		                                    day,
		                                    iso,
		                                    disabled: iso < this.minIso,
		                                });
		                            }

		                            while (cells.length < 42) {
		                                cells.push({ key: `${year}-${monthIndex}-tail-${cells.length}`, day: null, iso: null, disabled: true });
		                            }

		                            return { key: `${year}-${monthIndex}`, title, cells };
		                        },

		                        pick(iso) {
		                            if (!iso || iso < this.minIso) return;

		                            if (this.active === 'dep' || (this.depIso && this.retIso)) {
		                                this.depIso = iso;
		                                this.retIso = '';
		                                this.active = 'ret';
		                            } else {
		                                if (iso < this.depIso) {
		                                    this.depIso = iso;
		                                    this.retIso = '';
		                                    this.active = 'ret';
		                                } else {
		                                    this.retIso = iso;
		                                    this.active = 'dep';
		                                }
		                            }

		                            this.dep = fmt(this.depIso);
		                            this.ret = fmt(this.retIso);
		                        },

		                        apply() {
		                            if (!this.$wire) return;
		                            this.$wire.$set(wireDepKey, this.depIso);
		                            this.$wire.$set(wireRetKey, this.retIso);
		                        },

		                        dayClass(cell) {
		                            if (!cell.day) return 'text-transparent';
		                            if (cell.disabled) return 'text-gray-300 cursor-not-allowed';

		                            const iso = cell.iso;
		                            const isDep = this.depIso && iso === this.depIso;
		                            const isRet = this.retIso && iso === this.retIso;
		                            const inRange = this.depIso && this.retIso && iso > this.depIso && iso < this.retIso;
		                            const isHoverRange = this.active === 'ret' && this.depIso && !this.retIso && this.hoveredIso && iso > this.depIso && iso <= this.hoveredIso;

		                            if (isDep || isRet) return 'bg-[#2ab4c0] text-white';
		                            if (inRange || isHoverRange) return 'bg-[#2ab4c0]/10 text-gray-900';
		                            if (this.hoveredIso === iso) return 'bg-gray-100 text-gray-900';
		                            return 'text-gray-900';
		                        },
		                    };
		                };
		            }

		            if (!window.singleDatePicker) {
		                window.singleDatePicker = function (opts) {
		                    const pad = (n) => String(n).padStart(2, '0');
		                    const toIso = (d) => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
		                    const parseIso = (iso) => {
		                        if (!iso) return null;
		                        const [y, m, d] = iso.split('-').map(Number);
		                        return new Date(y, (m || 1) - 1, d || 1);
		                    };
		                    const fmt = (iso) => {
		                        const d = parseIso(iso);
		                        if (!d) return '';
		                        return `${pad(d.getMonth() + 1)}/${pad(d.getDate())}/${d.getFullYear()}`;
		                    };
		                    const startOfDay = (d) => new Date(d.getFullYear(), d.getMonth(), d.getDate());

		                    return {
		                        open: false,
		                        title: opts?.title || 'Select date',
		                        display: opts?.value ? fmt(opts.value) : '',
		                        iso: opts?.value || '',
		                        wireValueKey: opts?.wireValueKey || '',
		                        base: null,
		                        months: [],
		                        minIso: toIso(startOfDay(new Date())),
		                        hoveredIso: null,

		                        init() {
		                            const baseIso = this.iso || this.minIso;
		                            const baseDate = parseIso(baseIso) || new Date();
		                            this.base = new Date(baseDate.getFullYear(), baseDate.getMonth(), 1);
		                            this.months = [];
		                            this.refreshMonths();
		                        },

		                        prevMonth() {
		                            const today = new Date();
		                            const currentMonth = new Date(today.getFullYear(), today.getMonth(), 1);
		                            const prev = new Date(this.base.getFullYear(), this.base.getMonth() - 1, 1);
		                            if (prev >= currentMonth) {
		                                this.base = prev;
		                                this.refreshMonths();
		                            }
		                        },

		                        nextMonth() {
		                            this.base = new Date(this.base.getFullYear(), this.base.getMonth() + 1, 1);
		                            this.refreshMonths();
		                        },

		                        refreshMonths() {
		                            const m1 = this.buildMonth(this.base.getFullYear(), this.base.getMonth());
		                            const m2Date = new Date(this.base.getFullYear(), this.base.getMonth() + 1, 1);
		                            const m2 = this.buildMonth(m2Date.getFullYear(), m2Date.getMonth());
		                            this.months = [m1, m2];
		                        },

		                        buildMonth(year, monthIndex) {
		                            const monthStart = new Date(year, monthIndex, 1);
		                            const monthEnd = new Date(year, monthIndex + 1, 0);
		                            const daysInMonth = monthEnd.getDate();
		                            const jsDow = monthStart.getDay(); // 0=Sun
		                            const offset = (jsDow + 6) % 7; // 0=Mon

		                            const title = monthStart.toLocaleString(undefined, { month: 'long', year: 'numeric' });
		                            const cells = [];

		                            for (let i = 0; i < offset; i++) {
		                                cells.push({ key: `${year}-${monthIndex}-blank-${i}`, day: null, iso: null, disabled: true });
		                            }

		                            for (let day = 1; day <= daysInMonth; day++) {
		                                const d = new Date(year, monthIndex, day);
		                                const iso = toIso(d);
		                                cells.push({
		                                    key: iso,
		                                    day,
		                                    iso,
		                                    disabled: iso < this.minIso,
		                                });
		                            }

		                            while (cells.length < 42) {
		                                cells.push({ key: `${year}-${monthIndex}-tail-${cells.length}`, day: null, iso: null, disabled: true });
		                            }

		                            return { key: `${year}-${monthIndex}`, title, cells };
		                        },

		                        pick(iso) {
		                            if (!iso || iso < this.minIso) return;
		                            this.iso = iso;
		                            this.display = fmt(this.iso);
		                        },

		                        apply() {
		                            if (!this.$wire || !this.wireValueKey) return;
		                            this.$wire.$set(this.wireValueKey, this.iso);
		                        },

		                        dayClass(cell) {
		                            if (!cell.day) return 'text-transparent';
		                            if (cell.disabled) return 'text-gray-300 cursor-not-allowed';
		                            if (this.iso && cell.iso === this.iso) return 'bg-[#2ab4c0] text-white';
		                            if (this.hoveredIso === cell.iso) return 'bg-gray-100 text-gray-900';
		                            return 'text-gray-900';
		                        },
		                    };
		                };
		            }
		        })();
		    </script>

    @livewireScripts
</body>

</html>
