{{--
    Anonymous Blade component: <x-seat :seat="$seat" wire:click="..." />
    File: resources/views/components/seat.blade.php

    $seat = [
        'id'      => '3A',
        'state'   => 'available' | 'occupied' | 'extra' | 'selected',
        'isExtra' => bool,
    ]
--}}
@props(['seat'])

@php
    $stateClasses = match($seat['state']) {
        'selected' => 'seat-selected',
        'occupied' => 'seat-occupied',
        'extra'    => 'seat-extra-legroom',
        default    => 'seat-available',
    };

    $isDisabled = $seat['state'] === 'occupied';
@endphp

<button
    {{ $attributes }}
    id="seat-{{ $seat['id'] }}"
    data-seat="{{ $seat['id'] }}"
    class="seat {{ $stateClasses }}"
    @disabled($isDisabled)
    title="{{ $seat['id'] }}"
>
    @if($seat['state'] === 'selected')
        ✓
    @endif
</button>
