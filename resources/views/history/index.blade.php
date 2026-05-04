@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">📜 Riwayat Pesanan Saya</h4>

        @if($pesanans->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pesanans as $pesanan)
                        <tr>
                            <td>{{ $pesanan->idpesanan }}</td>
                            <td>{{ $pesanan->timestamp->format('d/m/Y H:i') }}</td>
                            <td>Rp {{ number_format($pesanan->total, 0, ',', '.') }}</td>
                            <td>
                                @if($pesanan->status_bayar == 1)
                                    <span class="badge bg-success">Lunas ✅</span>
                                @elseif($pesanan->status_bayar == 0)
                                    <span class="badge bg-warning">Pending</span>
                                @else
                                    <span class="badge bg-danger">Batal</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('history.show', $pesanan->idpesanan) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-qrcode"></i> Lihat QR Code
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info text-center">
                Belum ada pesanan. <a href="{{ route('landing') }}">Belanja sekarang</a>
            </div>
        @endif
    </div>
</div>
@endsection