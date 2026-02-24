
<div class="flightworld-root">
    <!-- preloader area -->
    <div class="preloader">
        <div class="d-table">
            <div class="d-table-cell">
                <div class="load-spinner">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div>
    </div>
    <div class="pagewrap" id="video-page">
        <div class="head-wrapper">
            <div class="video-image">
                <video autoplay muted loop class="background-video" src="assets/images/hero/video.mp4">
                </video>
            </div>
            <!-- page header section -->
            <header class="header position-relative z-in-2" id="home">
                <div class="container">
                    <nav class="navbar navbar-expand-md navbar-light py-0 px-0">
                        <a class="navbar-brand" href="index.html"><img src="assets/images/logo.png" alt="Brand Logo"
                                title="Brand Logo" class="img-fluid"></a>
                        <button class="navbar-toggler px-1 btn rounded-0" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto">
                                <li class="nav-item">
                                    <div class="dropdown-container pe-md-4">
                                        <div class="dropdown-toggle click-dropdown">
                                            <i class="bi bi-currency-exchange"></i> Currency
                                        </div>
                                        <div class="dropdown-menu">
                                            <ul>
                                                <li class="nav-item"><a class="dropdown-item" href="#"><span
                                                            class="flag in"></span>INR</a></li>
                                                <li class="nav-item"><a class="dropdown-item" href="#"><span
                                                            class="flag us"></span>USD</a></li>
                                                <li class="nav-item"><a class="dropdown-item" href="#"><span
                                                            class="flag er"></span>EUR</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <div class="dropdown-container pe-md-4">
                                        <div class="dropdown-toggle click-dropdown">
                                            <i class="bi bi-translate"></i> Language
                                        </div>
                                        <div class="dropdown-menu">
                                            <ul>
                                                <li class="nav-item"><a class="dropdown-item" href="#"><span
                                                            class="flag us"></span>English</a></li>
                                                <li class="nav-item"><a class="dropdown-item" href="#"><span
                                                            class="flag ru"></span>Rusian</a></li>
                                                <li class="nav-item"><a class="dropdown-item" href="#"><span
                                                            class="flag fr"></span>French</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <div class="dropdown-container">
                                        <div class="dropdown-toggle click-dropdown">
                                            <i class="bi bi-person-circle"></i> Account
                                        </div>
                                        <div class="dropdown-menu">
                                            <ul>
                                                <li class="nav-item"><a class="dropdown-item"
                                                        href="signin.html">Login</a></li>
                                                <li class="nav-item"><a class="dropdown-item"
                                                        href="signup.html">Register</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </header>
            <!-- Search engine section -->
            <section class="position-relative z-in-2 content">
                <div class="container">
                    <div class="row" data-aos="fade-up">
                        <div class="col-12 mt-5">
                            <h2 class="h4 text-center text-white mb-4">The best tour experience</h2>
                            <h1 class="h1 text-center theme-text-white fw-bold theme-text-shadow mb-4">Find and book
                                best <span class="theme-text-primary" id="changingword">flights</span></h1>
                            <p class="font-small text-white text-center px-5 max-1">Curabitur nunc erat, consequat in
                                erat ut, congue bibendum nulla. Suspendisse id pharetra lacus,
                                et hendrerit mi quis leo elementum.</p>
                        </div>
                    </div>
                </div>
            </section>
            <!-- search engine flight-->
            <div class="flight-search" data-aos="fade-up">
                <div class="container">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs border-0" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" id="oneway-tab" data-bs-toggle="tab"
                                data-bs-target="#oneway" type="button" role="tab" aria-controls="oneway"
                                aria-selected="true">
                                <span
                                    class="d-inline-block icon-20 rounded-circle bg-white align-middle me-2"></span>One-way
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="return-tab" data-bs-toggle="tab" data-bs-target="#return"
                                type="button" role="tab" aria-controls="return" aria-selected="false">
                                <span
                                    class="d-inline-block icon-20 rounded-circle bg-white align-middle me-2"></span>Return
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="multiCity-tab" data-bs-toggle="tab" data-bs-target="#multiCity"
                                type="button" role="tab" aria-controls="multiCity" aria-selected="false">
                                <span
                                    class="d-inline-block icon-20 rounded-circle bg-white align-middle me-2"></span>Multi-city
                            </button>
                        </li>
                    </ul>
                    <!-- Tab content -->
                    <div class="tab-content">
                        <!-- oneway search -->
                        <div id="oneway" class="tab-pane active">
                            <div class="row">
                                <div class="col-12">
                                    <div class="search-pan row mx-0 theme-border-radius">
                                        <div class="col-12 col-lg-4 col-xl-2 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-2">
                                            <div class="form-group">
                                                <i class="bi bi-geo-alt-fill position-absolute h2 icon-pos"></i>
                                                <input type="text" class="form-control ps-5" id="onewayOrigin"
                                                    placeholder="Origin">
                                                <button class="pos-swap"><i
                                                        class="bi bi-arrow-left-right pl-1"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-4 col-xl-2 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-2">
                                            <div class="form-group">
                                                <i class="bi bi-geo-alt-fill position-absolute h2 icon-pos"></i>
                                                <input type="text" class="form-control ps-5" id="onewayDestination"
                                                    placeholder="Destination">
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-4 col-xl-3 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-0 pe-xl-2">
                                            <div class="form-control form-group d-flex">
                                                <i class="bi bi-calendar3 position-absolute h2 icon-pos"></i>
                                                <span class="dep-date-input">
                                                    <input type="text" class="cal-input" placeholder="Depart Date"
                                                        id="datepicker">
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-6 col-xl-3 ps-0 mb-2 mb-lg-0 mb-xl-0 pe-0 pe-lg-2">
                                            <div class="dropdown" id="myDD">
                                                <button class="dropdown-toggle form-control" type="button"
                                                    id="travellerInfoOneway" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i
                                                        class="bi bi-person-lines-fill position-absolute h2 icon-pos"></i>
                                                    <span class="text-truncate">1 Traveller(s), Economy </span>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="travellerInfoOneway">
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
                                                                    class="me-2">Business </label>
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
                                        <div class="col-12 col-lg-6 col-xl-2 px-0">
                                            <button type="submit" class="btn btn-search"
                                                onclick="window.location.href='flight-listing-oneway.html';">
                                                <span class="fw-bold">Search</span>
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
                        <div id="return" class="tab-pane fade">
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                    <div class="search-pan row mx-0 theme-border-radius">
                                        <div class="col-12 col-lg-4 col-xl-2 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-2">
                                            <div class="form-group">
                                                <i class="bi bi-geo-alt-fill position-absolute h2 icon-pos"></i>
                                                <input type="text" class="form-control ps-5" id="returnOrigin"
                                                    placeholder="Origin">
                                                <button class="pos-swap"><i
                                                        class="bi bi-arrow-left-right pl-1"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-4 col-xl-2 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-2">
                                            <div class="form-group">
                                                <i class="bi bi-geo-alt-fill position-absolute h2 icon-pos"></i>
                                                <input type="text" class="form-control ps-5" id="returnDestination"
                                                    placeholder="Destination">
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-4 col-xl-3 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-0 pe-xl-2">
                                            <div class="form-control form-group d-flex">
                                                <i class="bi bi-calendar3 position-absolute h2 icon-pos"></i>
                                                <span class="dep-date-input">
                                                    <input type="text" class="cal-input" placeholder="Depart Date"
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
                                                    id="travellerInfoReturn" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i
                                                        class="bi bi-person-lines-fill position-absolute h2 icon-pos"></i>
                                                    <span class="text-truncate">1 Traveller(s), Economy </span>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="travellerInfoReturn">
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
                                                                    class="me-2">Business </label>
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
                        <div id="multiCity" class="tab-pane fade">
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                    <div class="search-pan row mx-0 theme-border-radius">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-12 col-lg-6 col-xl-3 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-2">
                                                    <div class="form-group">
                                                        <i class="bi bi-geo-alt-fill position-absolute h2 icon-pos"></i>
                                                        <input type="text" class="form-control ps-5" id="multiOrigin"
                                                            placeholder="Origin">
                                                    </div>
                                                </div>
                                                <div
                                                    class="col-12 col-lg-6 col-xl-3 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-0 pe-xl-2">
                                                    <div class="form-group">
                                                        <i class="bi bi-geo-alt-fill position-absolute h2 icon-pos"></i>
                                                        <input type="text" class="form-control ps-5"
                                                            id="multiDestination" placeholder="Destination">
                                                    </div>
                                                </div>
                                                <div
                                                    class="col-12 col-lg-6 col-xl-2 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-2 pe-xl-2">
                                                    <div class="form-control form-group d-flex">
                                                        <i class="bi bi-calendar3 position-absolute h2 icon-pos"></i>
                                                        <span class="dep-date-input">
                                                            <input type="text" class="cal-input"
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
                                                                                data-quantity="minus"
                                                                                data-field="onewayAdult">
                                                                                <i class="bi bi-dash"></i>
                                                                            </button>
                                                                        </div>
                                                                        <input class="input-group-field" type="number"
                                                                            name="onewayAdult" value="0">
                                                                        <div class="input-group-button">
                                                                            <button type="button" class="circle"
                                                                                data-quantity="plus"
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
                                                                                data-quantity="minus"
                                                                                data-field="onewayChild">
                                                                                <i class="bi bi-dash"></i>
                                                                            </button>
                                                                        </div>
                                                                        <input class="input-group-field" type="number"
                                                                            name="onewayChild" value="0">
                                                                        <div class="input-group-button">
                                                                            <button type="button" class="circle"
                                                                                data-quantity="plus"
                                                                                data-field="onewayChild">
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
                                                                                data-field="onewayInfant">
                                                                                <i class="bi bi-dash"></i>
                                                                            </button>
                                                                        </div>
                                                                        <input class="input-group-field" type="number"
                                                                            name="onewayInfant" value="0">
                                                                        <div class="input-group-button">
                                                                            <button type="button" class="circle"
                                                                                data-quantity="plus"
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
                                                                        <input type="radio" name="class"
                                                                            value="Business" class="me-2">Business
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
                                            <div class="multi_city_form_wrapper"></div>
                                            <div class="row mt-0 mt-md-0 mt-lg-0 mt-xl-2">
                                                <div class="col-12 col-lg-12 col-xl-8">
                                                    <div class="row">
                                                        <div
                                                            class="col-12 col-lg-4 col-xl-4 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-2">
                                                            <div class="form-group">
                                                                <i
                                                                    class="bi bi-geo-alt-fill position-absolute h2 icon-pos"></i>
                                                                <input type="text" class="form-control ps-5"
                                                                    id="multiOrigin2" placeholder="Origin">
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="col-12 col-lg-4 col-xl-4 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-2">
                                                            <div class="form-group">
                                                                <i
                                                                    class="bi bi-geo-alt-fill position-absolute h2 icon-pos"></i>
                                                                <input type="text" class="form-control ps-5"
                                                                    id="multiDestination2" placeholder="Destination">
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="col-12 col-lg-4 col-xl-4 ps-0 mb-2 mb-xl-0 pe-0 pe-lg-0 pe-xl-2">
                                                            <div class="form-control form-group d-flex">
                                                                <i
                                                                    class="bi bi-calendar3 position-absolute h2 icon-pos"></i>
                                                                <span class="dep-date-input">
                                                                    <input type="text" class="cal-input"
                                                                        placeholder="Depart Date" id="datepicker4">
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-12 col-xl-4 px-0">
                                                    <div class="row">
                                                        <div class="col-12 col-lg-6 col-xl-5 mb-2 mb-md-2 mb-lg-0 d-flex justify-content-center align-items-center"
                                                            id="wrapper">
                                                            <button type="submit" class="btn btn-light font-small"
                                                                id="addMulticityRow">
                                                                <span class="fw-bold">+ Add City</span> </button>
                                                        </div>
                                                        <div class="col-12 col-lg-6 col-xl-7">
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
        </div>
        <!-- body section -->
        <div class="content-section">

            <!-- Promotion banner section -->
            <!-- Travel deals & more start -->
            <!-- Travel deals -->
            <!-- Flight top destination -->
            <!-- how it work -->
            <!-- recommendations content -->

        </div>
        <!-- page footer section -->
        <footer class="footer" id="footerSec">
            <div class="container" data-aos="fade-up">
                <div class="row">
                    <div class="col-12 col-lg-6 pt-5">
                        <img src="assets/images/logoWhite.png" class="img-fluid" alt="Brand color white"
                            title="Brand color white">
                        <p class="text-justify pt-5">Flight World Travel Agent dashboard is a specialized travel service
                            for organizations from flightworld.com</p>
                        <p class="pt-lg-5">Get Latest Deals, Upcoming Flight Offers and Cheap Fare</p>
                        <form class="form">
                            <div class="row">
                                <div class="col-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control py-3 rounded-0" id="inputPassword2"
                                            placeholder="Enter your email address">
                                        <button type="button"
                                            class="btn btn-outline-light rounded-0 custom-btn-subscribe btn-effect">Subscribe</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="col-12 mt-5">
                            <img src="assets/images/icons/tafi.svg" class="img-fluid me-4" alt="tafi" title="tafi">
                            <img src="assets/images/icons/taai.svg" class="img-fluid" alt="taai" title="taai">
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 pt-5">
                        <img src="assets/images/icons/iata.svg" class="img-fluid d-inline-flex" alt="IATA" title="IATA">
                        <p class="fw-bold text-uppercase mb-0 ms-2 d-inline-flex">We are an IATA Certified travel agency
                        </p>
                        <div class="row">
                            <div class="col-md-4 mt-5">
                                <p class="text-uppercase fw-bold mb-4">About Us</p>
                                <ul class="fl-menu">
                                    <li class="nav-item"><a href="about.html">About</a></li>
                                    <li class="nav-item"><a href="contact.html">Contact us</a></li>
                                    <li class="nav-item"><a href="#">Bank Details</a></li>
                                </ul>
                            </div>
                            <div class="col-md-4 mt-5">
                                <p class="text-uppercase fw-bold">Legal</p>
                                <ul class="fl-menu">
                                    <li class="nav-item"><a href="privacy.html">Privacy</a></li>
                                    <li class="nav-item"><a href="#">T&C</a></li>
                                    <li class="nav-item"><a href="#">Disclaimer</a></li>
                                    <li class="nav-item"><a href="#">Privacy and Cookies</a></li>
                                    <li class="nav-item"><a href="#">Legal</a></li>
                                    <li class="nav-item"><a href="#">Help</a></li>
                                    <li class="nav-item"><a href="#">Feedback</a></li>
                                </ul>
                            </div>
                            <div class="col-md-4 mt-5">
                                <p class="text-uppercase fw-bold">Company</p>
                                <ul class="fl-menu">
                                    <li class="nav-item"><a href="#">Partner With Us</a></li>
                                    <li class="nav-item"><a href="#">Services</a></li>
                                    <li class="nav-item"><a href="#">Careers</a></li>
                                    <li class="nav-item"><a href="#">Products</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-0 pt-5">
                    <div class="col-12 border-top border-bottom">
                        <div class="row">
                            <div class="col-12 col-lg-4 text-start py-3">
                                <p class="d-inline-flex text-uppercase mb-0">Follow Us</p>
                                <div class="d-inline-flex social">
                                    <a href="#" class="ps-3"><i class="bi bi-facebook"></i></a>
                                    <a href="#" class="ps-3"><i class="bi bi-twitter-x"></i></a>
                                    <a href="#" class="ps-3"><i class="bi bi-linkedin"></i></a>
                                    <a href="#" class="ps-3"><i class="bi bi-instagram"></i></a>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4 text-lg-center social py-3">
                                <p class="d-inline-flex text-uppercase mb-0">Support Center:</p>
                                <a href="tel:+1234567890" class="botom-link">(769) 25698745</a>
                            </div>
                            <div class="col-12 col-lg-4 text-lg-end social py-3">
                                <a href="mailto:support@example.com" class="botom-link">support@example.com</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <p class="text-center py-5 mb-0 font-small">Â©2025 Flight World Pwt Lcd. All Rights Reserved.
                            Various trademarks are held by their respective owners.
                        </p>
                    </div>
                </div>
            </div>
            <p id="back-top" class="back-to-top bg-dark" style="display: block;">
                <a href="#top"><i class="bi bi-chevron-up"></i></a>
            </p>
        </footer>

    </div>
</div>

