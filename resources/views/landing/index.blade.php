@extends('layouts.app')

@section('title', 'Grayshop - All in One Online Store')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="hero-section bg-primary text-white text-center py-5 mb-4 rounded">
            <h1 class="display-4">Grayshop - All in One Online Store</h1>
            <p class="lead">White atau black market semua penjual ada disini</p>
            <div class="row justify-content-center mt-4">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Cari menu..." id="searchInput">
                        <button class="btn btn-light" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <h3>🔥 Menu Populer</h3>
        <hr>
    </div>
</div>

<div class="row" id="menuContainer">
    @forelse($menus as $menu)
    <div class="col-md-3 col-sm-6 menu-item" data-vendor="{{ $menu->vendor->nama_vendor }}" data-name="{{ $menu->nama_menu }}">
        <div class="card product-card h-100">
            @if($menu->path_gambar)
                <img src="{{ asset($menu->path_gambar) }}" alt="{{ $menu->nama_menu }}" style="height: 150px; object-fit: cover;">
            @else
                <div class="card-img-top bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center" style="height: 150px;">
                    <i class="fas fa-utensils fa-3x text-secondary"></i>
                </div>
            @endif
            <div class="card-body">
                <h5 class="card-title">{{ $menu->nama_menu }}</h5>
                <p class="card-text text-muted small">
                    <i class="fas fa-store"></i> {{ $menu->vendor->nama_vendor }}
                </p>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="price">Rp {{ number_format($menu->harga, 0, ',', '.') }}</span>
                    <form action="{{ route('cart.add', $menu->idmenu) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-order btn-sm">
                            <i class="fas fa-cart-plus"></i> Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info text-center">Belum ada menu tersedia.</div>
    </div>
    @endforelse
</div>

<div class="row mt-5">
    <div class="col-12">
        <h3>🏪 Vendor Kami</h3>
        <hr>
    </div>
    @foreach($vendors as $vendor)
    <div class="col-md-2 col-4 mb-3">
        <div class="card text-center vendor-card" data-vendor-id="{{ $vendor->idvendor }}" style="cursor: pointer;">
            <div class="card-body">
                <i class="fas fa-store fa-2x mb-2"></i>
                <h6 class="mb-0">{{ $vendor->nama_vendor }}</h6>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

@section('scripts')
<script>
    // Search filter
    document.getElementById('searchInput')?.addEventListener('keyup', function() {
        let searchText = this.value.toLowerCase();
        document.querySelectorAll('.menu-item').forEach(item => {
            let name = item.getAttribute('data-name')?.toLowerCase() || '';
            let vendor = item.getAttribute('data-vendor')?.toLowerCase() || '';
            item.style.display = (name.includes(searchText) || vendor.includes(searchText)) ? '' : 'none';
        });
    });
    
    // Filter by vendor
    document.querySelectorAll('.vendor-card').forEach(card => {
        card.addEventListener('click', function() {
            let vendorId = this.getAttribute('data-vendor-id');
            if (vendorId === 'all') {
                document.querySelectorAll('.menu-item').forEach(item => item.style.display = '');
            } else {
                document.querySelectorAll('.menu-item').forEach(item => {
                    let itemVendor = item.getAttribute('data-vendor')?.toLowerCase() || '';
                    let selectedVendor = card.querySelector('h6')?.innerText.toLowerCase() || '';
                    item.style.display = itemVendor === selectedVendor ? '' : 'none';
                });
            }
            
            // Highlight active vendor
            document.querySelectorAll('.vendor-card').forEach(c => c.classList.remove('border-primary'));
            this.classList.add('border-primary');
        });
    });
</script>
@endsection