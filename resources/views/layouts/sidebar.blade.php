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
                    @if(auth()->user()->role === 'vendor')
                        <span class="text-secondary text-small">{{ auth()->user()->vendor->nama_vendor ?? 'Vendor' }}</span>
                    @elseif(auth()->user()->role === 'admin')
                        <span class="text-secondary text-small">Administrator</span>
                    @else
                        <span class="text-secondary text-small">Customer</span>
                    @endif
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
            </a>
        </li>

        @if(auth()->user()->role === 'admin')
            {{-- MENU UNTUK ADMIN --}}
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <span class="menu-title">Dashboard</span>
                    <i class="mdi mdi-home menu-icon"></i>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic">
                    <span class="menu-title">Kelola Buku</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-book-open-variant-outline"></i>
                </a>
                <div class="collapse" id="ui-basic">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('kategori.index') }}">Kategori</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('koleksi-buku.index') }}">Koleksi Buku</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('barang.index') }}">
                    <span class="menu-title">Tag Harga</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('javascript.index') }}">
                    <span class="menu-title">JavaScript Practice</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('javascript.select') }}">
                    <span class="menu-title">Select Practice</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('wilayah.index') }}">
                    <span class="menu-title">Wilayah</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('transaksi.index') }}">
                    <span class="menu-title">Transaksi barang</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.transactions') }}">
                    <span class="menu-title">Daftar Transaksi</span>
                    <i class="mdi mdi-clipboard-list menu-icon"></i>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#customerMenu">
                    <span class="menu-title">Customer</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-account-group"></i>
                </a>
                <div class="collapse" id="customerMenu">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('customer.index') }}">Data Customer</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('customer.create-blob') }}">Tambah (Blob)</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('customer.create-file') }}">Tambah (File)</a>
                        </li>
                    </ul>
                </div>
            </li>

        @elseif(auth()->user()->role === 'vendor')
            {{-- MENU UNTUK VENDOR --}}
            <li class="nav-item">
                <a class="nav-link" href="{{ route('vendor.dashboard') }}">
                    <span class="menu-title">Dashboard Vendor</span>
                    <i class="mdi mdi-store menu-icon"></i>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#menuManagement">
                    <span class="menu-title">Kelola Menu</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-food"></i>
                </a>
                <div class="collapse" id="menuManagement">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('vendor.menu.index') }}">Daftar Menu</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('vendor.menu.create') }}">Tambah Menu</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('vendor.orders.index') }}">
                    <span class="menu-title">Pesanan Lunas</span>
                    <i class="mdi mdi-clipboard-list menu-icon"></i>
                </a>
            </li>

        @else
            {{-- MENU UNTUK CUSTOMER --}}
            <li class="nav-item">
                <a class="nav-link" href="{{ route('landing') }}">
                    <span class="menu-title">Beranda</span>
                    <i class="mdi mdi-home menu-icon"></i>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('cart.index') }}">
                    <span class="menu-title">Keranjang</span>
                    <i class="mdi mdi-cart menu-icon"></i>
                </a>
            </li>
        @endif

    </ul>
</nav>