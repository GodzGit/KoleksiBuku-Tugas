@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body text-center">
                <h4 class="card-title">💳 Pembayaran</h4>
                <p class="text-muted">Pesanan #{{ $pesanan->idpesanan }}</p>
                
                <div class="alert alert-info">
                    <strong>Total Pembayaran:</strong><br>
                    <span style="font-size: 24px;">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</span>
                </div>
                
                <div class="mb-4">
                    <p>
                        <strong>
                            @if($pesanan->metode_bayar == 1)
                                Pilih metode pembayaran
                            @else
                                📱 QRIS
                            @endif
                        </strong>
                    </p>
                </div>
                
                <button id="payButton" class="btn btn-success btn-lg">
                    <i class="fas fa-credit-card"></i> Bayar Sekarang
                </button>
                
                <div class="mt-3">
                    <small class="text-muted">Anda akan diarahkan ke halaman pembayaran Midtrans</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let paymentAttempts = 0;
    const maxAttempts = 3;
    
    document.getElementById('payButton').onclick = function() {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        
        fetch('{{ route("payment.process", $pesanan->idpesanan) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.token) {
                snap.pay(data.token, {
                    onSuccess: function(result) {
                        console.log('Payment Success:', result);
                        
                        // Kirim request ke server untuk update status pesanan
                        fetch('{{ route("payment.update-status", $pesanan->idpesanan) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ 
                                status: 'success',
                                transaction_id: result.transaction_id,
                                payment_type: result.payment_type
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log('Update status response:', data);
                            window.location.href = '{{ route("payment.success", $pesanan->idpesanan) }}';
                        })
                        .catch(error => {
                            console.error('Update status error:', error);
                            // Tetap redirect meskipun update status gagal
                            window.location.href = '{{ route("payment.success", $pesanan->idpesanan) }}';
                        });
                    },
                    onPending: function(result) {
                        console.log('Payment Pending:', result);
                        // Mulai polling untuk cek status
                        checkPaymentStatus();
                    },
                    onError: function(result) {
                        console.log('Payment Error:', result);
                        alert('Pembayaran gagal: ' + JSON.stringify(result));
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-credit-card"></i> Bayar Sekarang';
                    }
                });
            } else {
                alert('Error: ' + data.error);
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-credit-card"></i> Bayar Sekarang';
            }
        })
        .catch(error => {
            console.error('Fetch Error:', error);
            alert('Terjadi kesalahan: ' + error);
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-credit-card"></i> Bayar Sekarang';
        });
    };
    
    // Fungsi untuk mengecek status pembayaran secara periodik
    function checkPaymentStatus() {
        let interval = setInterval(function() {
            fetch('{{ route("payment.check", $pesanan->idpesanan) }}')
                .then(response => response.json())
                .then(data => {
                    if (data.is_lunas) {
                        clearInterval(interval);
                        window.location.href = '{{ route("payment.success", $pesanan->idpesanan) }}';
                    }
                })
                .catch(error => console.error('Status check error:', error));
        }, 3000); // Cek setiap 3 detik
        
        // Hentikan pengecekan setelah 2 menit
        setTimeout(() => clearInterval(interval), 120000);
    }
</script>
@endsection