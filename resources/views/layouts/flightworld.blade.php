<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="ThemesLay">

    <base href="{{ url('/') }}/">

    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon.png') }}">
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">

    <title>FlightWorld Home Video - Airline Booking & Search Engine Website Template</title>

    @livewireStyles
</head>

<body class="theme-bg-white" data-loading="false">
    <div class="flightworld-root">
        <!-- preloader area -->
        <div class="preloader" id="searchPreloader" style="display: none;">
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
                    <div class="mt-3 text-center">
                        <h5 class="text-white">Searching for flights...</h5>
                        <p class="text-white-50 small">Please wait while we find the best options for you.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- page header section -->
        <header class="header header-light">
            <div class="container">

                <nav class="navbar navbar-expand-md navbar-light py-0 px-0">
                    <a class="navbar-brand ms-5" href="index.html"><img src="assets/images/logo.png" alt="Brand Logo"
                            title="Brand Logo" class="img-fluid"></a>
                    <button class="navbar-toggler px-1 btn rounded-0" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto">


                            <li class="nav-item">
                                <div class="dropdown-container">
                                    <div class="dropdown-toggle click-dropdown">
                                        <i class="bi bi-person-circle"></i> Account
                                    </div>
                                        <div class="dropdown-menu">
                                            <ul>
                                                @auth
                                                    <li class="nav-item">
                                                        <form method="POST" action="{{ route('logout') }}">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item">Logout</button>
                                                        </form>
                                                    </li>
                                                @endauth
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                        </ul>
                    </div>
                </nav>

            </div>
        </header>


        {{ $slot }}

        <footer class="footer" id="footerSec">
            <div class="container" data-aos="fade-up">
                <div class="row">
                    <div class="col-12 col-lg-6 pt-5">
                        <img src="assets/images/logo.png" class="img-fluid" alt="Brand color white"
                            title="Brand color white">
                    </div>
                    <div class="col-12 col-lg-6 pt-5">
                        <p class="fw-bold text-uppercase mb-0 ms-2 d-inline-flex">Flight World Travel Agent dashboard is
                            a specialized travel service
                            for organizations from flightworld.com
                        </p>
                    </div>
                </div>
            </div>
            <p id="back-top" class="back-to-top bg-dark" style="display: block;">
                <a href="#top"><i class="bi bi-chevron-up"></i></a>
            </p>
        </footer>
    </div>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-ui.js') }}"></script>
    <script src="{{ asset('assets/js/counter.js') }}"></script>
    <script src="{{ asset('assets/js/aos.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/addCity-form.js') }}"></script>

    @stack('scripts')
    @livewireScripts
</body>

</html>
