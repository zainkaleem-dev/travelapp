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
        /* Livewire / NProgress spinner ko globally hide karo */
        #nprogress .spinner,
        #nprogress .spinner-icon {
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
            color: #4b5563;
            cursor: pointer;
            border: none;
            background: transparent;
            border-radius: 6px;
            transition: all 0.2s ease;
            white-space: nowrap;
            outline: none;
        }

        .trip-tab:hover {
            color: #111827;
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
            width: 18px;
            height: 18px;
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
            width: 10px;
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

        /* Quick inline search variant (under navigation) */
        .quick-inline-search {
            background: #d8dde2;
            color: #ffffff;
            box-shadow: 0 12px 30px rgba(0, 0, 0, .18);
            max-width: 100%;
            width: 100%;
        }

        /* Compact height + softer radius for inline quick search fields */
        .quick-inline-search .field-wrap {
            padding: 6px 10px 4px;
            border-radius: 8px;
        }

        /* Large screens: put all fields in one row (inline quick search) */
        @media (min-width: 1024px) {
            .quick-inline-search .qs-row-wrapper {
                display: flex;
                flex-wrap: nowrap;
                gap: 12px;
            }

            .quick-inline-search .qs-row-wrapper>.grid {
                display: contents;
            }

            .quick-inline-search .qs-row-wrapper>.grid>div {
                flex: 1 1 0;
            }

            .quick-inline-search .qs-search-button-wrap {
                margin-top: 0 !important;
                align-self: center;
            }
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

        @keyframes page-line-loading {
            0% {
                left: -30%;
                width: 30%;
            }

            50% {
                left: 40%;
                width: 35%;
            }

            100% {
                left: 100%;
                width: 30%;
            }
        }
    </style>

</head>

<body x-data="{ searchOpen: false }">

    {{-- ── Navbar ── --}}
    <nav class="relative z-50">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 flex items-center justify-between h-12 min-h-[48px]">
            <div class="flex items-center gap-5">

            </div>

            <div class="flex items-center gap-3">
                @auth
                    <div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false"
                        @click.outside="open = false">
                        <button type="button" @click="open = !open" :aria-expanded="open.toString()"
                            class="flex items-center gap-2 rounded-full border border-gray-200 bg-white pl-1.5 pr-2 py-1 hover:border-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-200 focus:ring-offset-0">
                            <div
                                class="w-8 h-8 rounded-full bg-orange-400 flex items-center justify-center text-white text-xs font-bold">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </div>
                            <svg class="w-3.5 h-3.5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-cloak x-show="open" x-transition.origin.top.right
                            class="absolute right-0 mt-2 w-60 bg-white border border-gray-200 rounded-xl shadow-lg z-[9999] overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                            </div>

                            {{-- <a href="{{ route('profile') }}"
                                class="w-full px-4 py-2.5 text-sm text-left text-gray-700 hover:bg-gray-50 flex items-center gap-2 transition-colors">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5.121 17.804A9 9 0 1118.88 17.8M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Profile
                            </a> --}}

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
    </nav>

    {{-- ── Step bar ── --}}
    <div class="bg-white border-b border-gray-200 overflow-hidden sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-3 sm:px-4">
            <div class="flex items-center overflow-x-auto no-scrollbar gap-0 min-w-0"
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
                </div>

                {{-- Right-side search toggle --}}
                <button type="button" @click="searchOpen = !searchOpen"
                    class="ml-auto my-1 flex items-center justify-center w-8 h-8 rounded-full border border-gray-200 bg-white text-gray-500 hover:text-[#2ab4c0] hover:border-[#2ab4c0]/60 shadow-sm flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Inline quick search panel (under navigation) --}}
    <div x-cloak x-show="searchOpen" x-transition.opacity x-transition.duration.200ms>
        <div class="max-w-none mx-auto px-3 sm:px-4 py-4">
            {{-- Directly render the search card without extra outer chrome --}}
            @livewire('quick-search')
        </div>
    </div>



    {{-- Page Content Slot --}}
    <div class="px-3 sm:px-4 lg:px-6 pb-12 sm:pb-16 w-full max-w-none">
        {{ $slot }}
    </div>

    {{-- NProgress Script and Livewire Integration --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
    <script>
        // Spinner ko disable karo, sirf top progress bar dikhayega
        NProgress.configure({ showSpinner: false });

        document.addEventListener('livewire:initialized', () => {
            Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
                NProgress.start();
                succeed(() => { NProgress.done(); });
                fail(() => { NProgress.done(); });
            });
        });

        // Close on ESC
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (document.body.__x && document.body.__x.$data && typeof document.body.__x.$data.searchOpen !== 'undefined') {
                    document.body.__x.$data.searchOpen = false;
                }
            }
        });
    </script>

    @livewireScripts
</body>

</html>
