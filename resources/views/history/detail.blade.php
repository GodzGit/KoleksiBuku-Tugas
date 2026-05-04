@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $pesanan->idpesanan)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="card-title">📱 QR Code Pesanan</h4>
                <p class="text-muted">Pesanan #{{ $pesanan->idpesanan }}</p>

                <div class="bg-light p-4 rounded d-inline-block mx-auto">
                    {!! QrCode::size(200)->generate($pesanan->idpesanan) !!}
                </div>

                <p class="mt-3 text-muted">Scan QR code ini di kasir vendor untuk memproses pesanan</p>

                <div class="mt-4 text-start">
                    <h5>Detail Pesanan:</h5>
                    <table class="table table-sm">
                        @foreach($pesanan->detailPesanan as $detail)
                        <tr>
                            <td>{{ $detail->menu->nama_menu }} x{{ $detail->jumlah }}</td>
                            <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                        <tr class="table-active">
                            <th>Total</th>
                            <th class="text-end">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</th>
                        </table>
                    </table>
                </div>

                <div class="mt-3">
                    <a href="{{ route('history.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection