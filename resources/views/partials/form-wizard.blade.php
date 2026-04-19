@if (in_array(request()->route()?->getName(), ['flights.list', 'additional.services', 'seating', 'passenger.details'], true))
    {{-- Form Wizard (frontend services) --}}
    @php
        $routeName = request()->route()?->getName();
        $activeStep = match ($routeName) {
            'flights.list' => 1,
            'additional.services' => 2,
            'seating' => 3,
            'passenger.details' => 4,
            default => 1,
        };

        // 4-step connector: fill up to active circle.
        // Steps are rendered in 4 equal parts (w-1/4), so step 1 circle center ~ 12.5%.
        $connectorPercent = match ($activeStep) {
            1 => 12.5,
            2 => 37.5,
            3 => 62.5,
            4 => 100,
            default => 12.5,
        };
    @endphp

    <div class="pt-4 pb-2 -order-1">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <div class="flex items-center justify-between gap-3 mb-3">
                <div>
                    <div class="text-sm font-bold text-gray-800">Service Wizard</div>
                    <div class="text-xs mt-0.5">
                        <span
                            class="{{ $activeStep === 1 ? 'font-bold text-[#000000]' : 'font-normal text-gray-500' }}">Flight
                            List</span>
                        <span class="text-gray-500"> / </span>
                        <span
                            class="{{ $activeStep === 2 ? 'font-bold text-[#000000]' : 'font-normal text-gray-500' }}">Additional
                            Services</span>
                        <span class="text-gray-500"> / </span>
                        <span
                            class="{{ $activeStep === 3 ? 'font-bold text-[#000000]' : 'font-normal text-gray-500' }}">Seating</span>
                        <span class="text-gray-500"> / </span>
                        <span
                            class="{{ $activeStep === 4 ? 'font-bold text-[#000000]' : 'font-normal text-gray-500' }}">Passenger
                            Details</span>
                    </div>
                </div>
            </div>

            <div class="relative">
                {{-- connector line --}}
                <div class="absolute left-0 right-0 top-5 h-[2px] bg-gray-200"></div>
                <div class="absolute left-0 top-5 h-[2px] bg-[#2ab4c0]" style="width: {{ $connectorPercent }}%"></div>

                <div class="flex items-start justify-between">
                    {{-- 1. Flight List --}}
                    <div class="flex flex-col items-center w-1/4">
                        <a href="{{ route('flights.list') }}"
                            class="w-10 h-10 rounded-full border-2 flex items-center justify-center z-10 transition-colors
                                {{ $activeStep === 1 ? 'bg-[#2ab4c0] border-[#2ab4c0] text-white' : 'bg-white border-gray-200 text-gray-400' }}">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M4 6h16" />
                                <path d="M4 12h16" />
                                <path d="M4 18h10" />
                            </svg>
                        </a>
                        <div
                            class="mt-2 text-[11px] {{ $activeStep === 1 ? 'font-bold text-[#2ab4c0]' : 'font-normal text-gray-600' }}">
                            Flight List
                        </div>
                    </div>

                    {{-- 2. Additional Services --}}
                    <div class="flex flex-col items-center w-1/4">
                        <div
                            class="w-10 h-10 rounded-full border-2 flex items-center justify-center z-10
                                {{ $activeStep === 2 ? 'bg-[#2ab4c0] border-[#2ab4c0] text-white' : 'bg-white border-gray-200 text-gray-400' }}">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M20 6H9l-2 4H20l-2 4H7l-2 4" />
                            </svg>
                        </div>
                        <div
                            class="mt-2 text-[11px] {{ $activeStep === 2 ? 'font-bold text-[#2ab4c0]' : 'font-normal text-gray-600' }}">
                            Additional Services</div>
                    </div>

                    {{-- 3. Seating --}}
                    <div class="flex flex-col items-center w-1/4">
                        <div
                            class="w-10 h-10 rounded-full border-2 flex items-center justify-center z-10
                                {{ $activeStep === 3 ? 'bg-[#2ab4c0] border-[#2ab4c0] text-white' : 'bg-white border-gray-200 text-gray-400' }}">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h10M7 11h10M7 15h10M7 19h10" />
                            </svg>
                        </div>
                        <div
                            class="mt-2 text-[11px] {{ $activeStep === 3 ? 'font-bold text-[#2ab4c0]' : 'font-normal text-gray-600' }}">
                            Seating</div>
                    </div>

                    {{-- 4. Passenger Details --}}
                    <div class="flex flex-col items-center w-1/4">
                        <div
                            class="w-10 h-10 rounded-full border-2 flex items-center justify-center z-10
                                {{ $activeStep === 4 ? 'bg-[#2ab4c0] border-[#2ab4c0] text-white' : 'bg-white border-gray-200 text-gray-400' }}">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                        </div>
                        <div
                            class="mt-2 text-[11px] {{ $activeStep === 4 ? 'font-bold text-[#2ab4c0]' : 'font-normal text-gray-600' }}">
                            Passenger Details</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif