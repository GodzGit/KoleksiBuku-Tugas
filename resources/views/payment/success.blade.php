@extends('layouts.app')

@section('title', 'Pembayaran Berhasil')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card text-center">
            <div class="card-body">
                <div class="mb-4">
                    <i class="fas fa-check-circle text-success" style="font-size: 64px;"></i>
                </div>
                <h4 class="card-title">✅ Pembayaran Berhasil!</h4>
                <p class="text-muted">Terima kasih telah memesan di Kantin Online</p>
                
                <div class="alert alert-success">
                    <strong>Status: {{ $pesanan->status_text }}</strong>
                </div>
                
                <div class="mt-4">
                    <p>Detail Pesanan:</p>
                    <div class="table-responsive">
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
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="mt-3">
                    <a href="{{ route('landing') }}" class="btn btn-primary">
                        <i class="fas fa-home"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Redirect ke beranda setelah 5 detik
    setTimeout(function() {
        window.location.href = '{{ route("landing") }}';
    }, 5000);
</script>
@endsection