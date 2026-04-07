@extends('layouts.master')

@section('title', 'Pesanan Lunas')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">📦 Pesanan dengan Status Lunas</h4>
        
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Nama Pemesan</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Metode Bayar</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>{{ $order->idpesanan }}</td>
                        <td>{{ $order->nama }}</td>
                        <td>{{ $order->timestamp->format('d/m/Y H:i') }}</td>
                        <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-info">{{ $order->metode_text }}</span>
                        </td>
                        <td>
                            <a href="{{ route('vendor.orders.show', $order->idpesanan) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> Lihat
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada pesanan lunas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection