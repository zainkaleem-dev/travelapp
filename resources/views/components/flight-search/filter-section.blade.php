@props([
    'title'  => '',
    'border' => true,
])

<div @class(['filter-section px-3 py-3' => $border, 'px-3 py-3' => !$border])
     x-data="{ open: true }">

    <button @click="open = !open" class="flex items-center justify-between w-full">
        <p class="text-xs font-semibold text-gray-600">{{ $title }}</p>
        <svg class="w-3.5 h-3.5 text-gray-400 transition-transform" :class="{ 'rotate-180': !open }"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div x-show="open" x-collapse class="mt-2 grid grid-cols-1 gap-1.5">
        {{ $slot }}
    </div>
</div>
