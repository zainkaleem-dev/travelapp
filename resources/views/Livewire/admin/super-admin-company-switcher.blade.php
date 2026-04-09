<div class="hidden sm:flex items-center gap-2 ml-auto mt-1">
    <div class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 shadow-sm">
        <div class="inline-flex items-center gap-1.5 text-[11px] font-black text-gray-600 whitespace-nowrap">
            <svg class="w-4 h-4 text-[#2ab4c0]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-8h6v8" />
            </svg>
            Company
        </div>

        <div class="h-5 w-px bg-gray-200"></div>

        <div class="min-w-[240px]">
            <select
                class="w-full bg-transparent text-sm font-semibold text-gray-900 outline-none"
                wire:change="switchCompany($event.target.value, '{{ request()->route()?->getName() }}')">
                <option value="0" @selected($companyId === 0)>Select company…</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}" @selected($companyId === (int) $company->id)>{{ $company->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
