@extends('layouts.master')

@section('title', 'Detail Pesanan #' . $pesanan->idpesanan)

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">📋 Detail Pesanan</h4>
                <table class="table">
                    <tr>
                        <th>ID Pesanan:</th>
                        <td>{{ $pesanan->idpesanan }}</td>
                    </tr>
                    <tr>
                        <th>Nama Pemesan:</th>
                        <td>{{ $pesanan->nama }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal:</th>
                        <td>{{ $pesanan->timestamp->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            <span class="badge bg-{{ $pesanan->status_badge }}">
                                {{ $pesanan->status_text }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Metode Bayar:</th>
                        <td>{{ $pesanan->metode_text }}</td>
                    </tr>
                    <tr>
                        <th>Total:</th>
                        <td><strong>Rp {{ number_format($pesanan->total, 0, ',', '.') }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">🛒 Item Pesanan</h4>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Menu</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pesanan->detailPesanan as $detail)
                        <tr>
                            <td>{{ $detail->menu->nama_menu }}</td>
                            <td>{{ $detail->jumlah }}</td>
                            <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @if($detail->catatan)
                        <tr>
                            <td colspan="4"><small class="text-muted">Catatan: {{ $detail->catatan }}</small></td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-active">
                            <th colspan="3" class="text-end">Total:</th>
                            <th>Rp {{ number_format($pesanan->total, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <a href="{{ route('vendor.orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection