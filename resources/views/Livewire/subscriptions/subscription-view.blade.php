<div class="px-1 py-1 w-full">
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm mb-4">
        <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-[21px] font-black text-gray-900 tracking-tight">View Subscription</h1>
                    <p class="text-[11px] font-bold text-gray-500 uppercase mt-1">Details for {{ $subscription->company->name }}</p>
                </div>
                <div class="flex items-center gap-3">

                    <a href="{{ route('subscriptions.index') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-gray-600 hover:bg-gray-50 transition-colors shadow-sm uppercase"
                        wire:navigate>
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Basic Info --}}
                <div class="space-y-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Company</label>
                        <p class="text-[13px] font-bold text-gray-900 uppercase">{{ $subscription->company->name }}</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Plan Name</label>
                        <p class="text-[13px] font-bold text-gray-900 uppercase">{{ $subscription->plan_name }}</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Price</label>
                        <p class="text-[13px] font-bold text-[#2ab4c0] uppercase">{{ number_format($subscription->price, 2) }} {{ session('currency', 'USD') }}</p>
                    </div>
                </div>

                {{-- Feature List --}}
                <div class="md:col-span-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-4 block">Enabled Features & Limits</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($definedFeatures as $key => $def)
                            <div class="flex items-center justify-between p-3 rounded-lg border {{ ($subscription->features[$key] ?? false) ? 'border-[#2ab4c0]/20 bg-[#2ab4c0]/5' : 'border-gray-100 bg-gray-50/30' }}">
                                <span class="text-[11px] font-bold text-gray-700 uppercase">{{ $def['label'] }}</span>
                                @if($def['type'] === 'toggle')
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-bold uppercase {{ ($subscription->features[$key] ?? false) ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-500' }}">
                                        {{ ($subscription->features[$key] ?? false) ? 'Enabled' : 'Disabled' }}
                                    </span>
                                @else
                                    <span class="text-[11px] font-black text-gray-900">{{ $subscription->features[$key] ?? 0 }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
