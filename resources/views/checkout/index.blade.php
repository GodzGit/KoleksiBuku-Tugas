@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="row">
    <div class="col-md-7">
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title">📝 Data Pemesan</h4>
                <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Pemesan</label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                               value="Guest_{{ str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT) }}" required>
                        <small class="text-muted">Atau masukkan nama Anda</small>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <p class="text-muted">Pilih metode pembayaran nanti di halaman pembayaran</p>
                        <input type="hidden" name="metode_bayar" value="1">
                    </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">🛒 Ringkasan Pesanan</h4>
                <table class="table table-sm">
                    @foreach($cart as $item)
                    <tr>
                        <td>{{ $item['nama_menu'] }} x{{ $item['jumlah'] }}</td>
                        <td class="text-end">Rp {{ number_format($item['harga'] * $item['jumlah'], 0, ',', '.') }}</td>
                    </tr>
                    @if(isset($item['catatan']))
                    <tr>
                        <td colspan="2"><small class="text-muted">Catatan: {{ $item['catatan'] }}</small></td>
                    </tr>
                    @endif
                    @endforeach
                    <tr class="table-active">
                        <th>Total</th>
                        <th class="text-end">Rp {{ number_format($total, 0, ',', '.') }}</th>
                    </tr>
                </table>
                
                <div class="mb-3">
                    <label class="form-label">Catatan (opsional)</label>
                    @foreach($cart as $idmenu => $item)
                    <input type="text" name="catatan[{{ $idmenu }}]" class="form-control form-control-sm mb-2" 
                           placeholder="Catatan untuk {{ $item['nama_menu'] }}">
                    @endforeach
                </div>
                
                <button type="submit" class="btn btn-primary w-100" id="btnCheckout">
                    <i class="fas fa-check-circle"></i> Konfirmasi & Bayar
                </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection