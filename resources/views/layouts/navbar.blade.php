<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <a class="navbar-brand brand-logo" href="{{ route('dashboard') }}">
            <img src="{{ asset('template/images/logo.svg') }}" alt="logo"/>
        </a>
        <a class="navbar-brand brand-logo-mini" href="#">
            <img src="{{ asset('template/images/logo-mini.svg') }}" alt="logo"/>
        </a>
    </div>

    <div class="navbar-menu-wrapper d-flex align-items-stretch">

        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
        </button>

        <ul class="navbar-nav navbar-nav-right">

            <!-- PROFILE -->
            <li class="nav-item nav-profile dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <div class="nav-profile-img">
                        <img src="{{ asset('template/images/faces/face1.jpg') }}" alt="image">
                        <span class="availability-status online"></span>
                    </div>
                    <div class="nav-profile-text">
                        <p class="mb-1 text-black">{{ auth()->user()->name }}</p>
                    </div>
                </a>
                <div class="dropdown-menu navbar-dropdown">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="mdi mdi-logout me-2 text-primary"></i> Logout
                        </button>
                    </form>

                </div>
            </li>

            <!-- FULLSCREEN -->
            <li class="nav-item d-none d-lg-block full-screen-link">
                <a class="nav-link">
                    <i class="mdi mdi-fullscreen" id="fullscreen-button"></i>
                </a>
            </li>

        </ul>

        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center"
                type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>

    </div>
</nav>
