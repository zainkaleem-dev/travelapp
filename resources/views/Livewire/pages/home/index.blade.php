<div class="home-page head-wrapper">

    <div class="flight-search" wire:ignore.self>
        <div class="container">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs border-0" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="oneway-tab" data-bs-toggle="tab" data-bs-target="#oneway"
                        type="button" role="tab" aria-controls="oneway" aria-selected="true">
                        <span class="d-inline-block icon-20 rounded-circle bg-white align-middle me-2"></span>One-way
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="return-tab" data-bs-toggle="tab" data-bs-target="#return" type="button"
                        role="tab" aria-controls="return" aria-selected="false" tabindex="-1">
                        <span class="d-inline-block icon-20 rounded-circle bg-white align-middle me-2"></span>Return
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="multiCity-tab" data-bs-toggle="tab" data-bs-target="#multiCity"
                        type="button" role="tab" aria-controls="multiCity" aria-selected="false" tabindex="-1">
                        <span class="d-inline-block icon-20 rounded-circle bg-white align-middle me-2"></span>Multi-city
                    </button>
                </li>
            </ul>
            <!-- Tab content -->
            <div class="tab-content">
                <!-- oneway search -->
                <div id="oneway" class="tab-pane active" role="tabpanel" aria-labelledby="oneway-tab">
                    <div class="row">
                        <div class="col-12">
                            <div class="search-pan row mx-0 theme-border-radius">
                                <div class="col-12 col-lg-4 col-xl-2 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-2">
                                    <div class="form-group">
                                        <i class="bi bi-geo-alt-fill position-absolute h2 icon-pos"></i>
                                        <input type="text" class="form-control ps-5" id="onewayOrigin"
                                            placeholder="Origin" wire:model.live="oneWayOrigin"
                                            wire:key="oneway-origin">
                                        <button class="pos-swap"><i class="bi bi-arrow-left-right pl-1"></i></button>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4 col-xl-2 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-2">
                                    <div class="form-group">
                                        <i class="bi bi-geo-alt-fill position-absolute h2 icon-pos"></i>
                                        <input type="text" class="form-control ps-5" id="onewayDestination"
                                            placeholder="Destination" wire:model.live="oneWayDestination"
                                            wire:key="oneway-destination">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4 col-xl-3 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-0 pe-xl-2">
                                    <div class="form-control form-group d-flex">
                                        <i class="bi bi-calendar3 position-absolute h2 icon-pos"></i>
                                        <span class="dep-date-input" wire:ignore>
                                            <input type="text" class="cal-input" placeholder="Depart Date"
                                                id="datepicker" wire:model.live="oneWayDepartureDate" autocomplete="off"
                                                wire:key="oneway-departure">
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 col-xl-3 ps-0 mb-2 mb-lg-0 mb-xl-0 pe-0 pe-lg-2">
                                    <div class="dropdown" id="myDD">
                                        <button class="dropdown-toggle form-control" type="button"
                                            id="travellerInfoOneway" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-person-lines-fill position-absolute h2 icon-pos"></i>
                                            <span
                                                class="text-truncate">{{ $oneWayAdults + $oneWayChildren + $oneWayInfants }}
                                                Traveller(s), {{ $oneWayTravelClass }} </span>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="travellerInfoOneway">
                                            <ul class="drop-rest">
                                                <li>
                                                    <div class="d-flex">Select Adults</div>
                                                    <div class="ms-auto input-group plus-minus-input">
                                                        <div class="input-group-button">
                                                            <button type="button" class="circle"
                                                                wire:click="decreaseAdults">
                                                                <i class="bi bi-dash"></i>
                                                            </button>
                                                        </div>
                                                        <input class="input-group-field" type="number"
                                                            wire:model="oneWayAdults" readonly>
                                                        <div class="input-group-button">
                                                            <button type="button" class="circle"
                                                                wire:click="increaseAdults">
                                                                <i class="bi bi-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="d-flex">Select Child</div>
                                                    <div class="ms-auto input-group plus-minus-input">
                                                        <div class="input-group-button">
                                                            <button type="button" class="circle"
                                                                wire:click="decreaseChildren">
                                                                <i class="bi bi-dash"></i>
                                                            </button>
                                                        </div>
                                                        <input class="input-group-field" type="number"
                                                            wire:model="oneWayChildren" readonly>
                                                        <div class="input-group-button">
                                                            <button type="button" class="circle"
                                                                wire:click="increaseChildren">
                                                                <i class="bi bi-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="d-flex">Select Infants</div>
                                                    <div class="ms-auto input-group plus-minus-input">
                                                        <div class="input-group-button">
                                                            <button type="button" class="circle"
                                                                wire:click="decreaseInfants">
                                                                <i class="bi bi-dash"></i>
                                                            </button>
                                                        </div>
                                                        <input class="input-group-field" type="number"
                                                            wire:model="oneWayInfants" readonly>
                                                        <div class="input-group-button">
                                                            <button type="button" class="circle"
                                                                wire:click="increaseInfants">
                                                                <i class="bi bi-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="class" value="Economy"
                                                            wire:model="oneWayTravelClass" class="me-2">Economy </label>
                                                </li>
                                                <li>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="class" value="Special"
                                                            wire:model="oneWayTravelClass" class="me-2">Premium Economy
                                                    </label>
                                                </li>
                                                <li>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="class" value="Business"
                                                            wire:model="oneWayTravelClass" class="me-2">Business
                                                    </label>
                                                </li>
                                                <li>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="class" value="First"
                                                            wire:model="oneWayTravelClass" class="me-2">First Class
                                                    </label>
                                                </li>
                                                <li>
                                                    <button type="button" class="btn btn" onclick="">Done</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 col-xl-2 px-0">
                                    <button type="submit" class="btn btn-search" wire:click.prevent="searchFlights"
                                        wire:loading.attr="disabled" wire:target="searchFlights">
                                        <span class="fw-bold" wire:loading.remove
                                            wire:target="searchFlights">Search</span>
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
                                            wire:loading wire:target="searchFlights"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-4">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-check-inline">
                                        <label class="check-wrap">Refundable Flights
                                            <input type="checkbox">
                                            <span class="checkmark"></span> </label>
                                    </div>
                                    <div class="form-check-inline">
                                        <label class="check-wrap"> Non Stop Flights
                                            <input type="checkbox">
                                            <span class="checkmark"></span> </label>
                                    </div>
                                    <div class="form-check-inline">
                                        <label class="check-wrap"> GDS Special Return
                                            <input type="checkbox">
                                            <span class="checkmark"></span> </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Return search -->
                <div id="return" class="tab-pane fade" role="tabpanel" aria-labelledby="return-tab">
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="search-pan row mx-0 theme-border-radius">
                                <div class="col-12 col-lg-4 col-xl-2 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-2">
                                    <div class="form-group">
                                        <i class="bi bi-geo-alt-fill position-absolute h2 icon-pos"></i>
                                        <input type="text" class="form-control ps-5" id="returnOrigin"
                                            placeholder="Origin" wire:model.live="returnOrigin">
                                        <button class="pos-swap"><i class="bi bi-arrow-left-right pl-1"></i></button>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4 col-xl-2 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-2">
                                    <div class="form-group">
                                        <i class="bi bi-geo-alt-fill position-absolute h2 icon-pos"></i>
                                        <input type="text" class="form-control ps-5" id="returnDestination"
                                            placeholder="Destination" wire:model.live="returnDestination">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4 col-xl-3 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-0 pe-xl-2">
                                    <div class="form-control form-group d-flex">
                                        <i class="bi bi-calendar3 position-absolute h2 icon-pos"></i>
                                        <span class="dep-date-input">
                                            <input type="text" class="cal-input hasDatepicker" placeholder="Depart Date"
                                                id="datepicker1">
                                        </span>
                                        <span class="arv-date-input ms-2">
                                            <input type="text" class="cal-input" placeholder="Return Date"
                                                id="datepickerNull">
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 col-xl-3 ps-0 mb-2 mb-lg-0 mb-xl-0 pe-0 pe-lg-2">
                                    <div class="dropdown" id="myDDReturn">
                                        <button class="dropdown-toggle form-control" type="button"
                                            id="travellerInfoReturn" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-person-lines-fill position-absolute h2 icon-pos"></i>
                                            <span class="text-truncate">1 Traveller(s), Economy </span>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="travellerInfoReturn">
                                            <ul class="drop-rest">
                                                <li>
                                                    <div class="d-flex">Select Adults</div>
                                                    <div class="ms-auto input-group plus-minus-input">
                                                        <div class="input-group-button">
                                                            <button type="button" class="circle" data-quantity="minus"
                                                                data-field="onewayAdult">
                                                                <i class="bi bi-dash"></i>
                                                            </button>
                                                        </div>
                                                        <input class="input-group-field" type="number"
                                                            name="onewayAdult" value="0">
                                                        <div class="input-group-button">
                                                            <button type="button" class="circle" data-quantity="plus"
                                                                data-field="onewayAdult">
                                                                <i class="bi bi-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="d-flex">Select Child</div>
                                                    <div class="ms-auto input-group plus-minus-input">
                                                        <div class="input-group-button">
                                                            <button type="button" class="circle"
                                                                wire:click="decreaseChildren">
                                                                <i class="bi bi-dash"></i>
                                                            </button>
                                                        </div>
                                                        <input class="input-group-field" type="number"
                                                            wire:model="oneWayChildren" readonly>
                                                        <div class="input-group-button">
                                                            <button type="button" class="circle"
                                                                wire:click="increaseChildren">
                                                                <i class="bi bi-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="d-flex">Select Infants</div>
                                                    <div class="ms-auto input-group plus-minus-input">
                                                        <div class="input-group-button">
                                                            <button type="button" class="circle" data-quantity="minus"
                                                                data-field="onewayInfant">
                                                                <i class="bi bi-dash"></i>
                                                            </button>
                                                        </div>
                                                        <input class="input-group-field" type="number"
                                                            name="onewayInfant" value="0">
                                                        <div class="input-group-button">
                                                            <button type="button" class="circle" data-quantity="plus"
                                                                data-field="onewayInfant">
                                                                <i class="bi bi-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="class" value="Economy"
                                                            class="me-2">Economy </label>
                                                </li>
                                                <li>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="class" value="Special"
                                                            class="me-2">Premium Economy </label>
                                                </li>
                                                <li>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="class" value="Business"
                                                            class="me-2">Business </label>
                                                </li>
                                                <li>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="class" value="First"
                                                            class="me-2">First Class </label>
                                                </li>
                                                <li>
                                                    <button type="button" class="btn btn" onclick="">Done</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 col-xl-2 px-0">
                                    <button type="submit" class="btn btn-search"
                                        onclick="window.location.href='flight-listing-round-trip.html';">
                                        <span class="fw-bold">Search</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Multicity search -->
                <div id="multiCity" class="tab-pane fade" role="tabpanel" aria-labelledby="multiCity-tab">
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="search-pan row mx-0 theme-border-radius">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12 col-lg-6 col-xl-3 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-2">
                                            <div class="form-group">
                                                <i class="bi bi-geo-alt-fill position-absolute h2 icon-pos"></i>
                                                <input type="text" class="form-control ps-5" id="multiOrigin"
                                                    placeholder="Origin" wire:model.live="multiCitySegments.0.origin">
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-6 col-xl-3 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-0 pe-xl-2">
                                            <div class="form-group">
                                                <i class="bi bi-geo-alt-fill position-absolute h2 icon-pos"></i>
                                                <input type="text" class="form-control ps-5" id="multiDestination"
                                                    placeholder="Destination"
                                                    wire:model.live="multiCitySegments.0.destination">
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-6 col-xl-2 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-2 pe-xl-2">
                                            <div class="form-control form-group d-flex">
                                                <i class="bi bi-calendar3 position-absolute h2 icon-pos"></i>
                                                <span class="dep-date-input">
                                                    <input type="text" class="cal-input hasDatepicker"
                                                        placeholder="Depart Date" id="datepicker3">
                                                </span>
                                            </div>
                                        </div>
                                        <div
                                            class="col-12 col-lg-6 col-xl-4 ps-0 mb-2 mb-lg-0 mb-xl-0 pe-0 pe-lg-0 pe-xl-0">
                                            <div class="dropdown" id="myDDRound">
                                                <button class="dropdown-toggle form-control" type="button"
                                                    id="travellerInfoMulti" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i
                                                        class="bi bi-person-lines-fill position-absolute h2 icon-pos"></i>
                                                    <span class="text-truncate">1 Traveller(s), Economy </span>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="travellerInfoMulti">
                                                    <ul class="drop-rest">
                                                        <li>
                                                            <div class="d-flex">Select Adults</div>
                                                            <div class="ms-auto input-group plus-minus-input">
                                                                <div class="input-group-button">
                                                                    <button type="button" class="circle"
                                                                        data-quantity="minus" data-field="onewayAdult">
                                                                        <i class="bi bi-dash"></i>
                                                                    </button>
                                                                </div>
                                                                <input class="input-group-field" type="number"
                                                                    name="onewayAdult" value="0">
                                                                <div class="input-group-button">
                                                                    <button type="button" class="circle"
                                                                        data-quantity="plus" data-field="onewayAdult">
                                                                        <i class="bi bi-plus"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="d-flex">Select Child</div>
                                                            <div class="ms-auto input-group plus-minus-input">
                                                                <div class="input-group-button">
                                                                    <button type="button" class="circle"
                                                                        data-quantity="minus" data-field="onewayChild">
                                                                        <i class="bi bi-dash"></i>
                                                                    </button>
                                                                </div>
                                                                <input class="input-group-field" type="number"
                                                                    name="onewayChild" value="0">
                                                                <div class="input-group-button">
                                                                    <button type="button" class="circle"
                                                                        data-quantity="plus" data-field="onewayChild">
                                                                        <i class="bi bi-plus"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="d-flex">Select Infants</div>
                                                            <div class="ms-auto input-group plus-minus-input">
                                                                <div class="input-group-button">
                                                                    <button type="button" class="circle"
                                                                        data-quantity="minus" data-field="onewayInfant">
                                                                        <i class="bi bi-dash"></i>
                                                                    </button>
                                                                </div>
                                                                <input class="input-group-field" type="number"
                                                                    name="onewayInfant" value="0">
                                                                <div class="input-group-button">
                                                                    <button type="button" class="circle"
                                                                        data-quantity="plus" data-field="onewayInfant">
                                                                        <i class="bi bi-plus"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="class" value="Economy"
                                                                    class="me-2">Economy </label>
                                                        </li>
                                                        <li>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="class" value="Special"
                                                                    class="me-2">Premium Economy </label>
                                                        </li>
                                                        <li>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="class" value="Business"
                                                                    class="me-2">Business
                                                            </label>
                                                        </li>
                                                        <li>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="class" value="First"
                                                                    class="me-2">First Class </label>
                                                        </li>
                                                        <li>
                                                            <button type="button" class="btn btn"
                                                                onclick="">Done</button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-0 mt-md-0 mt-lg-0 mt-xl-2">
                                        <div class="col-12 col-lg-4 col-xl-3 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-2">
                                            <div class="form-group">
                                                <i class="bi bi-geo-alt-fill position-absolute h2 icon-pos"></i>
                                                <input type="text" class="form-control ps-5" id="multiOrigin2"
                                                    placeholder="Origin" wire:model.live="multiCitySegments.1.origin">
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-4 col-xl-3 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-2">
                                            <div class="form-group">
                                                <i class="bi bi-geo-alt-fill position-absolute h2 icon-pos"></i>
                                                <input type="text" class="form-control ps-5" id="multiDestination2"
                                                    placeholder="Destination"
                                                    wire:model.live="multiCitySegments.1.destination">
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-4 col-xl-2 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-0 pe-xl-2">
                                            <div class="form-control form-group d-flex">
                                                <i class="bi bi-calendar3 position-absolute h2 icon-pos"></i>
                                                <span class="dep-date-input">
                                                    <input type="text" class="cal-input hasDatepicker"
                                                        placeholder="Depart Date" id="datepicker4">
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-12 col-xl-4 px-0">
                                            <div class="row">
                                                <div
                                                    class="col-12 col-lg-6 col-xl-7 mb-2 mb-md-2 mb-lg-0 d-flex justify-content-center align-items-center">
                                                    <button type="submit" class="btn btn-light" id="add-button">
                                                        <span class="fw-bold">+ Add City</span> </button>
                                                    <button type="submit" class="btn" id="remove-button"> <span
                                                            class="fw-bold">Close</span> </button>
                                                </div>
                                                <div class="col-12 col-lg-6 col-xl-5">
                                                    <button type="submit" class="btn btn-search"
                                                        onclick="window.location.href='flight-listing-multicity.html';">
                                                        <span class="fw-bold">Search</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @if(($oneWayFlightResults && $activeTab === 'oneway') || ($returnFlightResults && $activeTab === 'return') || ($multiCityFlightResults && $activeTab === 'multiCity'))
        <!-- Flight Results (one-way handled by its own component) -->

        <div class="filter-sec py-2">
            <div class="container">
                <div class="d-flex justify-content-between">
                    <div class="fw-bold"><i class="bi bi-funnel pe-2"></i>Filters</div>
                    <a class="btn flt-btn" data-bs-toggle="collapse" href="#collapseExample" role="button"
                        aria-expanded="false" aria-controls="collapseExample">Reset All
                    </a>
                </div>
            </div>
        </div>
        <div class="border-bottom theme-bg-white">
            <div class="container">
                <div class="collapse" id="collapseExample">
                    <div class="row mb-3 pb-2 border-bottom">
                        <div class="col-12 col-md-3 col-lg-2 py-2 border-end"><span class="d-flex mb-3">Price</span>
                            <div class="price-range-slider">
                                <p class="range-value">
                                    <input type="text" id="amount" readonly="">
                                </p>
                                <div id="slider-range"
                                    class="range-bar ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content">
                                    <div class="ui-slider-range ui-corner-all ui-widget-header"
                                        style="left: 0%; width: 32.4324%;"></div><span tabindex="0"
                                        class="ui-slider-handle ui-corner-all ui-state-default"
                                        style="left: 0%;"></span><span tabindex="0"
                                        class="ui-slider-handle ui-corner-all ui-state-default"
                                        style="left: 32.4324%;"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 col-lg-3 py-2 border-end">
                            <span class="d-flex mb-3">Arrival Time</span>
                            <span class="font-small mb-2 d-flex">
                                @if($activeTab === 'oneway')
                                    {{ $oneWayOrigin }} - {{ $oneWayDestination }}
                                @elseif($activeTab === 'return')
                                    {{ $returnOrigin }} - {{ $returnDestination }}
                                @else
                                    Select Origin - Destination
                                @endif
                            </span>
                            <ul>
                                <li class="d-flex justify-content-between">
                                    <div class="form-check-inline">
                                        <label class="check-wrap">Before 6AM
                                            <input type="checkbox"><span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="pull-right">
                                        <div class="form-check-inline">
                                            <label class="check-wrap">12 Noon-6PM
                                                <input type="checkbox"><span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <div class="form-check-inline">
                                        <label class="check-wrap">After 6PM
                                            <input type="checkbox"><span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="pull-right">
                                        <div class="form-check-inline">
                                            <label class="check-wrap">6AM-12 Noon
                                                <input type="checkbox"><span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-3 col-lg-3 py-3 border-end">
                            <span class="d-flex mb-3">Departure Time</span>
                            <span class="font-small mb-2 d-flex">
                                @if($activeTab === 'oneway')
                                    {{ $oneWayDestination }} - {{ $oneWayOrigin }}
                                @elseif($activeTab === 'return')
                                    {{ $returnDestination }} - {{ $returnOrigin }}
                                @else
                                    Select Destination - Origin
                                @endif
                            </span>
                            <ul>
                                <li class="d-flex justify-content-between">
                                    <div class="form-check-inline">
                                        <label class="check-wrap">Before 6AM
                                            <input type="checkbox"><span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="pull-right">
                                        <div class="form-check-inline">
                                            <label class="check-wrap">12 Noon-6PM
                                                <input type="checkbox"><span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <div class="form-check-inline">
                                        <label class="check-wrap">After 6PM
                                            <input type="checkbox"><span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="pull-right">
                                        <div class="form-check-inline">
                                            <label class="check-wrap">6AM-12 Noon
                                                <input type="checkbox"><span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-3 col-lg-2 border-end">
                            <span class="d-flex mb-3">Duration</span>
                            <div class="price-range-slider">
                                <p class="range-value">
                                    <input type="text" id="duration" readonly="">
                                </p>
                                <div id="duration-range"
                                    class="range-bar ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content">
                                    <div class="ui-slider-range ui-corner-all ui-widget-header"
                                        style="left: 0%; width: 62.1622%;"></div><span tabindex="0"
                                        class="ui-slider-handle ui-corner-all ui-state-default"
                                        style="left: 0%;"></span><span tabindex="0"
                                        class="ui-slider-handle ui-corner-all ui-state-default"
                                        style="left: 62.1622%;"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 col-lg-2 py-2">
                            <span class="fw-bold">Stops</span>
                            <ul>
                                <li>
                                    <div class="form-check-inline">
                                        <label class="check-wrap">0 Stop
                                            <input type="checkbox"><span class="checkmark"></span>
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check-inline">
                                        <label class="check-wrap">1 Stop
                                            <input type="checkbox"><span class="checkmark"></span>
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check-inline">
                                        <label class="check-wrap">1+ Stop
                                            <input type="checkbox"><span class="checkmark"></span>
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check-inline">
                                        <label class="check-wrap">Direct/Non Change of Aircraft
                                            <input type="checkbox"><span class="checkmark"></span>
                                        </label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12 col-md-3 py-2 border-end">
                            <span class="d-flex mb-3">Fare Type</span>
                            <ul>
                                <li>
                                    <div class="form-check-inline">
                                        <label class="check-wrap">Refundable Fares Only
                                            <input type="checkbox"><span class="checkmark"></span>
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check-inline">
                                        <label class="check-wrap">Nonfundable Fares
                                            <input type="checkbox"><span class="checkmark"></span>
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check-inline">
                                        <label class="check-wrap">Partially refundable
                                            <input type="checkbox"><span class="checkmark"></span>
                                        </label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-3 py-2 border-end">
                            <span class="d-flex mb-3">Fare Category</span>
                            <ul>
                                <li>
                                    <div class="form-check-inline">
                                        <label class="check-wrap">Retail
                                            <input type="checkbox"><span class="checkmark"></span>
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check-inline">
                                        <label class="check-wrap">Special Return Fare
                                            <input type="checkbox"><span class="checkmark"></span>
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check-inline">
                                        <label class="check-wrap">Flexi
                                            <input type="checkbox"><span class="checkmark"></span>
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check-inline">
                                        <label class="check-wrap">SME
                                            <input type="checkbox"><span class="checkmark"></span>
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check-inline">
                                        <label class="check-wrap">Business
                                            <input type="checkbox"><span class="checkmark"></span>
                                        </label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-4 py-2">
                            <span class="d-flex mb-3">Airlines</span>
                            <ul class="d-flex flex-wrap justify-content-between">
                                <li class="w-50">
                                    <div class="form-check-inline">
                                        <label class="check-wrap">Spicejet<br>
                                            <i class="fa fa-rupee"></i> 4288 onwards
                                            <input type="checkbox"><span class="checkmark"></span>
                                        </label>
                                    </div>
                                </li>
                                <li class="w-50">
                                    <div class="form-check-inline">
                                        <label class="check-wrap">Goair<br>
                                            <i class="fa fa-rupee"></i> 4240 onwards
                                            <input type="checkbox"><span class="checkmark"></span>
                                        </label>
                                    </div>
                                </li>
                                <li class="w-50">
                                    <div class="form-check-inline">
                                        <label class="check-wrap">IndiGo<br>
                                            <i class="fa fa-rupee"></i> 4494 onwards
                                            <input type="checkbox"><span class="checkmark"></span>
                                        </label>
                                    </div>
                                </li>
                                <li class="w-50">
                                    <div class="form-check-inline">
                                        <label class="check-wrap">Vistara<br>
                                            <i class="fa fa-rupee"></i> 3065 onwards
                                            <input type="checkbox"><span class="checkmark"></span>
                                        </label>
                                    </div>
                                </li>
                                <li class="w-50">
                                    <div class="form-check-inline">
                                        <label class="check-wrap">Air India<br>
                                            <i class="fa fa-rupee"></i> 5779 onwards
                                            <input type="checkbox"><span class="checkmark"></span>
                                        </label>
                                    </div>
                                </li>
                                <li class="w-50">
                                    <div class="form-check-inline">
                                        <label class="check-wrap">Jet Airways<br>
                                            <i class="fa fa-rupee"></i> 9055 onwards
                                            <input type="checkbox"><span class="checkmark"></span>
                                        </label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-2 align-self-end">
                            <button type="submit" class="btn btn-light">
                                <span class="fw-bold">Reset</span>
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <span class="fw-bold">Apply</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-section">
            @if($activeTab === 'oneway')
                <div class="srp py-2" wire:key="oneway-results-section">
                    <div class="container">
                        @if($searchError)
                            <div class="row">
                                <div class="col-12 mt-2">
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        {{ $searchError }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @php
                            $currentResults = $oneWayFlightResults;
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
                                        <span class="font-small fw-bold">Price<i class="bi bi-arrow-up"></i> <input
                                                type="checkbox" class="cursor-pointer"></span>
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
                                    <div class="col-12 mb-3" wire:key="flight-offer-{{ $loop->index }}">
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
                                                    {{ \Carbon\Carbon::parse($firstSegment['departure']['at'])->format('H:i') }}
                                                </div>
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
                                                    {{ \Carbon\Carbon::parse($lastSegment['arrival']['at'])->format('H:i') }}
                                                </div>
                                                <div class="font-small text-muted">{{ $lastSegment['arrival']['iataCode'] }}</div>
                                            </div>
                                            <div class="col-12 col-md-3 text-center mt-md-0 mt-2">
                                                <div class="fw-bold h5 mb-1 text-primary">
                                                    <span
                                                        class="font-small text-muted me-1">USD</span>{{ number_format($offer['price']['total']) }}
                                                </div>
                                                <button type="button" class="btn-select btn btn-effect"
                                                    wire:click.prevent="selectFlight('{{ $offer['id'] }}')" wire:loading.attr="disabled"
                                                    wire:target="selectFlight('{{ $offer['id'] }}')">
                                                    <span class="font-small" wire:loading.remove
                                                        wire:target="selectFlight('{{ $offer['id'] }}')">Select</span>
                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
                                                        wire:loading wire:target="selectFlight('{{ $offer['id'] }}')"></span>
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

            @elseif($activeTab === 'return')
                {{-- <livewire:pages.flights.listing-return :results="$returnFlightResults ?? []" :origin="$returnOrigin"
                    :destination="$returnDestination" :departure-date="$returnDepartureDate" :return-date="$returnReturnDate"
                    :wire:key="'listing-return-' . ($returnFlightResults ? 'has-results' : 'no-results')" /> --}}
            @elseif($activeTab === 'multiCity')
                {{-- <livewire:pages.flights.listing-multicity :results="$multiCityFlightResults ?? []"
                    :segments="$multiCitySegments ?? []"
                    :wire:key="'listing-multicity-' . ($multiCityFlightResults ? 'has-results' : 'no-results')" /> --}}
            @endif
        </div>
    @endif

</div>

@push('scripts')

    <script>
        document.addEventListener('livewire:init', () => {
            // Initialize jQuery datepicker
            if (typeof $ !== 'undefined' && $('#datepicker').length) {
                $('#datepicker').datepicker({
                    dateFormat: 'yy-mm-dd',
                    minDate: 0,
                    onSelect: function (dateText) {
                        const el = document.querySelector('[wire\\:id]');
                        if (el) {
                            const component = Livewire.find(el.getAttribute('wire:id'));
                            if (component) {
                                component.set('oneWayDepartureDate', dateText);
                            }
                        }
                    }
                });
            }

            Livewire.on('search-started', () => {
                const preloader = document.getElementById('searchPreloader');
                if (preloader) {
                    preloader.style.display = 'block';
                    document.body.style.overflow = 'hidden';

                    // Safety timeout: auto-hide after 20 seconds
                    setTimeout(() => {
                        preloader.style.display = 'none';
                        document.body.style.overflow = '';
                    }, 20000);
                }
            });

            Livewire.on('search-finished', () => {
                const preloader = document.getElementById('searchPreloader');
                if (preloader) {
                    preloader.style.display = 'none';
                    document.body.style.overflow = '';
                }
            });
        });

        // Backup listener for date changes (if picker is initialized elsewhere)
        document.addEventListener('livewire:init', () => {
            const datepicker = $('#datepicker');
            if (datepicker.length) {
                datepicker.on('change', function (e) {
                    const el = document.querySelector('[wire\\:id]');
                    if (el) {
                        const component = Livewire.find(el.getAttribute('wire:id'));
                        if (component) {
                            component.set('oneWayDepartureDate', e.target.value);
                        }
                    }
                });
            }
        });
    </script>
@endpush