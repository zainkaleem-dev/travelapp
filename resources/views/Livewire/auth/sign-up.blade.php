<div class="flex flex-col min-h-screen">

    {{-- ─── Top Promo Bar ──────────────────────────────────────────────────── --}}
    <div class="w-full bg-indigo-600 text-white text-xs sm:text-sm text-center py-2.5 px-4 flex items-center justify-center gap-2 font-medium relative">
        <span>⚡</span>
        <span>Up to 20% discount with early booking! Sign up now and benefit from the offer.</span>
        <div class="hidden sm:flex items-center gap-4 ml-auto absolute right-4">
            <a href="#" class="opacity-80 hover:opacity-100 text-xs">App</a>
            <a href="#" class="opacity-80 hover:opacity-100 text-xs">Support</a>
            <a href="#" class="opacity-80 hover:opacity-100 text-xs">English</a>
            <a href="#" class="opacity-80 hover:opacity-100 text-xs">USD</a>
        </div>
    </div>

    {{-- ─── Navbar ──────────────────────────────────────────────────────────── --}}
    <nav class="bg-white border-b border-gray-200 px-4 sm:px-8 py-3 flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <button type="button" onclick="window.history.back()" class="flex items-center gap-1.5 border border-gray-300 rounded-full px-4 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
                Back
            </button>
            <button type="button" onclick="window.history.forward()" class="flex items-center gap-1.5 border border-gray-300 rounded-full px-4 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                Next
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
        <div class="flex-1 max-w-xs hidden sm:block">
            <div class="flex items-center gap-2 bg-gray-100 rounded-full px-4 py-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                <input type="text" placeholder="Search..." class="bg-transparent text-sm text-gray-600 focus:outline-none w-full"/>
                <div class="w-6 h-6 bg-orange-400 rounded-full flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 21l-4.35-4.35M11 19A8 8 0 1011 3a8 8 0 000 16z"/></svg>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button class="hidden sm:flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                Find Reservation
            </button>
            <a href="{{ route('login') }}" wire:navigate class="flex items-center gap-1.5 px-3 py-1.5 font-semibold bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Login
            </a>
        </div>
    </nav>

    {{-- ─── Breadcrumb + Step Bar ───────────────────────────────────────────── --}}
    <div class="bg-white border-b border-gray-200 px-4 sm:px-8">
        <div class="text-xs text-gray-400 py-2">Home / Sign Up</div>
        <div class="flex overflow-x-auto gap-0 -mb-px">
            <div class="flex items-center gap-1.5 px-4 sm:px-5 py-2.5 text-white font-semibold whitespace-nowrap" style="background:#2ab4c0">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Sign Up
            </div>
            <div class="flex items-center gap-2 px-5 py-3 text-gray-400 text-sm whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                Select Flight
            </div>
            <div class="hidden sm:flex items-center gap-2 px-5 py-3 text-gray-400 text-sm whitespace-nowrap">Passenger Details</div>
            <div class="hidden md:flex items-center gap-2 px-5 py-3 text-gray-400 text-sm whitespace-nowrap">Additional Services</div>
            <div class="hidden md:flex items-center gap-2 px-5 py-3 text-gray-400 text-sm whitespace-nowrap">Choice Seat</div>
            <div class="hidden lg:flex items-center gap-2 px-5 py-3 text-gray-400 text-sm ml-auto whitespace-nowrap">Payment</div>
        </div>
    </div>

    {{-- ─── Main Content ────────────────────────────────────────────────────── --}}
    <main class="flex-1 flex items-start justify-center py-10 px-4">
        <div class="w-full max-w-md">

            <div class="bg-white rounded-2xl card-shadow border border-gray-100 overflow-hidden">

                {{-- Top accent bar --}}
                <div class="h-1 gradient-btn w-full"></div>

                <div class="p-8">

                    {{-- Flash success --}}
                    @if (session()->has('success'))
                        <div class="mb-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    <h1 class="text-2xl font-bold text-gray-900">Sign Up</h1>
                    <p class="text-gray-500 text-sm mt-1 mb-6">Create your account to continue.</p>

                    {{-- Flight route badge --}}
                    <div class="flex items-center gap-3 bg-gray-50 rounded-xl px-4 py-3 mb-6 border border-gray-100">
                        <div class="w-9 h-9 rounded-xl bg-brand flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600 font-medium flex-1">
                            <span class="font-bold text-gray-800">DUS</span>
                            <div class="flex-1 flex items-center gap-1">
                                <div class="w-1.5 h-1.5 rounded-full bg-brand"></div>
                                <div class="flex-1 border-t border-dashed border-gray-300"></div>
                                <div class="w-1.5 h-1.5 rounded-full bg-brand"></div>
                            </div>
                            <span class="font-bold text-gray-800">IST</span>
                        </div>
                        <span class="text-xs text-gray-400 ml-2">1 Passenger · ECO</span>
                    </div>

                    {{-- ── Registration Form ──────────────────────────────── --}}
                    <form wire:submit="register" class="space-y-4" novalidate>

                        {{-- Name --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Name</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                                </div>
                                <input
                                    type="text"
                                    wire:model.live.debounce.400ms="name"
                                    placeholder="Your full name"
                                    class="w-full pl-10 pr-4 py-3 border @error('name') border-red-400 @else border-gray-200 @enderror rounded-xl text-sm text-gray-800 placeholder-gray-400 transition"
                                />
                            </div>
                            @error('name')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 7l10 7 10-7"/></svg>
                                </div>
                                <input
                                    type="email"
                                    wire:model.live.debounce.400ms="email"
                                    placeholder="your@email.com"
                                    class="w-full pl-10 pr-4 py-3 border @error('email') border-red-400 @else border-gray-200 @enderror rounded-xl text-sm text-gray-800 placeholder-gray-400 transition"
                                />
                            </div>
                            @error('email')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V7a4 4 0 018 0v4"/></svg>
                                </div>
                                <input
                                    type="{{ $showPassword ? 'text' : 'password' }}"
                                    wire:model="password"
                                    placeholder="••••••••"
                                    class="w-full pl-10 pr-10 py-3 border @error('password') border-red-400 @else border-gray-200 @enderror rounded-xl text-sm text-gray-800 placeholder-gray-400 transition"
                                />
                                <button
                                    type="button"
                                    wire:click="togglePassword"
                                    class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600"
                                >
                                    @if ($showPassword)
                                        {{-- eye-off --}}
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94"/>
                                            <path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/>
                                            <path d="M1 1l22 22"/>
                                            <path d="M10.73 10.73A3 3 0 0013.27 13.27"/>
                                        </svg>
                                    @else
                                        {{-- eye --}}
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    @endif
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V7a4 4 0 018 0v4"/></svg>
                                </div>
                                <input
                                    type="{{ $showPasswordConfirmation ? 'text' : 'password' }}"
                                    wire:model="password_confirmation"
                                    placeholder="••••••••"
                                    class="w-full pl-10 pr-10 py-3 border @error('password_confirmation') border-red-400 @else border-gray-200 @enderror rounded-xl text-sm text-gray-800 placeholder-gray-400 transition"
                                />
                                <button
                                    type="button"
                                    wire:click="togglePasswordConfirmation"
                                    class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600"
                                >
                                    @if ($showPasswordConfirmation)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94"/>
                                            <path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/>
                                            <path d="M1 1l22 22"/>
                                            <path d="M10.73 10.73A3 3 0 0013.27 13.27"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    @endif
                                </button>
                            </div>
                            @error('password_confirmation')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Terms checkbox --}}
                        <div class="flex items-start gap-2.5">
                            <input
                                type="checkbox"
                                id="agreed"
                                wire:model="agreed"
                                class="w-4 h-4 mt-0.5 rounded border-gray-300 accent-teal-500 cursor-pointer"
                            />
                            <label for="agreed" class="text-sm text-gray-600 cursor-pointer leading-snug">
                                I agree to the <a href="#" class="text-brand font-medium hover:underline">Terms of Service</a>
                            </label>
                        </div>
                        @error('agreed')
                            <p class="-mt-2 text-xs text-red-500">{{ $message }}</p>
                        @enderror

                        {{-- Submit --}}
                        <button
                            type="submit"
                            class="gradient-btn w-full flex items-center justify-center gap-2 text-white font-semibold py-3.5 rounded-xl text-sm mt-2 shadow-md"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-70 cursor-not-allowed"
                        >
                            <span wire:loading.remove wire:target="register">
                                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                                Create Account
                            </span>
                            <span wire:loading wire:target="register" class="flex items-center gap-2">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                                </svg>
                                Creating account…
                            </span>
                        </button>

                    </form>

                    {{-- Divider --}}
                    <div class="divider-line my-5">
                        <span class="text-xs text-gray-400">or continue with</span>
                    </div>

                    {{-- Social buttons --}}
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('auth.google') }}" class="social-btn flex items-center justify-center gap-2.5 border border-gray-200 rounded-xl py-2.5 text-sm font-medium text-gray-700 transition">
                            <svg class="w-4 h-4" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                            Google
                        </a>
                        <a href="{{ route('auth.facebook') }}" class="social-btn flex items-center justify-center gap-2.5 border border-gray-200 rounded-xl py-2.5 text-sm font-medium text-gray-700 transition">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="#1877F2"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            Facebook
                        </a>
                    </div>

                    <p class="text-center text-sm text-gray-500 mt-5">
                        Already have an account?
                        <a href="{{ route('login') }}" wire:navigate class="text-brand font-semibold hover:underline ml-1">Login</a>
                    </p>

                </div>
            </div>

            <p class="text-center text-xs text-gray-400 mt-6">
                © {{ date('Y') }} FlightBook ·
                <a href="#" class="hover:underline">Privacy Policy</a> ·
                <a href="#" class="hover:underline">Terms of Service</a>
            </p>
        </div>
    </main>

</div>
