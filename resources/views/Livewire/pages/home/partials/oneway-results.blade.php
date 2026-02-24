<div id="home-oneway-results-content">
        <!-- filter section -->
        <div class="filter-sec py-2">
            <div class="container">
                <div class="d-flex justify-content-between">
                    <div class="fw-bold"><i class="bi bi-funnel pe-2"></i>Filters</div>
                    <div>
                        <a class="btn flt-btn" data-bs-toggle="collapse" href="#collapseExample" role="button"
                            aria-expanded="false" aria-controls="collapseExample">Reset All
                        </a>
                    </div>
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
                                    <input type="text" id="amount" readonly>
                                </p>
                                <div id="slider-range" class="range-bar"></div>
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
                                    <input type="text" id="duration" readonly>
                                </p>
                                <div id="duration-range" class="range-bar"></div>
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
            <!-- flight result page - oneway start -->
            <div class="srp py-2">
                <div class="container">
                    <div class="row">
                        <div class="col-12 my-2">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-bold">Berlin (BER)<i class="bi bi-arrow-right mx-2"></i>London (LHR)
                                    </div>
                                    <div class="mb-1 font-small">Sun, Mar 30</div>
                                </div>
                                <div>
                                    <span class="font-small">Showing 118 of 118 flights.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="row">
                                <div class="col-12 col-md-12 d-none d-md-block">
                                    <div class="row g-0 border theme-border-radius p-2 theme-bg-accent-three">
                                        <div class="col-md-3">
                                            <span class="font-small fw-bold">Airline</span>
                                        </div>
                                        <div class="col-md-2">
                                            <span class="font-small fw-bold">Depart</span>
                                        </div>
                                        <div class="col-md-2">
                                            <span class="font-small fw-bold">Duration</span>
                                        </div>
                                        <div class="col-md-2">
                                            <span class="font-small fw-bold">Arrive</span>
                                        </div>
                                        <div class="col-md-3 text-md-center">
                                            <span class="font-small fw-bold">Price<i class="bi bi-arrow-up"></i>
                                                <input type="checkbox" class="cursor-pointer">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-12 d-md-block d-md-none">
                                    <button class="btn w-100 border theme-border-radius p-2 theme-bg-accent-three"
                                        type="button">
                                        <i class="bi bi-sliders me-2"></i><span class="visible-xs font-medium">Sort
                                            Depart</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3" data-aos="fade-up">
                            <div
                                class="row g-0 border theme-border-radius theme-box-shadow p-2 align-items-center theme-bg-white">
                                <div class="col-12 col-md-3">
                                    <div class="d-flex">
                                        <div>
                                            <img src="assets/images/icons/6E.jpg" class="img-fluid theme-border-radius"
                                                alt="Indigo" title="airline Indigo">
                                        </div>
                                        <div class="d-flex flex-column ms-2">
                                            <span class="font-small d-inline-flex mb-0 align-middle">IndiGo
                                            </span>
                                            <span class="font-small d-inline-flex mb-0 align-middle">6E - 315</span>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="#" class="font-small" data-bs-target="#flightDetailsModal"
                                            data-bs-toggle="modal">Flight Details</a>
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="fw-bold">07:20</div>
                                    <div class="font-small">BER</div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="font-small">03h 15m</div>
                                    <span class="stops"></span>
                                    <div class="font-small">Non Stop</div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="fw-bold">08:20</div>
                                    <div class="font-small">LHR</div>
                                </div>
                                <div class="col-12 col-md-3 text-center mt-md-0 mt-2">
                                    <div class="fw-bold"><i class="bi bi-currency-dollar ms-2"></i>4755</div>
                                    <button type="submit" class="btn-select btn btn-effect"
                                        onclick="window.location.href='review-booking.html';">
                                        <span class="font-small">Select</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- repetable -->
                        <div class="col-12 mb-3" data-aos="fade-up" data-aos-delay="300">
                            <div
                                class="row g-0 border theme-border-radius theme-box-shadow p-2 align-items-center theme-bg-white">
                                <div class="col-12 col-md-3">
                                    <div class="d-flex">
                                        <div>
                                            <img src="assets/images/icons/airasia.jpg"
                                                class="img-fluid theme-border-radius" alt="airasia"
                                                title="airline airasia">
                                        </div>
                                        <div class="d-flex flex-column ms-2">
                                            <span class="font-small d-inline-flex mb-0 align-middle">Airasia
                                            </span>
                                            <span class="font-small d-inline-flex mb-0 align-middle">AE - 315</span>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="#" class="font-small" data-bs-target="#flightDetailsModal"
                                            data-bs-toggle="modal">Flight Details</a>
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="fw-bold">07:20</div>
                                    <div class="font-small">BER</div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="font-small">03h 15m</div>
                                    <span class="stops"></span>
                                    <div class="font-small">Non Stop</div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="fw-bold">08:20</div>
                                    <div class="font-small">LHR</div>
                                </div>
                                <div class="col-12 col-md-3 text-center mt-md-0 mt-2">
                                    <div class="fw-bold"><i class="bi bi-currency-dollar ms-2"></i>4755</div>
                                    <button type="submit" class="btn-select btn btn-effect"
                                        onclick="window.location.href='review-booking.html';">
                                        <span class="font-small">Select</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- repetable -->
                        <div class="col-12 mb-3" data-aos="fade-up" data-aos-delay="300">
                            <div
                                class="row g-0 border theme-border-radius theme-box-shadow p-2 align-items-center theme-bg-white">
                                <div class="col-12 col-md-3">
                                    <div class="d-flex">
                                        <div>
                                            <img src="assets/images/icons/spicejet.jpg"
                                                class="img-fluid theme-border-radius" alt="spicejet"
                                                title="airline spicejet">
                                        </div>
                                        <div class="d-flex flex-column ms-2">
                                            <span class="font-small d-inline-flex mb-0 align-middle">Spicejet
                                            </span>
                                            <span class="font-small d-inline-flex mb-0 align-middle">SP - 315</span>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="#" class="font-small" data-bs-target="#flightDetailsModal"
                                            data-bs-toggle="modal">Flight Details</a>
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="fw-bold">07:20</div>
                                    <div class="font-small">BER</div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="font-small">03h 15m</div>
                                    <span class="stops"></span>
                                    <div class="font-small">Non Stop</div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="fw-bold">08:20</div>
                                    <div class="font-small">LHR</div>
                                </div>
                                <div class="col-12 col-md-3 text-center mt-md-0 mt-2">
                                    <div class="fw-bold"><i class="bi bi-currency-dollar ms-2"></i>4755</div>
                                    <button type="submit" class="btn-select btn btn-effect"
                                        onclick="window.location.href='review-booking.html';">
                                        <span class="font-small">Select</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- repetable -->
                        <div class="col-12 mb-3" data-aos="fade-up" data-aos-delay="300">
                            <div
                                class="row g-0 border theme-border-radius theme-box-shadow p-2 align-items-center theme-bg-white">
                                <div class="col-12 col-md-3">
                                    <div class="d-flex">
                                        <div>
                                            <img src="assets/images/icons/vistara.jpg"
                                                class="img-fluid theme-border-radius" alt="vistara"
                                                title="airline vistara">
                                        </div>
                                        <div class="d-flex flex-column ms-2">
                                            <span class="font-small d-inline-flex mb-0 align-middle">Vistara
                                            </span>
                                            <span class="font-small d-inline-flex mb-0 align-middle">6E - 315</span>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="#" class="font-small" data-bs-target="#flightDetailsModal"
                                            data-bs-toggle="modal">Flight Details</a>
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="fw-bold">07:20</div>
                                    <div class="font-small">BER</div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="font-small">03h 15m</div>
                                    <span class="stops"></span>
                                    <div class="font-small">Non Stop</div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="fw-bold">08:20</div>
                                    <div class="font-small">LHR</div>
                                </div>
                                <div class="col-12 col-md-3 text-center mt-md-0 mt-2">
                                    <div class="fw-bold"><i class="bi bi-currency-dollar ms-2"></i>4755</div>
                                    <button type="submit" class="btn-select btn btn-effect"
                                        onclick="window.location.href='review-booking.html';">
                                        <span class="font-small">Select</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- repetable -->
                        <div class="col-12 mb-3" data-aos="fade-up" data-aos-delay="300">
                            <div
                                class="row g-0 border theme-border-radius theme-box-shadow p-2 align-items-center theme-bg-white">
                                <div class="col-12 col-md-3">
                                    <div class="d-flex">
                                        <div>
                                            <img src="assets/images/icons/thaiAir.jpg"
                                                class="img-fluid theme-border-radius" alt="thaiAir"
                                                title="airline thaiAir">
                                        </div>
                                        <div class="d-flex flex-column ms-2">
                                            <span class="font-small d-inline-flex mb-0 align-middle">ThaiAir
                                            </span>
                                            <span class="font-small d-inline-flex mb-0 align-middle">6E - 315</span>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="#" class="font-small" data-bs-target="#flightDetailsModal"
                                            data-bs-toggle="modal">Flight Details</a>
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="fw-bold">07:20</div>
                                    <div class="font-small">BER</div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="font-small">03h 15m</div>
                                    <span class="stops"></span>
                                    <div class="font-small">Non Stop</div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="fw-bold">08:20</div>
                                    <div class="font-small">LHR</div>
                                </div>
                                <div class="col-12 col-md-3 text-center mt-md-0 mt-2">
                                    <div class="fw-bold"><i class="bi bi-currency-dollar ms-2"></i>4755</div>
                                    <button type="submit" class="btn-select btn btn-effect"
                                        onclick="window.location.href='review-booking.html';">
                                        <span class="font-small">Select</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- repetable -->
                        <div class="col-12 mb-3" data-aos="fade-up" data-aos-delay="300">
                            <div
                                class="row g-0 border theme-border-radius theme-box-shadow p-2 align-items-center theme-bg-white">
                                <div class="col-12 col-md-3">
                                    <div class="d-flex">
                                        <div>
                                            <img src="assets/images/icons/6E.jpg" class="img-fluid theme-border-radius"
                                                alt="Indigo" title="airline Indigo">
                                        </div>
                                        <div class="d-flex flex-column ms-2">
                                            <span class="font-small d-inline-flex mb-0 align-middle">IndiGo
                                            </span>
                                            <span class="font-small d-inline-flex mb-0 align-middle">6E - 315</span>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="#" class="font-small" data-bs-target="#flightDetailsModal"
                                            data-bs-toggle="modal">Flight Details</a>
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="fw-bold">07:20</div>
                                    <div class="font-small">BER</div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="font-small">03h 15m</div>
                                    <span class="stops"></span>
                                    <div class="font-small">Non Stop</div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="fw-bold">08:20</div>
                                    <div class="font-small">LHR</div>
                                </div>
                                <div class="col-12 col-md-3 text-center mt-md-0 mt-2">
                                    <div class="fw-bold"><i class="bi bi-currency-dollar ms-2"></i>4755</div>
                                    <button type="submit" class="btn-select btn btn-effect"
                                        onclick="window.location.href='review-booking.html';">
                                        <span class="font-small">Select</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- repetable -->
                        <div class="col-12 mb-3" data-aos="fade-up" data-aos-delay="300">
                            <div
                                class="row g-0 border theme-border-radius theme-box-shadow p-2 align-items-center theme-bg-white">
                                <div class="col-12 col-md-3">
                                    <div class="d-flex">
                                        <div>
                                            <img src="assets/images/icons/airasia.jpg"
                                                class="img-fluid theme-border-radius" alt="airasia"
                                                title="airline airasia">
                                        </div>
                                        <div class="d-flex flex-column ms-2">
                                            <span class="font-small d-inline-flex mb-0 align-middle">Airasia
                                            </span>
                                            <span class="font-small d-inline-flex mb-0 align-middle">AE - 315</span>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="#" class="font-small" data-bs-target="#flightDetailsModal"
                                            data-bs-toggle="modal">Flight Details</a>
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="fw-bold">07:20</div>
                                    <div class="font-small">BER</div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="font-small">03h 15m</div>
                                    <span class="stops"></span>
                                    <div class="font-small">Non Stop</div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="fw-bold">08:20</div>
                                    <div class="font-small">LHR</div>
                                </div>
                                <div class="col-12 col-md-3 text-center mt-md-0 mt-2">
                                    <div class="fw-bold"><i class="bi bi-currency-dollar ms-2"></i>4755</div>
                                    <button type="submit" class="btn-select btn btn-effect"
                                        onclick="window.location.href='review-booking.html';">
                                        <span class="font-small">Select</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- repetable -->
                        <div class="col-12 mb-3" data-aos="fade-up" data-aos-delay="300">
                            <div
                                class="row g-0 border theme-border-radius theme-box-shadow p-2 align-items-center theme-bg-white">
                                <div class="col-12 col-md-3">
                                    <div class="d-flex">
                                        <div>
                                            <img src="assets/images/icons/spicejet.jpg"
                                                class="img-fluid theme-border-radius" alt="spicejet"
                                                title="airline spicejet">
                                        </div>
                                        <div class="d-flex flex-column ms-2">
                                            <span class="font-small d-inline-flex mb-0 align-middle">Spicejet
                                            </span>
                                            <span class="font-small d-inline-flex mb-0 align-middle">SP - 315</span>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="#" class="font-small" data-bs-target="#flightDetailsModal"
                                            data-bs-toggle="modal">Flight Details</a>
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="fw-bold">07:20</div>
                                    <div class="font-small">BER</div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="font-small">03h 15m</div>
                                    <span class="stops"></span>
                                    <div class="font-small">Non Stop</div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="fw-bold">08:20</div>
                                    <div class="font-small">LHR</div>
                                </div>
                                <div class="col-12 col-md-3 text-center mt-md-0 mt-2">
                                    <div class="fw-bold"><i class="bi bi-currency-dollar ms-2"></i>4755</div>
                                    <button type="submit" class="btn-select btn btn-effect"
                                        onclick="window.location.href='review-booking.html';">
                                        <span class="font-small">Select</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- repetable -->
                        <div class="col-12 mb-3" data-aos="fade-up" data-aos-delay="300">
                            <div
                                class="row g-0 border theme-border-radius theme-box-shadow p-2 align-items-center theme-bg-white">
                                <div class="col-12 col-md-3">
                                    <div class="d-flex">
                                        <div>
                                            <img src="assets/images/icons/vistara.jpg"
                                                class="img-fluid theme-border-radius" alt="vistara"
                                                title="airline vistara">
                                        </div>
                                        <div class="d-flex flex-column ms-2">
                                            <span class="font-small d-inline-flex mb-0 align-middle">Vistara
                                            </span>
                                            <span class="font-small d-inline-flex mb-0 align-middle">6E - 315</span>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="#" class="font-small" data-bs-target="#flightDetailsModal"
                                            data-bs-toggle="modal">Flight Details</a>
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="fw-bold">07:20</div>
                                    <div class="font-small">BER</div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="font-small">03h 15m</div>
                                    <span class="stops"></span>
                                    <div class="font-small">Non Stop</div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="fw-bold">08:20</div>
                                    <div class="font-small">LHR</div>
                                </div>
                                <div class="col-12 col-md-3 text-center mt-md-0 mt-2">
                                    <div class="fw-bold"><i class="bi bi-currency-dollar ms-2"></i>4755</div>
                                    <button type="submit" class="btn-select btn btn-effect"
                                        onclick="window.location.href='review-booking.html';">
                                        <span class="font-small">Select</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- repetable -->
                        <div class="col-12 mb-3" data-aos="fade-up" data-aos-delay="300">
                            <div
                                class="row g-0 border theme-border-radius theme-box-shadow p-2 align-items-center theme-bg-white">
                                <div class="col-12 col-md-3">
                                    <div class="d-flex">
                                        <div>
                                            <img src="assets/images/icons/thaiAir.jpg"
                                                class="img-fluid theme-border-radius" alt="thaiAir"
                                                title="airline thaiAir">
                                        </div>
                                        <div class="d-flex flex-column ms-2">
                                            <span class="font-small d-inline-flex mb-0 align-middle">ThaiAir
                                            </span>
                                            <span class="font-small d-inline-flex mb-0 align-middle">6E - 315</span>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="#" class="font-small" data-bs-target="#flightDetailsModal"
                                            data-bs-toggle="modal">Flight Details</a>
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="fw-bold">07:20</div>
                                    <div class="font-small">BER</div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="font-small">03h 15m</div>
                                    <span class="stops"></span>
                                    <div class="font-small">Non Stop</div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="fw-bold">08:20</div>
                                    <div class="font-small">LHR</div>
                                </div>
                                <div class="col-12 col-md-3 text-center mt-md-0 mt-2">
                                    <div class="fw-bold"><i class="bi bi-currency-dollar ms-2"></i>4755</div>
                                    <button type="submit" class="btn-select btn btn-effect"
                                        onclick="window.location.href='review-booking.html';">
                                        <span class="font-small">Select</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- repetable -->
                    </div>
                </div>
            </div>
        </div>

    <!-- flight details model -->
    <div class="modal fade" id="flightDetailsModal" aria-hidden="true" aria-labelledby="flightDetailsModalLabel"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-6 fw-bold" id="flightDetailsModalLabel">Flight Details</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header">
                            <div class="fw-bold">Berlin (BER)<i class="bi bi-arrow-right mx-2"></i>London (LHR)
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-info table-striped-columns small mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Time frame</th>
                                        <th scope="col">Airline Fee</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>0 hours to 2 hours*</td>
                                        <td>ADULT : Non Refundable</td>
                                    </tr>
                                    <tr>
                                        <td>2 hours to 4 days*</td>
                                        <td>ADULT : â‚¹ 2,999 + â‚¹ 300</td>
                                    </tr>
                                    <tr>
                                        <td>4 days to 365 days*</td>
                                        <td>ADULT : â‚¹ 1,999 + â‚¹ 300</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="p-2 bg-warning-subtle rounded-3 font-extra-small text-justify">
                        <b>*Important:</b> The airline fee is indicative.
                        Flight World does not
                        guarantee the accuracy of this information. All fees mentioned are per passenger. Date change
                        charges are applicable
                        only on selecting the same airline on a new date. The difference in fares between the old and
                        the new booking will
                        also be payable by the user.<br>Please refer to the Date Change Charges section above for
                        details on the number of
                        allowed free date changes, if applicable<br>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- flight details model end -->
</div>
