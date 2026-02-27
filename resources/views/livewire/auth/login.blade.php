{{-- Backup of previous login UI (tailwind-based) --}}
<div>

    {{-- ══════════════════════════════════════════════════════════
         PROMO BANNER
    ══════════════════════════════════════════════════════════ --}}
    <div class="bg-indigo-600 text-white py-1.5 text-center text-xs relative">
        <span>🏷️ Up to 20% discount with early booking! Sign up now and benefit from the offer.</span>

        <div class="hidden md:flex absolute right-4 top-1/2 -translate-y-1/2 items-center gap-4 text-white/80">
            <button class="flex items-center gap-1 hover:text-white transition-colors">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <rect x="5" y="2" width="14" height="20" rx="2"/>
                </svg>
                App
            </button>
            <button class="flex items-center gap-1 hover:text-white transition-colors">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3M12 17h.01"/>
                </svg>
                Support
                <svg class="w-2 h-2 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <button class="flex items-center gap-1 hover:text-white transition-colors">
                English
                <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <button class="flex items-center gap-1 hover:text-white transition-colors">
                USD
                <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         NAVBAR
    ══════════════════════════════════════════════════════════ --}}
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 flex items-center justify-between h-12 gap-3">

            {{-- Back / Next --}}
            <div class="flex items-center gap-2 flex-shrink-0">
                <button type="button" onclick="window.history.back()" class="flex items-center gap-1 px-3 py-1.5 rounded-full bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition-colors">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back
                </button>
                <button type="button" onclick="window.history.forward()" class="flex items-center gap-1 px-3 py-1.5 rounded-full border border-indigo-200 text-indigo-600 font-medium hover:bg-indigo-50 transition-colors">
                    Next
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            {{-- Search --}}
            <div class="flex-1 max-w-xs mx-2 relative hidden sm:block">
                <input type="text" placeholder="Search..."
                       class="w-full pl-3 pr-8 py-1.5 border border-gray-200 rounded-full bg-gray-50 text-xs
                              focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400"/>
                <button class="absolute right-2 top-1/2 -translate-y-1/2 w-5 h-5 rounded-full bg-orange-400 flex items-center justify-center">
                    <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35"/>
                    </svg>
                </button>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2 flex-shrink-0">
                <button class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Find Reservation
                </button>
                <a href="{{ route('signup') }}" wire:navigate class="flex items-center gap-1.5 px-3 py-1.5 font-semibold bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="hidden sm:inline">Register</span>
                    <span class="sm:hidden">Register</span>
                </a>
            </div>

        </div>
    </nav>

    {{-- ══════════════════════════════════════════════════════════
         STEP PROGRESS BAR
    ══════════════════════════════════════════════════════════ --}}
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4">
            <div class="hidden sm:block text-xs text-gray-400 py-1">Home / Login</div>
            <div class="flex items-center overflow-x-auto">

                {{-- Active step --}}
                <div class="flex items-center gap-1.5 px-4 sm:px-5 py-2.5 text-white font-semibold whitespace-nowrap"
                     style="background:#2ab4c0">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Login
                </div>

                <div class="px-4 sm:px-5 py-2.5 text-gray-400 whitespace-nowrap flex items-center gap-1.5">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                    </svg>
                    Select Flight
                </div>

                <div class="px-4 sm:px-5 py-2.5 text-gray-400 whitespace-nowrap hidden sm:block">Passenger Details</div>
                <div class="px-4 sm:px-5 py-2.5 text-gray-400 whitespace-nowrap hidden sm:block">Additional Services</div>
                <div class="px-4 sm:px-5 py-2.5 text-gray-400 whitespace-nowrap hidden md:block">Choice Seat</div>
                <div class="px-4 sm:px-5 py-2.5 text-gray-400 whitespace-nowrap ml-auto hidden sm:block">Payment</div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         MAIN CONTENT
    ══════════════════════════════════════════════════════════ --}}
    <div class="flex-1 flex items-start sm:items-center justify-center px-4 py-8">
        <div class="w-full max-w-sm">

            {{-- ── Login Card ───────────────────────────────────── --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">

                {{-- Top gradient accent --}}
                <div class="h-1 w-full" style="background:linear-gradient(90deg,#2ab4c0,#6366f1)"></div>

                <div class="px-6 py-6 space-y-4">

                    {{-- Title --}}
                    <div class="fu1">
                        <h1 class="text-lg font-bold text-gray-800">Login</h1>
                        <p class="text-gray-400 mt-0.5" style="font-size:11px">Sign in with your email and password.</p>
                    </div>

                    {{-- Flight context chip --}}
                    <div class="fu2 flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-lg px-3 py-2">
                        <div class="w-6 h-6 rounded-lg flex items-center justify-center flex-shrink-0" style="background:#2ab4c0">
                            <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                            </svg>
                        </div>
                        <div class="flex items-center gap-1.5 flex-1 min-w-0">
                            <span class="text-gray-600 font-semibold" style="font-size:10px">{{ $flightFrom }}</span>
                            <div class="flex-1 flex items-center gap-0.5">
                                <div class="flight-dot-sm"></div>
                                <div class="flex-1 border-t border-dashed border-gray-300"></div>
                                <div class="flight-dot-sm"></div>
                            </div>
                            <span class="text-gray-600 font-semibold" style="font-size:10px">{{ $flightTo }}</span>
                        </div>
                        <span class="text-gray-400 flex-shrink-0 font-medium" style="font-size:9px">
                            {{ $passengers }} {{ $passengers === 1 ? 'Passenger' : 'Passengers' }} · {{ $cabinClass }}
                        </span>
                    </div>

                    {{-- ── Form ──────────────────────────────────── --}}
                    <form wire:submit="login" novalidate>

                        {{-- Global error message --}}
                        @if($errorMessage)
                            <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-600 rounded-lg px-3 py-2 mb-1" style="font-size:11px">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
                                </svg>
                                {{ $errorMessage }}
                            </div>
                        @endif

                        {{-- Email --}}
                        <div class="fu3">
                            <label for="email" class="block text-gray-600 font-medium mb-1.5" style="font-size:11px">
                                Email
                            </label>
                            <div class="relative">
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <input wire:model="email"
                                       id="email"
                                       type="email"
                                       placeholder="your@email.com"
                                       autocomplete="email"
                                       class="input-field @error('email') border-red-400 bg-red-50 @enderror"/>
                            </div>
                            @error('email')
                                <p class="mt-1 text-red-500" style="font-size:10px">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="fu4 mt-4">
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="password" class="text-gray-600 font-medium" style="font-size:11px">Password</label>
                                <a href="{{ route('password.request') }}"
                                   class="text-indigo-500 hover:text-indigo-700 transition-colors"
                                   style="font-size:10px">
                                    Forgot password?
                                </a>
                            </div>
                            <div class="relative">
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <input wire:model="password"
                                       id="password"
                                       type="{{ $showPassword ? 'text' : 'password' }}"
                                       placeholder="••••••••"
                                       autocomplete="current-password"
                                       class="input-field @error('password') border-red-400 bg-red-50 @enderror"
                                       style="padding-right:38px"/>

                                {{-- Toggle show/hide password --}}
                                <button type="button"
                                        wire:click="togglePassword"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                    @if($showPassword)
                                        {{-- Eye-slash (password visible) --}}
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                        </svg>
                                    @else
                                        {{-- Eye (password hidden) --}}
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    @endif
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1 text-red-500" style="font-size:10px">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Remember me --}}
                        <div class="fu5 flex items-center gap-2 mt-4">
                            <input wire:model="remember"
                                   id="remember"
                                   type="checkbox"
                                   class="w-3.5 h-3.5 rounded cursor-pointer"/>
                            <label for="remember" class="text-gray-500 cursor-pointer select-none" style="font-size:11px">
                                Remember me
                            </label>
                        </div>

                        {{-- Login button --}}
                        <button type="submit"
                                wire:loading.attr="disabled"
                                class="fu6 login-btn w-full py-2.5 rounded-lg text-white font-bold flex items-center justify-center gap-2 mt-4"
                                style="font-size:13px">

                            {{-- Default state --}}
                            <span wire:loading.remove wire:target="login" class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                                Login
                            </span>

                            {{-- Loading state --}}
                            <span wire:loading wire:target="login" class="flex items-center gap-2">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                                </svg>
                                Signing in…
                            </span>
                        </button>

                    </form>
                    {{-- ── End Form ──────────────────────────────── --}}

                    {{-- Divider --}}
                    <div class="fu6 flex items-center gap-3">
                        <div class="flex-1 border-t border-gray-200"></div>
                        <span class="text-gray-400" style="font-size:10px">or continue with</span>
                        <div class="flex-1 border-t border-gray-200"></div>
                    </div>

                    {{-- Social buttons --}}
                    <div class="fu7 grid grid-cols-2 gap-2">
                        <button wire:click="loginWithGoogle" class="social-btn">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            Google
                        </button>

                        <button wire:click="loginWithFacebook" class="social-btn">
                            <svg class="w-3.5 h-3.5 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            Facebook
                        </button>
                    </div>

                    {{-- Register link --}}
                    <p class="fu8 text-center text-gray-500 pb-1" style="font-size:11px">
                        No account?
                        <a href="{{ route('signup') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold transition-colors">
                            Create one
                        </a>
                    </p>

                </div>

                {{-- Bottom gradient accent --}}
                <div class="h-0.5 w-full" style="background:linear-gradient(90deg,#6366f1,#2ab4c0)"></div>
            </div>

            {{-- Footer --}}
            <p class="text-center text-gray-400 mt-4" style="font-size:10px">
                © 2024 FlightBook ·
                <a href="{{ route('privacy') }}" class="hover:text-gray-600 transition-colors">Privacy Policy</a> ·
                <a href="{{ route('terms') }}"   class="hover:text-gray-600 transition-colors">Terms of Service</a>
            </p>

        </div>
    </div>

</div>
