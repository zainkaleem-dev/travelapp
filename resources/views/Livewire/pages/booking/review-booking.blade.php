<div>

    <div class="pagewrap modify-search">
        <div class="head-wrapper">
            <!-- review booking flight-->
            <div class="flight-search">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-md-6 mb-4 mb-md-0">
                            <span class="fw-bold theme-text-white">Review your booking</span>
                        </div>
                        <div class="col-12 col-md-6 mb-4 mb-md-0">
                            <ul class="reviewStatus step2">
                                <li><span class="numbering completed">1</span><span
                                        class="reviewText grayText font12 ">Flight Selected</span></li>
                                <li><span class="numbering onpage">2</span><span
                                        class="reviewText grayText font12 active">Review</span></li>
                                <li><span class="numbering ">3</span><span class="reviewText grayText font12 ">Traveller
                                        &amp; Addons</span></li>
                                <li><span class="numbering ">4</span><span class="reviewText grayText font12 ">Make
                                        Payment</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-section">
        <!-- flight revew booking page -->
        <div class="review-flight py-3">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-9" data-aos="fade-up" data-aos-delay="200">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="fw-bold">Flight Details</div>
                            <div>
                                <a href="{{ route('home') }}" class="font-small text-decoration-none text-dark"><i class="bi bi-arrow-left mx-2"></i>Back To Search</a>
                            </div>
                        </div>
                        
                        @foreach ($flightOffer['itineraries'] as $index => $itinerary)
                            <!-- flight details section -->
                            <div class="theme-box-shadow theme-border-radius bg-light mb-3">
                                <div class="row border-bottom py-2 m-auto">
                                    <div class="col-8">
                                        <span class="fw-bold">
                                            <span class="text-uppercase">{{ $index === 0 ? 'Depart' : ($index === 1 ? 'Return' : 'Segment ' . ($index + 1)) }} - </span>
                                            <span>
                                                {{ $itinerary['segments'][0]['departure']['iataCode'] }} 
                                                To 
                                                {{ end($itinerary['segments'])['arrival']['iataCode'] }}
                                            </span>
                                        </span>
                                    </div>
                                    <div class="col-4 text-end">
                                        <span class="font-small">Fare Rules</span>
                                        <i class="bi bi-info-circle-fill pl-1"></i>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="px-3">
                                            @foreach ($itinerary['segments'] as $segmentIndex => $segment)
                                                @if ($segmentIndex > 0)
                                                    <div class="row border-top py-2 text-center">
                                                        <div class="col-12">
                                                            <div class="border d-inline-block theme-border-radius font-small p-2 bg-white">
                                                                Transfer at {{ $segment['departure']['iataCode'] }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                <ul class="row py-3 mb-0">
                                                    <li class="col-12 pb-3">
                                                        <div class="float-start">
                                                            <img src="https://pics.avs.io/al_l/40/40/{{ $segment['carrierCode'] }}.png" alt="{{ $segment['carrierCode'] }}" style="width: 32px;">
                                                        </div>
                                                        <div class="float-start ms-2">
                                                            <div class="font-medium">{{ $dictionaries['carriers'][$segment['carrierCode']] ?? $segment['carrierCode'] }}</div>
                                                            <div class="font-small">{{ $segment['carrierCode'] }}-{{ $segment['number'] }}</div>
                                                        </div>
                                                        <div class="float-start rounded theme-bg-secondary theme-text-white font-medium px-2 ms-5">
                                                            Class - {{ $flightOffer['travelerPricings'][0]['fareDetailsBySegment'][$index + $segmentIndex]['cabin'] ?? 'Economy' }}
                                                        </div>
                                                        <div class="font-medium float-start mx-5">
                                                            {{ $flightOffer['travelerPricings'][0]['fareOption'] ?? 'Standard' }}
                                                        </div>
                                                        <div class="font-medium float-start mx-5">
                                                            {{ $flightOffer['lastTicketingDate'] ?? '' }}
                                                        </div>
                                                    </li>
                                                    <li class="col-12 col-md-6 col-lg-3 pb-3">
                                                        <div class="font-medium fw-bold text-uppercase">{{ $segment['departure']['iataCode'] }}</div>
                                                        <div class="font-medium fw-bold">
                                                            {{ \Carbon\Carbon::parse($segment['departure']['at'])->format('H:i') }} | 
                                                            <span class="fw-normal">{{ \Carbon\Carbon::parse($segment['departure']['at'])->format('D, M d') }}</span>
                                                        </div>
                                                        <div class="font-small">Terminal {{ $segment['departure']['terminal'] ?? 'N/A' }}</div>
                                                    </li>
                                                    <li class="col-12 col-md-6 col-lg-3 pb-3">
                                                        <div class="float-start"><i class="bi bi-clock pe-2 fs-6"></i></div>
                                                        <div class="float-start"> 
                                                            <span class="font-medium d-block">{{ str_replace(['PT', 'H', 'M'], ['', 'h ', 'm'], $segment['duration']) }}</span>
                                                            <span class="font-small d-block">{{ $segment['numberOfStops'] == 0 ? 'Non Stop' : $segment['numberOfStops'] . ' Stop(s)' }}</span>
                                                            <span class="font-small d-block">Aircraft: {{ $segment['aircraft']['code'] ?? '' }}</span>
                                                        </div>
                                                    </li>
                                                    <li class="col-12 col-md-6 col-lg-3 pb-3">
                                                        <div class="font-medium fw-bold text-uppercase">{{ $segment['arrival']['iataCode'] }}</div>
                                                        <div class="font-medium fw-bold">
                                                            {{ \Carbon\Carbon::parse($segment['arrival']['at'])->format('H:i') }} | 
                                                            <span class="fw-normal">{{ \Carbon\Carbon::parse($segment['arrival']['at'])->format('D, M d') }}</span>
                                                        </div>
                                                        <div class="font-small">Terminal {{ $segment['arrival']['terminal'] ?? 'N/A' }}</div>
                                                    </li>
                                                    <li class="col-12 col-md-6 col-lg-3 pb-3">
                                                        <span class="font-small text-uppercase fw-bold"> <i class="bi bi-briefcase me-2 fs-6"></i> Baggage </span>
                                                        <span class="font-small d-block">CHECK-IN : {{ $flightOffer['travelerPricings'][0]['fareDetailsBySegment'][$index + $segmentIndex]['includedCheckedBags']['quantity'] ?? 1 }} {{ $flightOffer['travelerPricings'][0]['fareDetailsBySegment'][$index + $segmentIndex]['includedCheckedBags']['weightUnit'] ?? 'PC' }}</span>
                                                        <span class="font-small d-block">CABIN : 7 Kgs</span>
                                                    </li>
                                                </ul>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- travel insurance section -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="fw-bold">Add-ons</div>
                        </div>
                        <div class="theme-box-shadow theme-border-radius bg-light mb-3 p-3">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('assets/images/icons/travel-secure-icon.png') }}" alt="Travel Secure">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <span class="fw-bold">Secure your trip</span>
                                    <p class="font-small mb-0">See all the benefits you get for just $ 159 (18% GST included)</p>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex flex-column">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                                        <label class="form-check-label font-medium" for="flexRadioDefault1">
                                            Yes, secure my trip, I agree to the <a href="#">Terms & Conditions</a> & <a href="#">Good Health</a> terms, and confirm all passengers are between 2 to 70 years of age
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
                                        <label class="form-check-label font-medium" for="flexRadioDefault2">
                                            No, I do not wish to secure my trip
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-3" data-aos="fade-down" data-aos-delay="300">
                        <div class="fw-bold mb-3">Fare Details</div>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="theme-box-shadow theme-border-radius bg-light">
                                    <ul class="">
                                        <!-- base fare section -->
                                        <li class="border-bottom p-3 font-medium">
                                            <a class="d-flex justify-content-between theme-text-accent-one" data-bs-toggle="collapse" href="#baseFare" role="button" aria-expanded="false" aria-controls="baseFare">
                                                <span class="font-medium fw-bold">Base Fare</span>
                                                <span><i class="bi bi-plus-circle-fill theme-text-accent-two"></i></span>
                                            </a>
                                            <div class="collapse show" id="baseFare">
                                                <div class="d-flex justify-content-between pt-3">
                                                    <span class="font-medium">Adult(s) ({{ count($flightOffer['travelerPricings']) }} X {{ $flightOffer['price']['currency'] }} {{ number_format($flightOffer['travelerPricings'][0]['price']['base']) }})</span>
                                                    <span class="font-medium">
                                                        {{ $flightOffer['price']['currency'] }}
                                                        <span class="fw-normal">{{ number_format($flightOffer['price']['base']) }}</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                        <!-- other charge fare -->
                                        <li class="border-bottom p-3 font-medium">
                                            <a class="d-flex justify-content-between theme-text-accent-one" data-bs-toggle="collapse" href="#otherCharges" role="button" aria-expanded="false" aria-controls="otherCharges">
                                                <span class="font-medium fw-bold">Taxes & Fees</span>
                                                <span><i class="bi bi-plus-circle-fill theme-text-accent-two"></i></span>
                                            </a>
                                            <div class="collapse show" id="otherCharges">
                                                @foreach ($flightOffer['price']['fees'] ?? [] as $fee)
                                                    <div class="d-flex justify-content-between pt-3">
                                                        <span class="font-medium">{{ $fee['type'] }}</span>
                                                        <span class="font-medium">
                                                            {{ $flightOffer['price']['currency'] }}
                                                            <span class="fw-normal">{{ number_format($fee['amount']) }}</span>
                                                        </span>
                                                    </div>
                                                @endforeach
                                                <div class="d-flex justify-content-between pt-3">
                                                    <span class="font-medium">Other Taxes</span>
                                                    <span class="font-medium">
                                                        {{ $flightOffer['price']['currency'] }}
                                                        <span class="fw-normal">{{ number_format($flightOffer['price']['total'] - $flightOffer['price']['base'] - array_sum(array_column($flightOffer['price']['fees'] ?? [], 'amount'))) }}</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                        <!-- grand total charge fare -->
                                        <li class="border-bottom p-3 font-medium">
                                            <div class="d-flex justify-content-between">
                                                <span class="fs-6 fw-bold">Grand Total</span>
                                                <span class="fs-6">
                                                    {{ $flightOffer['price']['currency'] }}
                                                    <span class="fw-bold">{{ number_format($flightOffer['price']['grandTotal'] ?? $flightOffer['price']['total']) }}</span>
                                                </span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="theme-box-shadow theme-border-radius bg-light p-3 font-small">
                                    Cancellation & Date change charges <a href="#" class="">More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- button section -->
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-effect btn-book"
                            onclick="window.location.href='{{ route('booking.traveller_details') }}';">Continue</button>
                    </div>
                </div>
            </div>
        </div>


    </div>

</div>