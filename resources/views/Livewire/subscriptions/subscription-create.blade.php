<div class="px-1 py-1 w-full flex flex-col gap-3">

    {{-- ── Header Container ────────────────────────────────────────── --}}
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] flex items-center justify-between gap-4">
            <div>
                <h1 class="text-[21px] font-black text-gray-900 tracking-tight">Create Subscription</h1>
                <p class="text-[11px] font-bold text-gray-500 uppercase mt-0.5">Configure a new plan for a company</p>
            </div>
            <a href="{{ route('subscriptions.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-gray-600 hover:bg-gray-50 transition-colors shadow-sm uppercase"
                wire:navigate>
                Back to List
            </a>
        </div>
    </div>

    {{-- ── Form Container ───────────────────────────────────────────── --}}
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <form wire:submit="save">

            {{-- Plan Details Section --}}
            <div class="px-6 py-3.5 border-b border-gray-200 bg-gradient-to-r from-white to-[#f2feff]">
                <h2 class="text-[11px] font-black tracking-widest text-gray-400 uppercase">Plan Details</h2>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Company Selection --}}
                    <div>
                        <label class="field-label">Select Company <span class="text-red-500">*</span></label>
                        <div class="relative"
                            x-data="{ open: false, selected: @entangle('company_id').live, labels: @js(\App\Models\Company::orderBy('name')->pluck('name','id')) }"
                            @keydown.escape.window="open = false" @click.outside="open = false">
                            <button type="button" class="input-field flex items-center justify-between text-left"
                                @click="open = !open">
                                <span x-text="!selected ? '— Choose Company —' : (labels[selected] ?? '— Choose Company —')"></span>
                                <svg class="w-3.5 h-3.5 text-gray-500 transition-transform"
                                    :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-cloak x-show="open" x-transition.origin.top class="admin-menu-panel">
                                <button type="button" class="admin-menu-item" :class="{ 'is-active': !selected }"
                                    @click="selected = ''; open = false">— Choose Company —</button>
                                @foreach(\App\Models\Company::orderBy('name')->get() as $company)
                                    <button type="button" class="admin-menu-item"
                                        :class="{ 'is-active': String(selected) === '{{ $company->id }}' }"
                                        @click="selected = '{{ $company->id }}'; open = false">{{ $company->name }}</button>
                                @endforeach
                            </div>
                        </div>
                        @error('company_id') <p class="mt-1 text-[10px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                    </div>

                    {{-- Plan Name --}}
                    <div>
                        <label class="field-label">Plan Name <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="plan_name" class="input-field" placeholder="e.g. Premium Plan">
                        @error('plan_name') <p class="mt-1 text-[10px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                    </div>

                    {{-- Price --}}
                    <div>
                        <label class="field-label">Price ({{ session('currency', 'USD') }}) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" wire:model="price" class="input-field" placeholder="0.00">
                        @error('price') <p class="mt-1 text-[10px] font-bold text-red-500 uppercase">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Feature Entitlements Section --}}
            <div class="px-6 py-3.5 border-t border-b border-gray-200 bg-gradient-to-r from-white to-[#f2feff]">
                <h2 class="text-[11px] font-black tracking-widest text-gray-400 uppercase">Feature Entitlements</h2>
            </div>

            <div class="p-6">
                <div class="overflow-hidden rounded-lg border border-gray-100 shadow-sm">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-[#2ab4c0]">
                                <th class="px-6 py-2 text-start text-[11px] font-bold text-white uppercase tracking-wide rounded-ss-lg">Module / Feature</th>
                                <th class="px-6 py-2 text-end text-[11px] font-bold text-white uppercase tracking-wide w-40 rounded-se-lg">Enabled / Value</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 bg-white">

                            {{-- ── Toggle Features ── --}}
                            @foreach($definedFeatures as $key => $def)
                                @if($def['type'] === 'toggle')
                                    <tr class="group hover:bg-gray-50/60 transition-colors">
                                        <td class="px-6 py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-colors
                                                    {{ ($selectedFeatures[$key] ?? false) ? 'bg-[#2ab4c0]/10 text-[#2ab4c0]' : 'bg-gray-100 text-gray-400' }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <span class="text-[11px] font-bold {{ ($selectedFeatures[$key] ?? false) ? 'text-gray-900' : 'text-gray-500' }} transition-colors">
                                                        {{ $def['label'] }}
                                                    </span>
                                                    <p class="text-[10px] font-bold uppercase tracking-widest {{ ($selectedFeatures[$key] ?? false) ? 'text-[#2ab4c0]' : 'text-gray-400' }} mt-0.5 transition-colors">
                                                        {{ ($selectedFeatures[$key] ?? false) ? 'Enabled' : 'Disabled' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 text-end">
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox"
                                                    wire:click="$set('selectedFeatures.{{ $key }}', {{ !($selectedFeatures[$key] ?? false) ? 'true' : 'false' }})"
                                                    {{ ($selectedFeatures[$key] ?? false) ? 'checked' : '' }}
                                                    class="sr-only peer">
                                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer
                                                    peer-checked:after:translate-x-full peer-checked:after:border-white
                                                    after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                                    after:bg-white after:border-gray-300 after:border after:rounded-full
                                                    after:h-5 after:w-5 after:transition-all
                                                    peer-checked:bg-[#2ab4c0] shadow-inner transition-colors"></div>
                                            </label>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach

                            {{-- ── Quantity Limits (separator row) ── --}}
                            <tr class="bg-gray-50">
                                <td colspan="3" class="px-6 py-2">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Quantity Limits</span>
                                </td>
                            </tr>

                            @foreach($definedFeatures as $key => $def)
                                @if($def['type'] === 'quantity')
                                    <tr class="group hover:bg-gray-50/60 transition-colors">
                                        <td class="px-6 py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-500 flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <span class="text-[11px] font-bold text-gray-700">{{ $def['label'] }}</span>
                                                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mt-0.5">Set maximum allowed</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 text-end">
                                            <div class="flex items-center justify-end gap-1.5 bg-gray-50 rounded-lg p-1 border border-gray-100 w-fit ml-auto">
                                                <button type="button"
                                                    wire:click="$set('selectedFeatures.{{ $key }}', {{ 'Math.max(0, ' . ($selectedFeatures[$key] ?? 0) . ' - 1)' }})"
                                                    onclick="let el=this.nextElementSibling; el.value=Math.max(0,parseInt(el.value||0)-1); el.dispatchEvent(new Event('input'));"
                                                    class="w-7 h-7 rounded bg-white border border-gray-200 text-gray-500 hover:text-[#2ab4c0] flex items-center justify-center font-black transition-colors text-[11px]">−</button>
                                                <input type="number"
                                                    wire:model.lazy="selectedFeatures.{{ $key }}"
                                                    min="0"
                                                    class="input-field w-14 text-center text-[11px] font-black text-gray-800 !py-1"
                                                    placeholder="0">
                                                <button type="button"
                                                    onclick="let el=this.previousElementSibling; el.value=parseInt(el.value||0)+1; el.dispatchEvent(new Event('input'));"
                                                    class="w-7 h-7 rounded bg-white border border-gray-200 text-gray-500 hover:text-[#2ab4c0] flex items-center justify-center font-black transition-colors text-[11px]">+</button>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ── Submit Footer ─────────────────────────────────────── --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('subscriptions.index') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-[11px] font-semibold text-gray-600 hover:bg-gray-50 transition-colors shadow-sm uppercase"
                    wire:navigate>
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#2ab4c0] px-6 py-2 text-[11px] font-bold text-white hover:bg-[#229aa4] transition-colors shadow-sm uppercase">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    Create Subscription
                </button>
            </div>

        </form>
    </div>

</div>
