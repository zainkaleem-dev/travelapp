<div class="px-1 py-1 w-full">
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <!-- Unified Header -->
        <div class="px-6 py-3.5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-[21px] font-black text-gray-900 tracking-tight">View Subscription</h1>
                    <p class="text-[11px] font-bold text-gray-400 uppercase mt-1 tracking-wider">Details for {{ $subscription->company->name }}</p>
                </div>
                <a href="{{ route('subscriptions.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-[11px] font-bold text-gray-700 hover:bg-gray-50 uppercase tracking-wider transition-colors shadow-sm">
                    Back to List
                </a>
            </div>
        </div>

        <div class="p-6 space-y-8">
            {{-- Basic Information (Audit Log Style) --}}
            <div class="rounded-lg border border-gray-200 bg-white p-4 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Company</p>
                    <p class="mt-1 text-[11px] font-bold text-gray-900 uppercase">{{ $subscription->company->name }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Plan Name</p>
                    <p class="mt-1 text-[11px] font-bold text-gray-700 uppercase">{{ $subscription->plan_name }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Price</p>
                    <p class="mt-1 text-[11px] font-bold text-[#2ab4c0] uppercase">
                        {{ number_format($subscription->price, 2) }} {{ session('currency', 'USD') }}
                    </p>
                </div>
            </div>

            {{-- Feature List --}}
            <div class="rounded-lg border border-gray-200 bg-white p-4">
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-6 pb-2 border-b border-gray-50">Enabled Features & Limits</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($definedFeatures as $key => $def)
                        <div class="flex items-center justify-between p-3 rounded-lg border {{ ($subscription->features[$key] ?? false) ? 'border-[#2ab4c0]/20 bg-[#2ab4c0]/5' : 'border-gray-100 bg-gray-50/30' }}">
                            <span class="text-[11px] font-bold text-gray-700 uppercase">{{ $def['label'] }}</span>
                            @if($def['type'] === 'toggle')
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-black uppercase {{ ($subscription->features[$key] ?? false) ? 'bg-green-100 text-green-700' : 'bg-gray-300 text-gray-500' }}">
                                    {{ ($subscription->features[$key] ?? false) ? 'Enabled' : 'Disabled' }}
                                </span>
                            @else
                                <span class="text-[11px] font-black text-gray-900">{{ $subscription->features[$key] ?? 0 }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Actions Footer --}}
            <div class="flex items-center justify-end gap-3 pt-6 ">
                <!-- <a href="{{ route('subscriptions.edit', $subscription->id) }}"
                    class="inline-flex items-center justify-center rounded-lg bg-[#2ab4c0] px-4 py-2 text-[11px] font-bold text-white hover:bg-[#229aa4] uppercase tracking-wider transition-colors shadow-sm">
                    Edit Plan
                </a> -->
                <a href="{{ route('subscriptions.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-[11px] font-bold text-gray-700 hover:bg-gray-50 uppercase tracking-wider transition-colors">
                    Close
                </a>
            </div>
        </div>
    </div>
</div>
