<div>
    <div class="bg-indigo-600 text-white py-1.5 text-center text-xs relative">
        <span>Up to 20% discount with early booking! Sign up now and benefit from the offer.</span>
    </div>

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 flex items-center justify-between h-12 gap-3">
            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="{{ route('login') }}" class="flex items-center gap-1 px-3 py-1.5 rounded-full bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition-colors">
                    Back
                </a>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="{{ route('login') }}" class="flex items-center gap-1.5 px-3 py-1.5 font-semibold bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    Login
                </a>
            </div>
        </div>
    </nav>

    <div class="bg-white border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4">
            <div class="hidden sm:block text-xs text-gray-400 py-1">Home / Reset Password</div>
            <div class="flex items-center overflow-x-auto">
                <div class="flex items-center gap-1.5 px-4 sm:px-5 py-2.5 text-white font-semibold whitespace-nowrap" style="background:#2ab4c0">
                    Reset Password
                </div>
            </div>
        </div>
    </div>

    <div class="flex-1 flex items-start sm:items-center justify-center px-4 py-8">
        <div class="w-full max-w-sm">
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                <div class="h-1 w-full" style="background:linear-gradient(90deg,#2ab4c0,#6366f1)"></div>

                <div class="px-6 py-6 space-y-4">
                    <div>
                        <h1 class="text-lg font-bold text-gray-800">Reset Password</h1>
                        <p class="text-gray-400 mt-0.5" style="font-size:11px">
                            Enter your email and choose a new password.
                        </p>
                    </div>

                    @if($successMessage)
                        <div class="rounded-lg border border-green-200 bg-green-50 px-3 py-2 text-green-700" style="font-size:11px">
                            {{ $successMessage }}
                        </div>
                    @endif

                    @if($errorMessage)
                        <div class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-red-700" style="font-size:11px">
                            {{ $errorMessage }}
                        </div>
                    @endif

                    <form wire:submit="resetPassword" novalidate class="space-y-4">
                        <div>
                            <label class="block text-gray-600 font-medium mb-1.5" style="font-size:11px">Email</label>
                            <input
                                type="email"
                                wire:model="email"
                                placeholder="your@email.com"
                                class="input-field @error('email') border-red-400 bg-red-50 @enderror"
                            />
                            @error('email')
                                <p class="mt-1 text-red-500" style="font-size:10px">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-600 font-medium mb-1.5" style="font-size:11px">New Password</label>
                            <input
                                type="password"
                                wire:model="password"
                                placeholder="Minimum 8 characters"
                                class="input-field @error('password') border-red-400 bg-red-50 @enderror"
                            />
                            @error('password')
                                <p class="mt-1 text-red-500" style="font-size:10px">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-600 font-medium mb-1.5" style="font-size:11px">Confirm Password</label>
                            <input
                                type="password"
                                wire:model="password_confirmation"
                                placeholder="Re-enter password"
                                class="input-field"
                            />
                        </div>

                        <button type="submit" class="login-btn w-full py-2.5 rounded-lg text-white font-bold flex items-center justify-center gap-2 mt-2" style="font-size:13px">
                            Reset Password
                        </button>
                    </form>

                    <p class="text-center text-gray-500 pb-1" style="font-size:11px">
                        <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold transition-colors">
                            Back to Login
                        </a>
                    </p>
                </div>

                <div class="h-0.5 w-full" style="background:linear-gradient(90deg,#6366f1,#2ab4c0)"></div>
            </div>
        </div>
    </div>
</div>
