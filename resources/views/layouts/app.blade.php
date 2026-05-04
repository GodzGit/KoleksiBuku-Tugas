<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Grayshop - Payment Gateway')</title>
    
    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Font Awesome Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- Custom CSS --}}
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        
        .cart-badge {
            position: relative;
            top: -8px;
            right: 5px;
            font-size: 10px;
        }
        
        .product-card {
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 20px;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .price {
            color: #28a745;
            font-weight: bold;
            font-size: 1.2rem;
        }
        
        .btn-order {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 5px 12px;
        }
        
        .btn-order:hover {
            background: linear-gradient(135deg, #1e7e34 0%, #155724 100%);
            color: white;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
        }
        
        .vendor-card {
            cursor: pointer;
            transition: all 0.3s;
            border-radius: 12px;
        }
        
        .vendor-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .vendor-card.active {
            border: 2px solid #28a745;
            background-color: #f0fff4;
        }
        
        footer {
            margin-top: auto;
        }
        
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        main {
            flex: 1;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b46a0 100%);
        }
        
        .alert {
            border-radius: 10px;
        }
        
        .dropdown-item.text-danger:hover {
            background-color: #dc3545;
            color: white !important;
        }
    </style>
    
    @yield('styles')
</head>
<body>
    
    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('landing') }}">
                🛒 Grayshop
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    
                    {{-- MENU UNTUK SEMUA USER (termasuk customer login) --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('landing') ? 'active' : '' }}" href="{{ route('landing') }}">
                            <i class="fas fa-home"></i> Beranda
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link position-relative {{ request()->routeIs('cart.*') ? 'active' : '' }}" href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-cart"></i> Keranjang
                            @php
                                $cartCount = count(session()->get('cart', []));
                            @endphp
                            @if($cartCount > 0)
                                <span class="badge bg-danger rounded-pill cart-badge">{{ $cartCount }}</span>
                            @endif
                        </a>
                    </li>

                    {{-- MENU UNTUK CUSTOMER YANG LOGIN --}}
                    @auth
                        @if(auth()->user()->role === 'customer')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('history.*') ? 'active' : '' }}" href="{{ route('history.index') }}">
                                    <i class="fas fa-history"></i> History
                                </a>
                            </li>
                        @endif
                    @endauth
                    
                    {{-- MENU UNTUK VENDOR (login sebagai vendor) --}}
                    @auth
                        @if(auth()->user()->role === 'vendor')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('vendor.*') ? 'active' : '' }}" href="{{ route('vendor.dashboard') }}">
                                    <i class="fas fa-store"></i> Vendor Panel
                                </a>
                            </li>
                        @endif
                    @endauth
                    
                    {{-- MENU UNTUK ADMIN (login sebagai admin) --}}
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-cog"></i> Admin
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('dashboard') }}">
                                            <i class="fas fa-tachometer-alt"></i> Dashboard
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('koleksi-buku.index') }}">
                                            <i class="fas fa-book"></i> Kelola Buku
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('kategori.index') }}">
                                            <i class="fas fa-tags"></i> Kelola Kategori
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-sign-out-alt"></i> Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    @endauth

                    {{-- PROFIL & LOGOUT UNTUK CUSTOMER YANG LOGIN --}}
                    @auth
                        @if(auth()->user()->role === 'customer')
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle"></i> {{ auth()->user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-sign-out-alt"></i> Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    @endauth
                    
                    {{-- TOMBOL LOGIN UNTUK GUEST (BELUM LOGIN) --}}
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus"></i> Register
                            </a>
                        </li>
                    @endguest

                </ul>
            </div>
        </div>
    </nav>
    
    {{-- MAIN CONTENT --}}
    <main class="py-4">
        <div class="container">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>
    
    {{-- FOOTER --}}
    <footer class="bg-dark text-white-50 py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">
                        <i class="fas fa-utensils"></i> Laravel - Payment Gateway Demo
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0">
                        &copy; {{ date('Y') }} Dibuat dengan <i class="fas fa-heart text-danger"></i> untuk belajar Laravel
                    </p>
                </div>
            </div>
        </div>
    </footer>
    
    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- jQuery (optional untuk beberapa fitur) --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- Midtrans Snap JS (untuk payment) --}}
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    
    {{-- Custom Scripts --}}
    <script>
        // Auto close alert after 5 seconds
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                let bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
        // Navbar active state handling
        document.querySelectorAll('.nav-link').forEach(link => {
            if (link.href === window.location.href) {
                link.classList.add('active');
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>