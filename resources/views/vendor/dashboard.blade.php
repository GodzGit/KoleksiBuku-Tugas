@extends('layouts.master')

@section('title', 'Dashboard Vendor')

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="mb-4">Dashboard Vendor - {{ $vendor->nama_vendor }}</h4>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Total Menu</h5>
                <h2 class="mb-0">{{ $totalMenu }}</h2>
                <p class="mb-0">Menu aktif</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Pesanan Lunas</h5>
                <h2 class="mb-0">{{ $totalPesananLunas }}</h2>
                <p class="mb-0">Pesanan selesai</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">Pendapatan</h5>
                <h2 class="mb-0">Rp {{ number_format($pendapatan, 0, ',', '.') }}</h2>
                <p class="mb-0">Total pendapatan</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Menu Cepat</h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('vendor.menu.index') }}" class="btn btn-primary">
                        <i class="fas fa-utensils"></i> Kelola Menu
                    </a>
                    <a href="{{ route('vendor.orders.index') }}" class="btn btn-success">
                        <i class="fas fa-clipboard-list"></i> Lihat Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection