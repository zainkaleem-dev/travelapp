<div>
    @php
        $data = $results['data'] ?? [];
        $carriers = $results['dictionaries']['carriers'] ?? [];
        $outboundLabelDate = $departureDate ? \Carbon\Carbon::parse($departureDate)->format('D, M d') : '-';
        $inboundLabelDate = $returnDate ? \Carbon\Carbon::parse($returnDate)->format('D, M d') : '-';
    @endphp

    <div class="row">
        <div class="col-12 my-2">
            <div class="d-flex justify-content-between align-items-start">
                <div class="fw-bold">{{ $origin }} <i class="bi bi-arrow-right mx-2"></i> {{ $destination }}</div>
                <div><span class="font-small">Showing {{ count($data) }} results.</span></div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12 col-lg-6 mb-3 mb-lg-0">
            <div class="fw-bold">{{ $origin }} <i class="bi bi-arrow-right mx-2"></i> {{ $destination }}</div>
            <div class="font-small">{{ $outboundLabelDate }}</div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="fw-bold">{{ $destination }} <i class="bi bi-arrow-right mx-2"></i> {{ $origin }}</div>
            <div class="font-small">{{ $inboundLabelDate }}</div>
        </div>
    </div>

    <div class="row">
        @forelse($data as $offer)
            @php
                $outbound = $offer['itineraries'][0] ?? null;
                $inbound = $offer['itineraries'][1] ?? null;

                $outFirst = $outbound['segments'][0] ?? null;
                $outLast = !empty($outbound['segments']) ? end($outbound['segments']) : null;

                $inFirst = $inbound['segments'][0] ?? null;
                $inLast = !empty($inbound['segments']) ? end($inbound['segments']) : null;

                $carrierCode = $outFirst['carrierCode'] ?? ($inFirst['carrierCode'] ?? 'NA');
                $airlineName = $carriers[$carrierCode] ?? $carrierCode;
                $priceTotal = $offer['price']['total'] ?? 0;
            @endphp

            <div class="col-12 col-lg-6 mb-3">
                <div class="row g-0 border theme-border-radius theme-box-shadow p-2 align-items-center theme-bg-white">
                    <div class="col-12 col-md-4">
                        <div class="font-small fw-bold">{{ $airlineName }}</div>
                        <div class="font-small text-muted">{{ $carrierCode }}</div>
                        <a href="#" class="font-small text-primary">Flight Details</a>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="fw-bold">{{ $outFirst ? \Carbon\Carbon::parse($outFirst['departure']['at'])->format('H:i') : '-' }}</div>
                        <div class="font-small text-muted">{{ $outFirst['departure']['iataCode'] ?? '-' }}</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="fw-bold">{{ $outLast ? \Carbon\Carbon::parse($outLast['arrival']['at'])->format('H:i') : '-' }}</div>
                        <div class="font-small text-muted">{{ $outLast['arrival']['iataCode'] ?? '-' }}</div>
                    </div>
                    <div class="col-12 col-md-2 text-md-end mt-2 mt-md-0">
                        <div class="fw-bold text-primary">USD {{ number_format((float) $priceTotal) }}</div>
                        <button type="submit" class="btn-select btn btn-effect" onclick="window.location.href='{{ route('booking.review') }}';">
                            <span class="font-small">Select</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 mb-3">
                <div class="row g-0 border theme-border-radius theme-box-shadow p-2 align-items-center theme-bg-white">
                    <div class="col-12 col-md-4">
                        <div class="font-small fw-bold">{{ $airlineName }}</div>
                        <div class="font-small text-muted">{{ $carrierCode }}</div>
                        <a href="#" class="font-small text-primary">Flight Details</a>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="fw-bold">{{ $inFirst ? \Carbon\Carbon::parse($inFirst['departure']['at'])->format('H:i') : '-' }}</div>
                        <div class="font-small text-muted">{{ $inFirst['departure']['iataCode'] ?? '-' }}</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="fw-bold">{{ $inLast ? \Carbon\Carbon::parse($inLast['arrival']['at'])->format('H:i') : '-' }}</div>
                        <div class="font-small text-muted">{{ $inLast['arrival']['iataCode'] ?? '-' }}</div>
                    </div>
                    <div class="col-12 col-md-2 text-md-end mt-2 mt-md-0">
                        <div class="fw-bold text-primary">USD {{ number_format((float) $priceTotal) }}</div>
                        <button type="submit" class="btn-select btn btn-effect" onclick="window.location.href='{{ route('booking.review') }}';">
                            <span class="font-small">Select</span>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <h5 class="text-muted">No return flights found</h5>
            </div>
        @endforelse
    </div>
</div>
