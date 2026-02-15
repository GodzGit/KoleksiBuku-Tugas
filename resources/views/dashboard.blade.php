@extends('layouts.master')

@section('title','Dashboard')

@section('style-page')
<link rel="stylesheet" href="{{ asset('template/vendors/chart.js/chart.css') }}">
@endsection

@section('content')
<h1>INI ISI DASHBOARD</h1>

<div class="row">

    <div class="col-md-6">
        <div class="card bg-gradient-primary text-white">
            <div class="card-body">
                <h4>Total Buku</h4>
                <h2>{{ $totalBuku }}</h2>
                <a href="{{ route('koleksi-buku.index') }}" class="btn btn-sm btn-primary">Lihat Buku</a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card bg-gradient-success text-white">
            <div class="card-body">
                <h4>Total Kategori</h4>
                <h2>{{ $totalKategori }}</h2>
                <a href="{{ route('kategori.index') }}" class="btn btn-sm btn-success">Lihat Kategori</a>
            </div>
        </div>
    </div>

</div>

@endsection

@section('js-page')
<script src="{{ asset('template/js/dashboard.js') }}"></script>
@endsection
