<div>
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 flex items-center justify-between h-12 gap-3">
            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="{{ route('login') }}"
                    class="flex items-center gap-1 px-3 py-1.5 rounded-full bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition-colors">
                    Back
                </a>
            </div>

            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="{{ route('login') }}"
                    class="flex items-center gap-1.5 px-3 py-1.5 font-semibold bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    Login
                </a>
            </div>
        </div>
    </nav>

    <div class="bg-white border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4">
            <div class="hidden sm:block text-xs text-gray-400 py-1">Home / Forgot Password</div>
            <div class="flex items-center overflow-x-auto">
                <div class="flex items-center gap-1.5 px-4 sm:px-5 py-2.5 text-white font-semibold whitespace-nowrap"
                    style="background:#2ab4c0">
                    Forgot Password
                </div>
            </div>
        </div>
    </div>

    <div class="flex-1 flex items-start sm:items-center justify-center px-4 py-8">
        <div class="w-full max-w-sm">
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                <div class="h-1 w-full" style="background:linear-gradient(90deg,#2ab4c0,#6366f1)"></div>

                <div class="px-6 py-6 space-y-4">
                    <div class="fu1">
                        <h1 class="text-lg font-bold text-gray-800">Forgot Password</h1>
                        <p class="text-gray-400 mt-0.5" style="font-size:11px">
                            Enter your email address and we will send you a reset link.
                        </p>
                    </div>

                    @if($successMessage)
                        <div class="fu2 rounded-lg border border-green-200 bg-green-50 px-3 py-2 text-green-700"
                            style="font-size:11px">
                            {{ $successMessage }}
                        </div>
                    @endif

                    @if($errorMessage)
                        <div class="fu2 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-red-700"
                            style="font-size:11px">
                            {{ $errorMessage }}
                        </div>
                    @endif

                    <form wire:submit="sendResetLink" novalidate>
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
                                <input id="email" type="email" wire:model="email" placeholder="your@email.com"
                                    class="input-field @error('email') border-red-400 bg-red-50 @enderror" />
                            </div>
                            @error('email')
                                <p class="mt-1 text-red-500" style="font-size:10px">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                            class="fu6 login-btn w-full py-2.5 rounded-lg text-white font-bold flex items-center justify-center gap-2 mt-4"
                            style="font-size:13px">
                            Send Reset Link
                        </button>
                    </form>

                    <p class="fu8 text-center text-gray-500 pb-1" style="font-size:11px">
                        Remember your password?
                        <a href="{{ route('login') }}"
                            class="text-indigo-600 hover:text-indigo-800 font-semibold transition-colors">
                            Back to Login
                        </a>
                    </p>
                </div>

                <div class="h-0.5 w-full" style="background:linear-gradient(90deg,#6366f1,#2ab4c0)"></div>
            </div>
        </div>
    </div>
</div>