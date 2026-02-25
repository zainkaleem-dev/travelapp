<div>
    @php
        $templatePath = resource_path('views/Livewire/pages/flights/listing-multicity.blade.php');
        $html = '';

        if (file_exists($templatePath)) {
            $raw = file_get_contents($templatePath);

            if (preg_match('/<div class="content-section">([\s\S]*?)(?:<!--\s*page footer section\s*-->|<footer\b)/i', $raw, $matches)) {
                $html = $matches[1];
            } elseif (preg_match('/<div class="filter-sec[\s\S]*$/i', $raw, $matches)) {
                $html = $matches[0];
            } else {
                $html = $raw;
            }

            $html = preg_replace('/<!doctype[\s\S]*?<body[^>]*>/i', '', $html);
            $html = preg_replace('/<\/body>[\s\S]*$/i', '', $html);
            $html = preg_replace('/<footer\b[\s\S]*$/i', '', $html);
            $html = preg_replace('/<script\b[\s\S]*?<\/script>/i', '', $html);
            $html = preg_replace('/\sdata-aos(?:-[a-z]+)?="[^"]*"/i', '', $html);
            $html = str_replace([' aos-init', ' aos-animate'], '', $html);
        }
    @endphp

    <div wire:ignore>
        {!! $html !!}
    </div>
</div>
