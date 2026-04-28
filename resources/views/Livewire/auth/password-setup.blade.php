<div x-data="{ showNewPassword: false, showConfirmPassword: false }">
    <div class="flex-1 flex flex-col min-h-screen items-start sm:items-center justify-center px-4 py-8">
        <div class="flex justify-center">
            <img src="{{ asset('assets/images/travelapp_logo.png') }}" alt="Logo" class="h-64 w-auto">
        </div>
        <div class="w-full max-w-sm">
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                <div class="h-1 w-full" style="background: #2ab4c0;"></div>

                <div class="px-6 py-6 space-y-4">
                    <div class="fu1">
                        <h1 class="text-lg font-bold text-gray-800">Create New Password</h1>
                        <p class="text-gray-600 mt-0.5" style="font-size:11px">For security, update your password before continuing.</p>
                    </div>

                    <form wire:submit="save" novalidate>
                        <div class="fu3 mt-4">
                            <label for="new_password" class="block text-gray-600 font-medium mb-1.5" style="font-size:11px">
                                New Password
                            </label>
                            <div class="relative">
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input id="new_password" :type="showNewPassword ? 'text' : 'password'" wire:model.defer="new_password" placeholder="••••••••"
                                    class="input-field pr-10 @error('new_password') border-red-400 bg-red-50 @enderror">
                                <button type="button" @click="showNewPassword = !showNewPassword"
                                    class="absolute inset-y-0 right-3 inline-flex items-center text-gray-400 hover:text-gray-600"
                                    :title="showNewPassword ? 'Hide password' : 'Show password'">
                                    <svg x-show="!showNewPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="showNewPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.042-3.368M9.88 9.88a3 3 0 104.24 4.24" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6.1 6.1A9.958 9.958 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.964 9.964 0 01-4.132 5.411M3 3l18 18" />
                                    </svg>
                                </button>
                            </div>
                            @error('new_password')
                                <p class="mt-1 text-red-500" style="font-size:10px">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="fu5 mt-4">
                            <label for="new_password_confirmation" class="block text-gray-600 font-medium mb-1.5" style="font-size:11px">
                                Re-enter New Password
                            </label>
                            <div class="relative">
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input id="new_password_confirmation" :type="showConfirmPassword ? 'text' : 'password'" wire:model.defer="new_password_confirmation" placeholder="••••••••"
                                    class="input-field pr-10 @error('new_password_confirmation') border-red-400 bg-red-50 @enderror">
                                <button type="button" @click="showConfirmPassword = !showConfirmPassword"
                                    class="absolute inset-y-0 right-3 inline-flex items-center text-gray-400 hover:text-gray-600"
                                    :title="showConfirmPassword ? 'Hide password' : 'Show password'">
                                    <svg x-show="!showConfirmPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="showConfirmPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.042-3.368M9.88 9.88a3 3 0 104.24 4.24" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6.1 6.1A9.958 9.958 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.964 9.964 0 01-4.132 5.411M3 3l18 18" />
                                    </svg>
                                </button>
                            </div>
                            @error('new_password_confirmation')
                                <p class="mt-1 text-red-500" style="font-size:10px">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" wire:loading.attr="disabled"
                            class="fu6 login-btn w-full rounded-lg px-4 py-2 text-sm font-semibold text-white flex items-center justify-center gap-2 mt-4"
                            style="background: #2ab4c0;">
                            <svg wire:loading wire:target="save" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                            </svg>
                            <span wire:loading.remove wire:target="save">Update Password</span>
                            <span wire:loading wire:target="save">Updating…</span>
                        </button>
                    </form>
                </div>

                <div class="h-0.5 w-full" style="background: #2ab4c0;"></div>
            </div>
        </div>
    </div>
</div>

