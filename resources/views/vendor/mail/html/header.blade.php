@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
    <img
        src="{{ asset('assets/images/travelapp_logo.png') }}"
        class="logo"
        alt="{{ config('app.name', 'TravelApp') }} Logo"
        style="max-height:40px; width:auto;"
    >

    @if (trim($slot) !== 'Laravel' && trim($slot) !== '')
        {!! $slot !!}
    @endif
</a>
</td>
</tr>
