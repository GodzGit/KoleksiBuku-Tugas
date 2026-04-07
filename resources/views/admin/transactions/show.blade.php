@extends('layouts.master')

@section('title', 'Detail Transaksi #' . $transaction->idpesanan)

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">📋 Detail Transaksi</h4>
                <table class="table table-bordered">
                    <tr><th width="150">ID Pesanan</th><td>{{ $transaction->idpesanan }}</td></tr>
                    <tr><th>Pemesan</th><td>{{ $transaction->nama }}</td></tr>
                    <tr><th>Tanggal</th><td>{{ $transaction->timestamp->format('d/m/Y H:i:s') }}</td></tr>
                    <tr><th>Status</th><td>
                        @if($transaction->status_bayar == 0)
                            <span class="badge bg-warning">Pending</span>
                        @elseif($transaction->status_bayar == 1)
                            <span class="badge bg-success">Lunas ✅</span>
                        @else
                            <span class="badge bg-danger">Batal</span>
                        @endif
                     </td></tr>
                    <tr><th>Metode Bayar</th><td>
                        @if($transaction->metode_bayar == 1) Virtual Account (BCA)
                        @elseif($transaction->metode_bayar == 2) QRIS
                        @else - @endif
                     </td></tr>
                    <tr><th>Total</th><td><strong>Rp {{ number_format($transaction->total, 0, ',', '.') }}</strong></td></tr>
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
                        <tr><th>Menu</th><th>Vendor</th><th>Jumlah</th><th>Harga</th><th>Subtotal</th></tr>
                    </thead>
                    <tbody>
                        @foreach($transaction->detailPesanan as $detail)
                        <tr>
                            <td>{{ $detail->menu->nama_menu }}</td>
                            <td>{{ $detail->menu->vendor->nama_vendor ?? '-' }}</td>
                            <td>{{ $detail->jumlah }}</td>
                            <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @if($detail->catatan)
                        <tr><td colspan="5"><small class="text-muted">Catatan: {{ $detail->catatan }}</small></td></tr>
                        @endif
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-active">
                            <th colspan="4" class="text-end">Total:</th>
                            <th>Rp {{ number_format($transaction->total, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <a href="{{ route('admin.transactions') }}" class="btn btn-secondary">
            <i class="mdi mdi-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection