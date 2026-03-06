<div>
    {{-- ══════════════════════════════════════════════════════════
    NAVBAR
    ══════════════════════════════════════════════════════════ --}}
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 flex items-center justify-between h-12 gap-3">

            {{-- Back / Next --}}
            <div class="flex items-center gap-2 flex-shrink-0">

            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2 flex-shrink-0">

                <a href="{{ route('signup') }}" wire:navigate
                    class="flex items-center gap-1.5 px-3 py-1.5 font-semibold text-white rounded-lg transition-colors"
                    style="background: #2ab4c0;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="hidden sm:inline">Register</span>
                    <span class="sm:hidden">Register</span>
                </a>
            </div>

        </div>
    </nav>
    {{-- ══════════════════════════════════════════════════════════
    MAIN CONTENT
    ══════════════════════════════════════════════════════════ --}}
    <div class="flex-1 flex items-start sm:items-center justify-center px-4 py-8">
        <div class="w-full max-w-sm">

            {{-- ── Login Card ───────────────────────────────────── --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">

                {{-- Top gradient accent --}}
                <div class="h-1 w-full" style="background: #2ab4c0;"></div>

                <div class="px-6 py-6 space-y-4">

                    {{-- Title --}}
                    <div class="fu1">
                        <h1 class="text-lg font-bold text-gray-800">Login</h1>
                        <p class="text-gray-600 mt-0.5" style="font-size:11px">Sign in with your email and password.</p>
                    </div>

                    {{-- ── Form ──────────────────────────────────── --}}
                    <form wire:submit="login" novalidate>
                        @if (session()->has('success'))
                            <div class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 rounded-lg px-3 py-2 mb-3"
                                style="font-size:11px">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                {{ session('success') }}
                            </div>
                        @endif

                        {{-- Global error message --}}
                        @if($errorMessage)
                            <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-600 rounded-lg px-3 py-2 mb-1"
                                style="font-size:11px">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01" />
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input wire:model="email" id="email" type="email" placeholder="your@email.com"
                                    autocomplete="email"
                                    class="input-field @error('email') border-red-400 bg-red-50 @enderror" />
                            </div>
                            @error('email')
                                <p class="mt-1 text-red-500" style="font-size:10px">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="fu4 mt-4">
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="password" class="text-gray-600 font-medium"
                                    style="font-size:11px">Password</label>
                                <a href="{{ route('password.request') }}"
                                    class="text-indigo-600 hover:text-indigo-800 font-semibold transition-colors"
                                    style="font-size:10px">
                                    Forgot password?
                                </a>
                            </div>
                            <div class="relative">
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input wire:model="password" id="password"
                                    type="{{ $showPassword ? 'text' : 'password' }}" placeholder="••••••••"
                                    autocomplete="current-password"
                                    class="input-field @error('password') border-red-400 bg-red-50 @enderror"
                                    style="padding-right:38px" />

                                {{-- Toggle show/hide password --}}
                                <button type="button" wire:click="togglePassword"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                    @if($showPassword)
                                        {{-- Eye-slash (password visible) --}}
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    @else
                                        {{-- Eye (password hidden) --}}
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
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
                            <input wire:model="remember" id="remember" type="checkbox"
                                class="w-3.5 h-3.5 rounded cursor-pointer" />
                            <label for="remember" class="text-gray-600 cursor-pointer select-none"
                                style="font-size:11px">
                                Remember me
                            </label>
                        </div>

                        {{-- Login button --}}
                        <button type="submit" wire:loading.attr="disabled"
                            class="fu6 login-btn w-full py-2.5 rounded-lg text-white font-bold flex items-center justify-center gap-2 mt-4"
                            style="font-size:13px; background: #2ab4c0;">

                            {{-- Default state --}}
                            <span wire:loading.remove wire:target="login" class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Login
                            </span>

                            {{-- Loading state --}}
                            <span wire:loading wire:target="login" class="flex items-center gap-2">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                </svg>
                                Signing in…
                            </span>
                        </button>

                    </form>
                    {{-- ── End Form ──────────────────────────────── --}}

                    {{-- Register link --}}
                    <p class="fu8 text-center text-gray-600 pb-1" style="font-size:11px">
                        No account?
                        <a href="{{ route('signup') }}"
                            class="text-indigo-600 hover:text-indigo-800 font-semibold transition-colors">
                            Create one
                        </a>
                    </p>

                </div>

                {{-- Bottom gradient accent --}}
                <div class="h-0.5 w-full" style="background: #2ab4c0;"></div>
            </div>

            {{-- Footer --}}
            <p class="text-center text-gray-600 mt-4" style="font-size:10px">
                © 2024 FlightBook ·
                <a href="{{ route('privacy') }}" class="hover:text-gray-600 transition-colors">Privacy Policy</a> ·
                <a href="{{ route('terms') }}" class="hover:text-gray-600 transition-colors">Terms of Service</a>
            </p>

        </div>
    </div>

</div>