<div>
    <!-- flight result page - oneway start -->
    <div class="srp py-2">
        <div class="container">
            @php
                $currentResults = $flightResults;
                $count = isset($currentResults['data']) ? count($currentResults['data']) : 0;
            @endphp

            <div class="row">
                <div class="col-12 my-2">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-bold">
                                {{ $oneWayOrigin }} <i class="bi bi-arrow-right mx-2"></i> {{ $oneWayDestination }}
                            </div>
                            <div class="mb-1 font-small">
                                {{ \Carbon\Carbon::parse($oneWayDepartureDate)->format('D, M d') }}
                            </div>
                        </div>
                        <div>
                            <span class="font-small">Showing {{ $count }} results.</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 mb-3">
                    <div class="row g-0 border theme-border-radius p-2 theme-bg-accent-three d-none d-md-flex">
                        <div class="col-md-3"><span class="font-small fw-bold">Airline</span></div>
                        <div class="col-md-2"><span class="font-small fw-bold">Depart</span></div>
                        <div class="col-md-2"><span class="font-small fw-bold">Duration</span></div>
                        <div class="col-md-2"><span class="font-small fw-bold">Arrive</span></div>
                        <div class="col-md-3 text-md-center">
                            <span class="font-small fw-bold">Price<i class="bi bi-arrow-up"></i> <input type="checkbox"
                                    class="cursor-pointer"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                @if(isset($currentResults['data']) && count($currentResults['data']) > 0)
                    @foreach($currentResults['data'] as $offer)
                        @php
                            $itinerary = $offer['itineraries'][0];
                            $firstSegment = $itinerary['segments'][0];
                            $lastSegment = end($itinerary['segments']);
                            $carrierCode = $firstSegment['carrierCode'];
                            $airlineName = $currentResults['dictionaries']['carriers'][$carrierCode] ?? $carrierCode;
                            $duration = str_replace(['PT', 'H', 'M'], ['', 'h ', 'm'], $itinerary['duration']);
                        @endphp
                        <div class="col-12 mb-3">
                            <div
                                class="row g-0 border theme-border-radius theme-box-shadow p-2 align-items-center theme-bg-white">
                                <div class="col-12 col-md-3">
                                    <div class="d-flex">
                                        <div>
                                            <img src="assets/images/icons/{{ $carrierCode }}.jpg"
                                                class="img-fluid theme-border-radius" style="width: 40px;"
                                                alt="{{ $airlineName }}"
                                                onerror="this.onerror=null;this.src='data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs='">
                                        </div>
                                        <div class="d-flex flex-column ms-2">
                                            <span
                                                class="font-small d-inline-flex mb-0 align-middle fw-bold">{{ $airlineName }}</span>
                                            <span
                                                class="font-small d-inline-flex mb-0 align-middle text-muted">{{ $carrierCode }}-{{ $firstSegment['number'] }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="#" class="font-small text-primary" data-bs-target="#flightDetailsModal"
                                            data-bs-toggle="modal">Flight Details</a>
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="fw-bold">
                                        {{ \Carbon\Carbon::parse($firstSegment['departure']['at'])->format('H:i') }}</div>
                                    <div class="font-small text-muted">{{ $firstSegment['departure']['iataCode'] }}</div>
                                </div>
                                <div class="col-4 col-md-2 text-center">
                                    <div class="font-small">{{ $duration }}</div>
                                    <div class="position-relative my-1">
                                        <hr class="m-0">
                                        <span
                                            class="position-absolute top-50 start-50 translate-middle bg-white px-1 font-xsmall text-muted">
                                            {{ count($itinerary['segments']) - 1 }} Stop(s)
                                        </span>
                                    </div>
                                    <div
                                        class="font-small {{ count($itinerary['segments']) == 1 ? 'text-success' : 'text-warning' }}">
                                        {{ count($itinerary['segments']) == 1 ? 'Non Stop' : 'Connecting' }}
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="fw-bold">
                                        {{ \Carbon\Carbon::parse($lastSegment['arrival']['at'])->format('H:i') }}</div>
                                    <div class="font-small text-muted">{{ $lastSegment['arrival']['iataCode'] }}</div>
                                </div>
                                <div class="col-12 col-md-3 text-center mt-md-0 mt-2">
                                    <div class="fw-bold h5 mb-1 text-primary">
                                        <span
                                            class="font-small text-muted me-1">USD</span>{{ number_format($offer['price']['total']) }}
                                    </div>
                                    <button type="button" class="btn-select btn btn-effect"
                                        wire:click.prevent="$parent.selectFlight({{ json_encode($offer) }})">
                                        <span class="font-small">Select</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 text-center py-5">
                        <div class="mb-3">
                            <i class="bi bi-airplane text-muted opacity-25" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="text-muted">No flights found</h5>
                        <p class="text-muted small">Try adjusting your search criteria or dates.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>