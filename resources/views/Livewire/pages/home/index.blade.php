<div class="home-page head-wrapper">

        <div class="flight-search">
            <div class="container">

                <!-- Nav tabs -->
                <ul class="nav nav-tabs border-0" role="tablist">
                    <li class="nav-item">
                        <button @class(['nav-link', 'active' => $activeTab === 'oneway']) id="oneway-tab"
                            wire:click="setActiveTab('oneway')" type="button" role="tab"
                            aria-controls="oneway" aria-selected="{{ $activeTab === 'oneway' ? 'true' : 'false' }}">
                            <span
                                class="d-inline-block icon-20 rounded-circle bg-white align-middle me-2"></span>One-way
                        </button>
                    </li>
                    <li class="nav-item">
                        <button @class(['nav-link', 'active' => $activeTab === 'return']) id="return-tab"
                            wire:click="setActiveTab('return')" type="button" role="tab"
                            aria-controls="return" aria-selected="{{ $activeTab === 'return' ? 'true' : 'false' }}">
                            <span class="d-inline-block icon-20 rounded-circle bg-white align-middle me-2"></span>Return
                        </button>
                    </li>
                    <li class="nav-item">
                        <button @class(['nav-link', 'active' => $activeTab === 'multiCity']) id="multiCity-tab"
                            wire:click="setActiveTab('multiCity')" type="button" role="tab"
                            aria-controls="multiCity"
                            aria-selected="{{ $activeTab === 'multiCity' ? 'true' : 'false' }}">
                            <span
                                class="d-inline-block icon-20 rounded-circle bg-white align-middle me-2"></span>Multi-city
                        </button>
                    </li>
                </ul>
                <!-- Tab content -->
                <div class="tab-content">
                    <!-- oneway search -->
                    <div id="oneway" @class(['tab-pane', 'active show' => $activeTab === 'oneway'])>
                        <div class="row">
                            <div class="col-12">
                                <div class="search-pan row mx-0 theme-border-radius">
                                    <div class="col-12 col-lg-4 col-xl-2 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-2"
                                        wire:key="oneway-origin">
                                        <div class="form-group">
                                            <i class="bi bi-geo-alt-fill position-absolute h2 icon-pos"></i>
                                            <input type="text" class="form-control ps-5" id="onewayOrigin"
                                                placeholder="Origin" wire:model="oneWayOrigin">
                                            @error('oneWayOrigin') <span
                                            class="text-danger font-xs">{{ $message }}</span> @enderror
                                            <button class="pos-swap"><i
                                                    class="bi bi-arrow-left-right pl-1"></i></button>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4 col-xl-2 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-2"
                                        wire:key="oneway-destination">
                                        <div class="form-group">
                                            <i class="bi bi-geo-alt-fill position-absolute h2 icon-pos"></i>
                                            <input type="text" class="form-control ps-5" id="onewayDestination"
                                                placeholder="Destination" wire:model="oneWayDestination">
                                            @error('oneWayDestination') <span
                                            class="text-danger font-xs">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4 col-xl-3 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-0 pe-xl-2"
                                        wire:key="oneway-date">
                                        <div class="form-control form-group d-flex">
                                            <i class="bi bi-calendar3 position-absolute h2 icon-pos"></i>
                                            <span class="dep-date-input">
                                                <input type="date" class="cal-input" placeholder="Depart Date"
                                                    id="onewayDepartureDate" wire:model="oneWayDepartureDate">
                                                @error('oneWayDepartureDate') <span
                                                class="text-danger font-xs">{{ $message }}</span> @enderror
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6 col-xl-3 ps-0 mb-2 mb-lg-0 mb-xl-0 pe-0 pe-lg-2">
                                        <div class="dropdown" id="myDD">
                                            <button class="dropdown-toggle form-control" type="button"
                                                id="travellerInfoOneway" data-bs-toggle="dropdown"
                                                aria-expanded="false">
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
                                                                    data-quantity="minus" data-field="onewayAdult"
                                                                    wire:click="decreaseAdults">
                                                                    <i class="bi bi-dash"></i>
                                                                </button>
                                                            </div>
                                                            <input class="input-group-field" type="number"
                                                                name="onewayAdult" value="{{ $oneWayAdults }}"
                                                                wire:model="oneWayAdults">
                                                            <div class="input-group-button">
                                                                <button type="button" class="circle"
                                                                    data-quantity="plus" data-field="onewayAdult"
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
                                                                    data-quantity="minus" data-field="onewayChild"
                                                                    wire:click="decreaseChildren">
                                                                    <i class="bi bi-dash"></i>
                                                                </button>
                                                            </div>
                                                            <input class="input-group-field" type="number"
                                                                name="onewayChild" value="{{ $oneWayChildren }}"
                                                                wire:model="oneWayChildren">
                                                            <div class="input-group-button">
                                                                <button type="button" class="circle"
                                                                    data-quantity="plus" data-field="onewayChild"
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
                                                                    data-quantity="minus" data-field="onewayInfant"
                                                                    wire:click="decreaseInfants">
                                                                    <i class="bi bi-dash"></i>
                                                                </button>
                                                            </div>
                                                            <input class="input-group-field" type="number"
                                                                name="onewayInfant" value="{{ $oneWayInfants }}"
                                                                wire:model="oneWayInfants">
                                                            <div class="input-group-button">
                                                                <button type="button" class="circle"
                                                                    data-quantity="plus" data-field="onewayInfant"
                                                                    wire:click="increaseInfants">
                                                                    <i class="bi bi-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="class" value="Economy"
                                                                class="me-2" wire:model="oneWayTravelClass">Economy
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="class" value="Special"
                                                                class="me-2" wire:model="oneWayTravelClass">Premium
                                                            Economy </label>
                                                    </li>
                                                    <li>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="class" value="Business"
                                                                class="me-2" wire:model="oneWayTravelClass">Business
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="class" value="First" class="me-2"
                                                                wire:model="oneWayTravelClass">First Class </label>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="btn btn" onclick="">Done</button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6 col-xl-2 px-0">
                                        <button type="button" class="btn btn-search" id="onewaySearchBtn"
                                            wire:click="searchFlights" wire:loading.attr="disabled"
                                            wire:loading.class="btn-loading">
                                            <span class="fw-bold" wire:loading.remove
                                                wire:target="searchFlights">Search</span>
                                            <span class="fw-bold" wire:loading
                                                wire:target="searchFlights">Searching...</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-4">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-check-inline">
                                            <label class="check-wrap">Refundable Flights
                                                <input type="checkbox" wire:model="oneWayRefundable">
                                                <span class="checkmark"></span> </label>
                                        </div>
                                        <div class="form-check-inline">
                                            <label class="check-wrap"> Non Stop Flights
                                                <input type="checkbox" wire:model="oneWayNonStop">
                                                <span class="checkmark"></span> </label>
                                        </div>
                                        <div class="form-check-inline">
                                            <label class="check-wrap"> GDS Special Return
                                                <input type="checkbox" wire:model="oneWayGdsReturn">
                                                <span class="checkmark"></span> </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Return search -->
                    <div id="return" @class(['tab-pane fade', 'active show' => $activeTab === 'return'])>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="search-pan row mx-0 theme-border-radius">
                                    <div class="col-12 col-lg-4 col-xl-2 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-2"
                                        wire:key="return-origin">
                                        <div class="form-group">
                                            <i class="bi bi-geo-alt-fill position-absolute h2 icon-pos"></i>
                                            <input type="text" class="form-control ps-5" id="returnOrigin"
                                                placeholder="Origin" wire:model="returnOrigin">
                                            @error('returnOrigin') <span
                                            class="text-danger font-xs">{{ $message }}</span> @enderror
                                            <button class="pos-swap"><i
                                                    class="bi bi-arrow-left-right pl-1"></i></button>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4 col-xl-2 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-2"
                                        wire:key="return-destination">
                                        <div class="form-group">
                                            <i class="bi bi-geo-alt-fill position-absolute h2 icon-pos"></i>
                                            <input type="text" class="form-control ps-5" id="returnDestination"
                                                placeholder="Destination" wire:model="returnDestination">
                                            @error('returnDestination') <span
                                            class="text-danger font-xs">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4 col-xl-3 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-0 pe-xl-2"
                                        wire:key="return-dates">
                                        <div class="form-control form-group d-flex">
                                            <i class="bi bi-calendar3 position-absolute h2 icon-pos"></i>
                                            <span class="dep-date-input">
                                                <input type="date" class="cal-input" placeholder="Depart Date"
                                                    id="returnDepartureDate" wire:model="returnDepartureDate">
                                                @error('returnDepartureDate') <span
                                                class="text-danger font-xs">{{ $message }}</span> @enderror
                                            </span>
                                            <span class="arv-date-input ms-2">
                                                <input type="date" class="cal-input" placeholder="Return Date"
                                                    id="returnReturnDate" wire:model="returnReturnDate">
                                                @error('returnReturnDate') <span
                                                class="text-danger font-xs">{{ $message }}</span> @enderror
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6 col-xl-3 ps-0 mb-2 mb-lg-0 mb-xl-0 pe-0 pe-lg-2">
                                        <div class="dropdown" id="myDDReturn">
                                            <button class="dropdown-toggle form-control" type="button"
                                                id="travellerInfoReturn" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="bi bi-person-lines-fill position-absolute h2 icon-pos"></i>
                                                <span
                                                    class="text-truncate">{{ $returnAdults + $returnChildren + $returnInfants }}
                                                    Traveller(s), {{ $returnTravelClass }} </span>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="travellerInfoReturn">
                                                <ul class="drop-rest">
                                                    <li>
                                                        <div class="d-flex">Select Adults</div>
                                                        <div class="ms-auto input-group plus-minus-input">
                                                            <div class="input-group-button">
                                                                <button type="button" class="circle"
                                                                    data-quantity="minus" data-field="returnAdult"
                                                                    wire:click="decreaseReturnAdults">
                                                                    <i class="bi bi-dash"></i>
                                                                </button>
                                                            </div>
                                                            <input class="input-group-field" type="number"
                                                                name="returnAdult" value="{{ $returnAdults }}"
                                                                wire:model="returnAdults">
                                                            <div class="input-group-button">
                                                                <button type="button" class="circle"
                                                                    data-quantity="plus" data-field="returnAdult"
                                                                    wire:click="increaseReturnAdults">
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
                                                                    data-quantity="minus" data-field="returnChild"
                                                                    wire:click="decreaseReturnChildren">
                                                                    <i class="bi bi-dash"></i>
                                                                </button>
                                                            </div>
                                                            <input class="input-group-field" type="number"
                                                                name="returnChild" value="{{ $returnChildren }}"
                                                                wire:model="returnChildren">
                                                            <div class="input-group-button">
                                                                <button type="button" class="circle"
                                                                    data-quantity="plus" data-field="returnChild"
                                                                    wire:click="increaseReturnChildren">
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
                                                                    data-quantity="minus" data-field="returnInfant"
                                                                    wire:click="decreaseReturnInfants">
                                                                    <i class="bi bi-dash"></i>
                                                                </button>
                                                            </div>
                                                            <input class="input-group-field" type="number"
                                                                name="returnInfant" value="{{ $returnInfants }}"
                                                                wire:model="returnInfants">
                                                            <div class="input-group-button">
                                                                <button type="button" class="circle"
                                                                    data-quantity="plus" data-field="returnInfant"
                                                                    wire:click="increaseReturnInfants">
                                                                    <i class="bi bi-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="returnClass" value="Economy"
                                                                class="me-2" wire:model="returnTravelClass">Economy
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="returnClass" value="Special"
                                                                class="me-2" wire:model="returnTravelClass">Premium
                                                            Economy
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="returnClass" value="Business"
                                                                class="me-2" wire:model="returnTravelClass">Business
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="returnClass" value="First"
                                                                class="me-2" wire:model="returnTravelClass">First Class
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
                                        <button type="button" class="btn btn-search" wire:click="searchReturnFlights"
                                            wire:loading.attr="disabled" wire:loading.class="btn-loading">
                                            <span class="fw-bold" wire:loading.remove
                                                wire:target="searchReturnFlights">Search</span>
                                            <span class="fw-bold" wire:loading
                                                wire:target="searchReturnFlights">Searching...</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Multicity search -->
                    <div id="multiCity" @class(['tab-pane fade', 'active show' => $activeTab === 'multiCity'])>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="search-pan row mx-0 theme-border-radius">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-12 col-lg-6 col-xl-3 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-2"
                                                wire:key="multi-0-origin">
                                                <div class="form-group">
                                                    <i class="bi bi-geo-alt-fill position-absolute h2 icon-pos"></i>
                                                    <input type="text" class="form-control ps-5" id="multiOrigin"
                                                        placeholder="Origin" wire:model="multiCitySegments.0.origin">
                                                    @error('multiCitySegments.0.origin') <span
                                                    class="text-danger font-xs">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6 col-xl-3 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-0 pe-xl-2"
                                                wire:key="multi-0-destination">
                                                <div class="form-group">
                                                    <i class="bi bi-geo-alt-fill position-absolute h2 icon-pos"></i>
                                                    <input type="text" class="form-control ps-5" id="multiDestination"
                                                        placeholder="Destination"
                                                        wire:model="multiCitySegments.0.destination">
                                                    @error('multiCitySegments.0.destination') <span
                                                    class="text-danger font-xs">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6 col-xl-2 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-2 pe-xl-2"
                                                wire:key="multi-0-date">
                                                <div class="form-control form-group d-flex">
                                                    <i class="bi bi-calendar3 position-absolute h2 icon-pos"></i>
                                                    <span class="dep-date-input">
                                                        <input type="date" class="cal-input" placeholder="Depart Date"
                                                            id="multiDepartureDate1"
                                                            wire:model="multiCitySegments.0.departureDate">
                                                        @error('multiCitySegments.0.departureDate') <span
                                                        class="text-danger font-xs">{{ $message }}</span> @enderror
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
                                                        <span
                                                            class="text-truncate">{{ $multiCityAdults + $multiCityChildren + $multiCityInfants }}
                                                            Traveller(s), {{ $multiCityTravelClass }} </span>
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="travellerInfoMulti">
                                                        <ul class="drop-rest">
                                                            <li>
                                                                <div class="d-flex">Select Adults</div>
                                                                <div class="ms-auto input-group plus-minus-input">
                                                                    <div class="input-group-button">
                                                                        <button type="button" class="circle"
                                                                            data-quantity="minus"
                                                                            data-field="multiCityAdult"
                                                                            wire:click="decreaseMultiCityAdults">
                                                                            <i class="bi bi-dash"></i>
                                                                        </button>
                                                                    </div>
                                                                    <input class="input-group-field" type="number"
                                                                        name="multiCityAdult"
                                                                        value="{{ $multiCityAdults }}"
                                                                        wire:model="multiCityAdults">
                                                                    <div class="input-group-button">
                                                                        <button type="button" class="circle"
                                                                            data-quantity="plus"
                                                                            data-field="multiCityAdult"
                                                                            wire:click="increaseMultiCityAdults">
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
                                                                            data-quantity="minus"
                                                                            data-field="multiCityChild"
                                                                            wire:click="decreaseMultiCityChildren">
                                                                            <i class="bi bi-dash"></i>
                                                                        </button>
                                                                    </div>
                                                                    <input class="input-group-field" type="number"
                                                                        name="multiCityChild"
                                                                        value="{{ $multiCityChildren }}"
                                                                        wire:model="multiCityChildren">
                                                                    <div class="input-group-button">
                                                                        <button type="button" class="circle"
                                                                            data-quantity="plus"
                                                                            data-field="multiCityChild"
                                                                            wire:click="increaseMultiCityChildren">
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
                                                                            data-quantity="minus"
                                                                            data-field="multiCityInfant"
                                                                            wire:click="decreaseMultiCityInfants">
                                                                            <i class="bi bi-dash"></i>
                                                                        </button>
                                                                    </div>
                                                                    <input class="input-group-field" type="number"
                                                                        name="multiCityInfant"
                                                                        value="{{ $multiCityInfants }}"
                                                                        wire:model="multiCityInfants">
                                                                    <div class="input-group-button">
                                                                        <button type="button" class="circle"
                                                                            data-quantity="plus"
                                                                            data-field="multiCityInfant"
                                                                            wire:click="increaseMultiCityInfants">
                                                                            <i class="bi bi-plus"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <label class="radio-inline">
                                                                    <input type="radio" name="multiCityClass"
                                                                        value="Economy" class="me-2"
                                                                        wire:model="multiCityTravelClass">Economy
                                                                </label>
                                                            </li>
                                                            <li>
                                                                <label class="radio-inline">
                                                                    <input type="radio" name="multiCityClass"
                                                                        value="Special" class="me-2"
                                                                        wire:model="multiCityTravelClass">Premium
                                                                    Economy
                                                                </label>
                                                            </li>
                                                            <li>
                                                                <label class="radio-inline">
                                                                    <input type="radio" name="multiCityClass"
                                                                        value="Business" class="me-2"
                                                                        wire:model="multiCityTravelClass">Business
                                                                </label>
                                                            </li>
                                                            <li>
                                                                <label class="radio-inline">
                                                                    <input type="radio" name="multiCityClass"
                                                                        value="First" class="me-2"
                                                                        wire:model="multiCityTravelClass">First
                                                                    Class </label>
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
                                        <div class="multi_city_form_wrapper"></div>
                                        @foreach($multiCitySegments as $segmentIndex => $segment)
                                            @if($segmentIndex > 0)
                                                <div class="row mt-0 mt-md-0 mt-lg-0 mt-xl-2" wire:key="multi-row-{{ $segmentIndex }}">
                                                    <div class="col-12 col-lg-12 col-xl-8">
                                                        <div class="row">
                                                            <div class="col-12 col-lg-4 col-xl-4 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-2"
                                                                wire:key="multi-{{ $segmentIndex }}-origin">
                                                                <div class="form-group">
                                                                    <i class="bi bi-geo-alt-fill position-absolute h2 icon-pos"></i>
                                                                    <input type="text" class="form-control ps-5"
                                                                        placeholder="Origin"
                                                                        wire:model="multiCitySegments.{{ $segmentIndex }}.origin">
                                                                    @error("multiCitySegments.$segmentIndex.origin") <span
                                                                        class="text-danger font-xs">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-lg-4 col-xl-4 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-2"
                                                                wire:key="multi-{{ $segmentIndex }}-destination">
                                                                <div class="form-group">
                                                                    <i class="bi bi-geo-alt-fill position-absolute h2 icon-pos"></i>
                                                                    <input type="text" class="form-control ps-5"
                                                                        placeholder="Destination"
                                                                        wire:model="multiCitySegments.{{ $segmentIndex }}.destination">
                                                                    @error("multiCitySegments.$segmentIndex.destination") <span
                                                                        class="text-danger font-xs">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-lg-4 col-xl-4 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-0 pe-xl-2"
                                                                wire:key="multi-{{ $segmentIndex }}-date">
                                                                <div class="form-control form-group d-flex">
                                                                    <i class="bi bi-calendar3 position-absolute h2 icon-pos"></i>
                                                                    <span class="dep-date-input">
                                                                        <input type="date" class="cal-input"
                                                                            placeholder="Depart Date"
                                                                            wire:model="multiCitySegments.{{ $segmentIndex }}.departureDate">
                                                                        @error("multiCitySegments.$segmentIndex.departureDate") <span
                                                                            class="text-danger font-xs">{{ $message }}</span>
                                                                        @enderror
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if($segmentIndex === 1)
                                                        <div class="col-12 col-lg-12 col-xl-4 px-0">
                                                            <div class="row">
                                                                <div class="col-12 col-lg-6 col-xl-5 mb-2 mb-md-2 mb-lg-0 d-flex justify-content-center align-items-center">
                                                                    <button type="button" class="btn btn-light font-small"
                                                                        wire:click.prevent="addMultiCitySegment">
                                                                        <span class="fw-bold">+ Add City</span>
                                                                    </button>
                                                                </div>
                                                                <div class="col-12 col-lg-6 col-xl-7">
                                                                    <button type="button" class="btn btn-search"
                                                                        wire:click="searchMultiCityFlights"
                                                                        wire:loading.attr="disabled"
                                                                        wire:loading.class="btn-loading">
                                                                        <span class="fw-bold" wire:loading.remove
                                                                            wire:target="searchMultiCityFlights">Search</span>
                                                                        <span class="fw-bold" wire:loading
                                                                            wire:target="searchMultiCityFlights">Searching...</span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @if (!empty($searchError))
        <div class="container mt-3">
            <div class="alert alert-warning py-2 mb-0">
                {{ $searchError }}
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="container mt-3">
            <div class="alert alert-danger py-2 mb-0">
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if($flightResults || $returnFlightResults || $multiCityFlightResults || $activeTab === 'return')
    <!-- Flight Results -->

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
                        <span class="font-small mb-2 d-flex">New Delhi - Goa</span>
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
                        <span class="font-small mb-2 d-flex">Goa - New Delhi</span>
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
        <div class="srp py-2">
            <div class="container">
                @if($activeTab === 'return')
                    <livewire:pages.flights.listing-return
                        :results="$returnFlightResults ?? []"
                        :origin="$returnOrigin"
                        :destination="$returnDestination"
                        :departure-date="$returnDepartureDate"
                        :return-date="$returnReturnDate"
                        :key="'listing-return-' . md5(json_encode([$returnOrigin, $returnDestination, $returnDepartureDate, $returnReturnDate, count($returnFlightResults['data'] ?? [])]))" />
                @elseif($activeTab === 'multiCity')
                    <livewire:pages.flights.listing-multicity
                        :results="$multiCityFlightResults ?? []"
                        :segments="$multiCitySegments ?? []"
                        :key="'listing-multicity-' . md5(json_encode([count($multiCitySegments ?? []), count($multiCityFlightResults['data'] ?? [])]))" />
                @else
                    @php
                        $currentResults = $flightResults;
                        $count = isset($currentResults['data']) ? count($currentResults['data']) : 0;
                    @endphp

                    <div class="row">
                        <div class="col-12 my-2">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-bold">
                                        @if($activeTab === 'oneway')
                                            {{ $oneWayOrigin }} <i class="bi bi-arrow-right mx-2"></i> {{ $oneWayDestination }}
                                        @else
                                            Multi-City Trip
                                        @endif
                                    </div>
                                    <div class="mb-1 font-small">
                                        @if($activeTab === 'oneway')
                                            {{ \Carbon\Carbon::parse($oneWayDepartureDate)->format('D, M d') }}
                                        @else
                                            {{ count($multiCitySegments) }} Segments
                                        @endif
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
                                    <span class="font-small fw-bold">Price<i class="bi bi-arrow-up"></i> <input type="checkbox" class="cursor-pointer"></span>
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
                                    <div class="row g-0 border theme-border-radius theme-box-shadow p-2 align-items-center theme-bg-white">
                                        <div class="col-12 col-md-3">
                                            <div class="d-flex">
                                                <div>
                                                    <img src="assets/images/icons/{{ $carrierCode }}.jpg"
                                                        class="img-fluid theme-border-radius" style="width: 40px;"
                                                        alt="{{ $airlineName }}"
                                                        onerror="this.onerror=null;this.src='data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs='">
                                                </div>
                                                <div class="d-flex flex-column ms-2">
                                                    <span class="font-small d-inline-flex mb-0 align-middle fw-bold">{{ $airlineName }}</span>
                                                    <span class="font-small d-inline-flex mb-0 align-middle text-muted">{{ $carrierCode }}-{{ $firstSegment['number'] }}</span>
                                                </div>
                                            </div>
                                            <div>
                                                <a href="#" class="font-small text-primary" data-bs-target="#flightDetailsModal" data-bs-toggle="modal">Flight Details</a>
                                            </div>
                                        </div>
                                        <div class="col-4 col-md-2">
                                            <div class="fw-bold">{{ \Carbon\Carbon::parse($firstSegment['departure']['at'])->format('H:i') }}</div>
                                            <div class="font-small text-muted">{{ $firstSegment['departure']['iataCode'] }}</div>
                                        </div>
                                        <div class="col-4 col-md-2 text-center">
                                            <div class="font-small">{{ $duration }}</div>
                                            <div class="position-relative my-1">
                                                <hr class="m-0">
                                                <span class="position-absolute top-50 start-50 translate-middle bg-white px-1 font-xsmall text-muted">
                                                    {{ count($itinerary['segments']) - 1 }} Stop(s)
                                                </span>
                                            </div>
                                            <div class="font-small {{ count($itinerary['segments']) == 1 ? 'text-success' : 'text-warning' }}">
                                                {{ count($itinerary['segments']) == 1 ? 'Non Stop' : 'Connecting' }}
                                            </div>
                                        </div>
                                        <div class="col-4 col-md-2">
                                            <div class="fw-bold">{{ \Carbon\Carbon::parse($lastSegment['arrival']['at'])->format('H:i') }}</div>
                                            <div class="font-small text-muted">{{ $lastSegment['arrival']['iataCode'] }}</div>
                                        </div>
                                        <div class="col-12 col-md-3 text-center mt-md-0 mt-2">
                                            <div class="fw-bold h5 mb-1 text-primary">
                                                <span class="font-small text-muted me-1">USD</span>{{ number_format($offer['price']['total']) }}
                                            </div>
                                            <button type="submit" class="btn-select btn btn-effect" onclick="window.location.href='{{ route('booking.review') }}';">
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
                @endif
            </div>
        </div>
    </div>
    @endif

</div>

@push('scripts')

    <script>
        // Listen for Livewire loading events
        document.addEventListener('livewire:loading', function (event) {
            const preloader = document.getElementById('searchPreloader');
            preloader.style.display = 'block';
            document.body.style.overflow = 'hidden';
        });

        document.addEventListener('livewire:loaded', function (event) {
            const preloader = document.getElementById('searchPreloader');
            preloader.style.display = 'none';
            document.body.style.overflow = '';
        });
    </script>
@endpush
