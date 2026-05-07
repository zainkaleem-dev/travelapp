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
    @stack('styles')

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
            padding: 6px 13px;
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
            background: #fff;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            outline: none;
            padding: 10px 12px;
            transition: border-color .15s, box-shadow .15s, background-color .15s;
            appearance: none;
            -webkit-appearance: none;
        }

        .field-input:focus {
            border-color: #2ab4c0;
            box-shadow: 0 0 0 2px rgba(42, 180, 192, .12);
            background: #fff;
        }

        /* Auth-style input used in admin modules */
        .input-field {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 11px;
            color: #1e293b;
            background: #f8fafc;
            transition: all 0.15s ease;
        }

        .input-field::placeholder {
            color: #94a3b8;
        }

        .input-field:focus {
            outline: none;
            border-color: #2ab4c0;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(42, 180, 192, 0.1);
        }

        .admin-dropdown-wrap {
            position: relative;
        }

        .admin-dropdown {
            width: 100%;
            appearance: none;
            -webkit-appearance: none;
            border: 1px solid #e5e7eb;
            background: #fff;
            border-radius: 8px;
            padding: 6px 32px 6px 12px;
            font-size: 11px;
            font-weight: 600;
            color: #374151;
            line-height: 1.25rem;
            transition: border-color .15s, box-shadow .15s;
            cursor: pointer;
        }

        .admin-dropdown:focus {
            outline: none;
            border-color: #d1d5db;
            box-shadow: 0 0 0 2px rgba(229, 231, 235, .9);
        }

        .admin-dropdown-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 14px;
            height: 14px;
            color: #6b7280;
            pointer-events: none;
        }

        .admin-menu-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            border: 1px solid #e5e7eb;
            background: #fff;
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 11px;
            font-weight: 600;
            color: #374151;
            line-height: 1.25rem;
            transition: border-color .15s, box-shadow .15s;
        }

        .admin-menu-btn:hover {
            border-color: #d1d5db;
        }

        .admin-menu-btn:focus {
            outline: none;
            border-color: #d1d5db;
            box-shadow: 0 0 0 2px rgba(229, 231, 235, .9);
        }

        .admin-menu-panel {
            position: absolute;
            right: 0;
            left: 0;
            margin-top: 8px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(17, 24, 39, .12);
            overflow: hidden;
            z-index: 60;
            max-height: 260px;
            overflow-y: auto;
        }

        .admin-menu-item {
            width: 100%;
            text-align: left;
            padding: 10px 14px;
            font-size: 11px;
            color: #374151;
            background: #fff;
            transition: background-color .12s;
        }

        .admin-menu-item:hover {
            background: #f9fafb;
        }

        .admin-menu-item.is-active {
            color: #2ab4c0;
            font-weight: 700;
        }

        select.field-input {
            appearance: auto;
            -webkit-appearance: auto;
        }

        /* Keep legacy wrapped fields visually unchanged (search widgets, etc.) */
        .field-wrap .field-input {
            background: transparent;
            border: none;
            border-radius: 0;
            padding: 0;
            box-shadow: none;
            appearance: auto;
            -webkit-appearance: auto;
        }

	        .field-input::placeholder {
	            color: #6b7280;
	            font-weight: 400;
	        }

	        /* Right-side icons inside inputs (From/To) */
	        /* .field-wrap.has-icon-right {
	            padding-right: 85px;
	        } */

            .field-wrap.has-icon-right {
                padding-right: clamp(170px, 8vw, 85px);
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
    @if(session()->has('impersonated_by'))
        <div class="bg-indigo-600 px-4 py-2 text-white shadow-sm sticky top-0 z-[1000]">
            <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="text-[13px] font-semibold leading-tight">
                        You are currently impersonating <span class="underline decoration-indigo-300 decoration-2 underline-offset-2">{{ auth()->user()->display_name }}</span>
                    </span>
                </div>
                <a href="{{ route('impersonate.leave') }}" class="flex-shrink-0 rounded-lg bg-white/20 px-3 py-1.5 text-[11px] font-black uppercase tracking-wider hover:bg-white/30 transition-colors focus:outline-none focus:ring-2 focus:ring-white/50">
                    Return to Admin
                </a>
            </div>
        </div>
    @endif




    @php
        $user = auth()->user();
        $isAdmin = $user && $user->hasRole('Super Admin') || $user->hasRole('Organization Admin') || $user->hasRole('Company Admin');
        $isAdminRoute = request()->routeIs([
            'companies.*',
            'branches.*',
            'users.*',
            'roles.*',
            'features*',
            'admin.trip-purpose*',
            'admin.integrations-api*',
            'admin.audit-logs*',
            'admin.system-settings*',
            'admin.countries-and-cities*',
            'admin.countries.*',
            'admin.cities.*',
            'admin.airports*',
            'subscriptions.*',
            'admin.travel-policy.*',
            'dashboard'
        ]);
    @endphp

    {{-- ── Navbar ── --}}
    <nav class="relative z-50 bg-white border-b border-gray-200">
        <div class="{{ $isAdminRoute ? 'w-full px-3 sm:px-4 lg:px-6 py-3' : 'max-w-7xl mx-auto px-3 sm:px-4 py-3' }} flex items-center justify-between gap-4">
            <a href="{{ route('root') }}" class="flex-shrink-0">
                <img src="{{ asset('assets/images/travelapp_logo.png') }}" alt="TravelApp logo"
                    class="h-9 w-auto object-contain" />
            </a>

            {{-- Hierarchical Context Switcher --}}
            {{-- <div class="hidden md:block ml-4">
                <livewire:common.global-context-switcher />
            </div> --}}

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

                            {{-- @can('Manage Global System')
                            <a href="{{ route('admin.settings') }}"
                                class="w-full px-4 py-2.5 text-sm text-left text-gray-700 hover:bg-gray-50 flex items-center gap-2 transition-colors">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 3l7 4v6c0 5-3.5 9-7 10-3.5-1-7-5-7-10V7l7-4z" />
                                </svg>
                                Super Admin Settings
                            </a>
                            @endcan --}}

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
        
        
        @if(!$isAdminRoute)
            @include('partials.navigation-user')
        @endif
    </nav>

    @if(request()->routeIs('flights.list'))
        <div x-cloak x-show="searchOpen" x-transition.opacity x-transition.duration.200ms class="max-w-7xl mx-auto px-1 sm:px-2 lg:px-4 mt-4 mb-4">
            <div class="p-3 sm:p-4">
                <div class="flex flex-col relative">
                    @livewire('quick-search')
                </div>
            </div>
        </div>
    @endif

    @if($isAdminRoute)
        <div class="w-full">
            @include('partials.navigation-admin')
        </div>
    @else
        {{-- ── Step bar ── --}}
        <div class="z-40 bg-transparent">
            <div class="max-w-7xl mx-auto px-1 sm:px-2 lg:px-4 mt-8 pt-4 pb-12 sm:pb-16">
                @if(request()->routeIs('flights.search'))
                    @include('partials.navigation-flight')
                @endif

                <div class="p-3 sm:p-4">
                    <div class="flex flex-col relative">
                        @include('partials.form-wizard')
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    @endif
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.appSwalConfirmAction = async function(options = {}) {
            const {
                wire,
                action,
                args = [],
                confirmTitle = 'Are you sure?',
                confirmText = '',
                doneTitle = 'Done',
                doneText = '',
                confirmButtonText = 'Yes',
                cancelButtonText = 'No',
            } = options;

            if (!wire || !action || typeof wire[action] !== 'function') return;

            if (!window.Swal) {
                if (window.confirm(confirmTitle)) {
                    await wire[action](...args);
                }
                return;
            }

            const result = await Swal.fire({
                title: confirmTitle,
                text: confirmText || undefined,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText,
                cancelButtonText,
                reverseButtons: true,
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-2xl border border-gray-100 shadow-2xl',
                    title: 'text-gray-900 font-black',
                    htmlContainer: 'text-gray-600',
                    actions: 'gap-3',
                    confirmButton: 'inline-flex items-center justify-center rounded-lg bg-[#2ab4c0] px-4 py-2 text-sm font-bold text-white hover:bg-[#229aa4]',
                    cancelButton: 'inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-bold text-gray-600 hover:bg-gray-50'
                }
            });

            if (!result.isConfirmed) return;

            await wire[action](...args);

            await Swal.fire({
                title: doneTitle,
                text: doneText || undefined,
                icon: 'success',
                timer: 1400,
                showConfirmButton: false,
                customClass: {
                    popup: 'rounded-2xl border border-gray-100 shadow-xl',
                    title: 'text-gray-900 font-black',
                    htmlContainer: 'text-gray-600'
                }
            });
        };

        window.appSwalFromDataset = function(el, wire) {
            if (!el || !wire) return;

            let args = [];
            try {
                const rawArgs = el.dataset.args || '[]';
                args = JSON.parse(rawArgs);
                if (!Array.isArray(args)) args = [];
            } catch (e) {
                args = [];
            }

            return window.appSwalConfirmAction({
                wire,
                action: el.dataset.action,
                args,
                confirmTitle: el.dataset.confirmTitle || 'Are you sure?',
                confirmText: el.dataset.confirmText || '',
                doneTitle: el.dataset.doneTitle || 'Done',
                doneText: el.dataset.doneText || '',
                confirmButtonText: el.dataset.confirmButtonText || 'Yes',
                cancelButtonText: el.dataset.cancelButtonText || 'No',
            });
        };
    </script>

    @stack('scripts')
    @livewireScripts
</body>

</html>
