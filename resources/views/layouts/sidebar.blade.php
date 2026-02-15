<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">

        <!-- PROFILE -->
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    <img src="{{ asset('template/images/faces/face1.jpg') }}" alt="profile" />
                    <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <p class="mb-1 text-black">{{ auth()->user()->name }}</p>
                    <span class="text-secondary text-small">Administrator</span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
            </a>
        </li>

        <!-- DASHBOARD -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>

        <!-- UI ELEMENT -->
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic">
                <span class="menu-title">kelola buku</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-book-open-variant-outline"></i>
            </a>
            <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('kategori.index') }}">Kategori</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('koleksi-buku.index') }}">koleksi buku</a>
                    </li>
                </ul>
            </div>
        </li>

    </ul>
</nav>
