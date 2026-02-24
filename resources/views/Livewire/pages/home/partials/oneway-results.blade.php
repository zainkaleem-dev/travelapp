<div id="home-oneway-results-content">
    <div class="filter-sec py-2">
        <div class="container">
            <div class="d-flex justify-content-between">
                <div class="fw-bold"><i class="bi bi-funnel pe-2"></i>Filters</div>
                <div>
                    <a class="btn flt-btn" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">Reset All</a>
                </div>
            </div>
        </div>
    </div>

    <div class="border-bottom theme-bg-white">
        <div class="container">
            <div class="collapse" id="collapseExample">
                <div class="row mb-3 pb-2 border-bottom">
                    <div class="col-12 col-md-3 col-lg-2 py-2 border-end">
                        <span class="d-flex mb-3">Price</span>
                        <div class="price-range-slider">
                            <p class="range-value"><input type="text" id="amount" readonly></p>
                            <div id="slider-range" class="range-bar"></div>
                        </div>
                    </div>

                    <div class="col-12 col-md-3 col-lg-3 py-2 border-end">
                        <span class="d-flex mb-3">Arrival Time</span>
                        <ul>
                            <li><label class="check-wrap">Before 6AM<input type="checkbox"><span class="checkmark"></span></label></li>
                            <li><label class="check-wrap">6AM-12 Noon<input type="checkbox"><span class="checkmark"></span></label></li>
                            <li><label class="check-wrap">12 Noon-6PM<input type="checkbox"><span class="checkmark"></span></label></li>
                            <li><label class="check-wrap">After 6PM<input type="checkbox"><span class="checkmark"></span></label></li>
                        </ul>
                    </div>

                    <div class="col-12 col-md-3 col-lg-3 py-2 border-end">
                        <span class="d-flex mb-3">Departure Time</span>
                        <ul>
                            <li><label class="check-wrap">Before 6AM<input type="checkbox"><span class="checkmark"></span></label></li>
                            <li><label class="check-wrap">6AM-12 Noon<input type="checkbox"><span class="checkmark"></span></label></li>
                            <li><label class="check-wrap">12 Noon-6PM<input type="checkbox"><span class="checkmark"></span></label></li>
                            <li><label class="check-wrap">After 6PM<input type="checkbox"><span class="checkmark"></span></label></li>
                        </ul>
                    </div>

                    <div class="col-12 col-md-3 col-lg-2 py-2 border-end">
                        <span class="d-flex mb-3">Duration</span>
                        <div class="price-range-slider">
                            <p class="range-value"><input type="text" id="duration" readonly></p>
                            <div id="duration-range" class="range-bar"></div>
                        </div>
                    </div>

                    <div class="col-12 col-md-3 col-lg-2 py-2">
                        <span class="fw-bold">Stops</span>
                        <ul>
                            <li><label class="check-wrap">0 Stop<input type="checkbox"><span class="checkmark"></span></label></li>
                            <li><label class="check-wrap">1 Stop<input type="checkbox"><span class="checkmark"></span></label></li>
                            <li><label class="check-wrap">1+ Stop<input type="checkbox"><span class="checkmark"></span></label></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content-section">
        <div class="srp py-2">
            <div class="container">
                <div class="row">
                    <div class="col-12 my-2">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-bold" id="api-search-route">Berlin (BER) to London (LHR)</div>
                                <div class="mb-1 font-small" id="api-search-date">Sun, Mar 30</div>
                            </div>
                            <div>
                                <span class="font-small" id="api-search-count">Showing 0 of 0 flights.</span>
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
                            <div class="col-md-3 text-md-center"><span class="font-small fw-bold">Price</span></div>
                        </div>
                    </div>
                </div>

                <div id="api-flight-results-list" class="row"></div>
                <div id="dummy-flight-results" class="d-none"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="flightDetailsModal" aria-hidden="true" aria-labelledby="flightDetailsModalLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-6 fw-bold" id="flightDetailsModalLabel">Flight Details</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">Flight fare rules and details are provided by airline at booking step.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
