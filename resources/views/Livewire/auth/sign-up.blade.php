<div>

    {{-- ══════════════════════════════════════════════════════════
    MAIN CONTENT
    ══════════════════════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col min-h-screen items-start sm:items-center justify-center px-4 py-8">
        <div class="flex justify-center">
            <img src="{{ asset('assets/images/travelapp_logo.svg') }}" alt="Logo" class="h-64 w-auto">
        </div>
        <div class="w-full max-w-sm">

            {{-- ── Signup Card ─────────────────────────────────── --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">

                {{-- Top gradient accent --}}
                <div class="h-1 w-full" style="background: #2ab4c0;"></div>

                <div class="px-6 py-6 space-y-4">

                    {{-- Flash success --}}
                    @if (session()->has('success'))
                        <div class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 rounded-lg px-3 py-2 mb-3"
                            style="font-size:11px">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Title --}}
                    <div class="fu1">
                        <h1 class="text-lg font-bold text-gray-800">Sign Up</h1>
                        <p class="text-gray-400 mt-0.5" style="font-size:11px">Create your account to continue.</p>
                    </div>

                    {{-- ── Form ──────────────────────────────────── --}}
                    <form wire:submit="register" novalidate>

                        {{-- Name --}}
                        <div class="fu3">
                            <label for="name" class="block text-gray-600 font-medium mb-1.5" style="font-size:11px">
                                Name
                            </label>
                            <div class="relative">
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="8" r="4" />
                                        <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" />
                                    </svg>
                                </div>
                                <input wire:model.live.debounce.400ms="name" id="name" type="text"
                                    placeholder="Your full name" autocomplete="name"
                                    class="input-field @error('name') border-red-400 bg-red-50 @enderror" />
                            </div>
                            @error('name')
                                <p class="mt-1 text-red-500" style="font-size:10px">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="fu4 mt-4">
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
                                <input wire:model.live.debounce.400ms="email" id="email" type="email"
                                    placeholder="your@email.com" autocomplete="email"
                                    class="input-field @error('email') border-red-400 bg-red-50 @enderror" />
                            </div>
                            @error('email')
                                <p class="mt-1 text-red-500" style="font-size:10px">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="fu5 mt-4">
                            <label for="password" class="text-gray-600 font-medium mb-1.5 block"
                                style="font-size:11px">Password</label>
                            <div class="relative">
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input wire:model="password" id="password"
                                    type="{{ $showPassword ? 'text' : 'password' }}" placeholder="••••••••"
                                    autocomplete="new-password"
                                    class="input-field @error('password') border-red-400 bg-red-50 @enderror"
                                    style="padding-right:38px" />
                                <button type="button" wire:click="togglePassword"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                    @if($showPassword)
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    @else
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

                        {{-- Confirm Password --}}
                        <div class="fu6 mt-4">
                            <label for="password_confirmation" class="text-gray-600 font-medium mb-1.5 block"
                                style="font-size:11px">Confirm Password</label>
                            <div class="relative">
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input wire:model="password_confirmation" id="password_confirmation"
                                    type="{{ $showPasswordConfirmation ? 'text' : 'password' }}" placeholder="••••••••"
                                    autocomplete="new-password"
                                    class="input-field @error('password_confirmation') border-red-400 bg-red-50 @enderror"
                                    style="padding-right:38px" />
                                <button type="button" wire:click="togglePasswordConfirmation"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                    @if($showPasswordConfirmation)
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    @else
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    @endif
                                </button>
                            </div>
                            @error('password_confirmation')
                                <p class="mt-1 text-red-500" style="font-size:10px">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Terms --}}
                        <div class="fu7 flex items-center gap-2 mt-4">
                            <input wire:model="agreed" id="agreed" type="checkbox"
                                class="w-3.5 h-3.5 rounded cursor-pointer" />
                            <label for="agreed" class="text-gray-500 cursor-pointer select-none" style="font-size:11px">
                                I agree to the
                                <a href="{{ route('terms') }}"
                                    class="text-indigo-600 hover:text-indigo-800 font-semibold transition-colors">
                                    Terms of Service
                                </a>
                            </label>
                        </div>
                        @error('agreed')
                            <p class="mt-1 text-red-500" style="font-size:10px">{{ $message }}</p>
                        @enderror

                        {{-- Submit --}}
                        <button type="submit" wire:loading.attr="disabled"
                            wire:loading.class="opacity-70 cursor-not-allowed"
                            class="fu8 login-btn w-full py-2.5 rounded-lg text-white font-bold flex items-center justify-center gap-2 mt-4"
                            style="font-size:13px; background: #2ab4c0;">
                            <span wire:loading.remove wire:target="register" class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                                </svg>
                                Create Account
                            </span>
                            <span wire:loading wire:target="register" class="flex items-center gap-2">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                </svg>
                                Creating account...
                            </span>
                        </button>

                    </form>
                    {{-- ── End Form ──────────────────────────────── --}}


                    {{-- Login link --}}
                    <p class="fu8 text-center text-gray-600 pb-1" style="font-size:11px">
                        Already have an account?
                        <a href="{{ route('login') }}" wire:navigate
                            class="text-indigo-600 hover:text-indigo-800 font-semibold transition-colors">
                            Login
                        </a>
                    </p>

                </div>

                {{-- Bottom gradient accent --}}
                <div class="h-0.5 w-full" style="background: #2ab4c0;"></div>
            </div>

            {{-- Footer --}}
            <p class="text-center text-gray-600 mt-4" style="font-size:10px">
                © {{ date('Y') }} FlightBook ·
                <a href="{{ route('privacy') }}" class="hover:text-gray-600 transition-colors">Privacy Policy</a> ·
                <a href="{{ route('terms') }}" class="hover:text-gray-600 transition-colors">Terms of Service</a>
            </p>

        </div>
    </div>

</div>