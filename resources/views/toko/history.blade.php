@extends('layouts.master')

@section('title', 'History Kunjungan')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">📜 History Kunjungan Toko</h4>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Sales</th>
                        <th>Nama Toko</th>
                        <th>Alamat Toko</th>
                        <th>Lokasi Sales (Lat, Lng)</th>
                        <th>Jarak</th>
                        <th>Akurasi Sales</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kunjungans as $kunjungan)
                    <tr>
                        <td>{{ date('d/m/Y H:i:s', strtotime($kunjungan->created_at)) }}</td>
                        <td>{{ $kunjungan->nama_sales }}</td>
                        <td>{{ $kunjungan->toko->nama_toko ?? '-' }}</td>
                        <td>{{ $kunjungan->toko->alamat ?? '-' }}</td>
                        <td>{{ $kunjungan->latitude_sales }}, {{ $kunjungan->longitude_sales }}</td>
                        <td>{{ number_format($kunjungan->jarak_hitung, 2) }} meter</td>
                        <td>{{ $kunjungan->accuracy_sales }} meter</td>
                        <td>
                            @if($kunjungan->status == 'diterima')
                                <span class="badge bg-success">✅ Diterima</span>
                            @else
                                <span class="badge bg-danger">❌ Ditolak</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada history kunjungan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection