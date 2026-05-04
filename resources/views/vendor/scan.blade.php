@extends('layouts.master')

@section('title', 'Scan QR Code Customer')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-body text-center">
                <h4 class="card-title">📷 Scan QR Code Customer</h4>
                <p class="text-muted">Arahkan kamera ke QR code yang ditunjukkan customer</p>

                {{-- TAB PILIHAN --}}
                <ul class="nav nav-tabs mb-3" id="scanTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="kamera-tab" data-bs-toggle="tab" data-bs-target="#kamera" type="button">📷 Scan Kamera</button>
                    </li>
                </ul>

                <div class="tab-content">
                    {{-- TAB KAMERA --}}
                    <div class="tab-pane fade show active" id="kamera" role="tabpanel">
                        <div id="reader" style="width: 100%;"></div>
                    </div>
                </div>

                {{-- RESULT --}}
                <div id="result" class="mt-4" style="display: none;">
                    <div class="alert alert-success">
                        <h5>✅ Hasil Scan</h5>
                        <p><strong>ID Pesanan:</strong> <span id="orderId"></span></p>
                        <p><strong>Customer:</strong> <span id="customerName"></span></p>
                        <p><strong>Status:</strong> <span id="statusBayar"></span></p>
                        <p><strong>Total:</strong> Rp <span id="totalBayar"></span></p>
                    </div>
                    
                    <div class="card mt-3">
                        <div class="card-body">
                            <h6>📋 Menu yang Dipesan:</h6>
                            <table class="table table-sm" id="itemsTable">
                                <thead>
                                    <tr><th>Menu</th><th>Jumlah</th><th>Subtotal</th></tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    
                    <button id="scanLagiBtn" class="btn btn-primary mt-3">Scan Lagi</button>
                </div>

                <div id="errorResult" class="mt-4" style="display: none;">
                    <div class="alert alert-danger">
                        <h5>❌ Gagal</h5>
                        <p><span id="errorMessage"></span></p>
                        <button id="scanLagiErrorBtn" class="btn btn-primary">Scan Lagi</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-page')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    let html5QrCode = null;
    let isScanning = true;
    let isProcessing = false;

    function playBeep() {
        try {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            oscillator.frequency.value = 880;
            gainNode.gain.value = 0.5;
            oscillator.start();
            gainNode.gain.exponentialRampToValueAtTime(0.00001, audioContext.currentTime + 0.5);
            oscillator.stop(audioContext.currentTime + 0.5);
        } catch(e) {}
    }

    function stopScanner() {
        if (html5QrCode && isScanning) {
            html5QrCode.stop().then(() => {
                isScanning = false;
                console.log('Scanner berhenti');
            }).catch(err => console.error(err));
        }
    }

    function restartScanner() {
        const readerElement = document.getElementById('reader');
        
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                html5QrCode.clear();
                readerElement.innerHTML = '';
                initScanner();
            }).catch(() => {
                readerElement.innerHTML = '';
                initScanner();
            });
        } else {
            readerElement.innerHTML = '';
            initScanner();
        }
    }

    function initScanner() {
        const readerElement = document.getElementById('reader');
        readerElement.innerHTML = '';
        
        html5QrCode = new Html5Qrcode("reader");
        html5QrCode.start(
            { facingMode: "environment" },
            { 
                fps: 10, 
                qrbox: { width: 250, height: 250 },
                rememberLastUsedCamera: true
            },
            onScanSuccess,
            onScanError
        ).then(() => {
            isScanning = true;
            isProcessing = false;
            console.log('Scanner QR berhasil dimulai');
        }).catch(err => {
            console.error('Gagal start kamera:', err);
            alert('Tidak dapat mengakses kamera: ' + err);
        });
    }

    function onScanSuccess(decodedText) {
        if (!isScanning || isProcessing) return;
        isProcessing = true;
        
        console.log('QR Code terbaca:', decodedText);
        
        fetch('/vendor/scan/cek/' + encodeURIComponent(decodedText))
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    playBeep();
                    stopScanner();
                    
                    document.getElementById('orderId').innerText = data.pesanan.idpesanan;
                    document.getElementById('customerName').innerText = data.pesanan.nama_customer;
                    document.getElementById('totalBayar').innerText = data.pesanan.total.toLocaleString('id-ID');
                    
                    let statusHtml = '';
                    if (data.pesanan.status_bayar == 1) {
                        statusHtml = '<span class="badge bg-success">Lunas ✅</span>';
                    } else if (data.pesanan.status_bayar == 0) {
                        statusHtml = '<span class="badge bg-warning">Pending</span>';
                    } else {
                        statusHtml = '<span class="badge bg-danger">Batal</span>';
                    }
                    document.getElementById('statusBayar').innerHTML = statusHtml;
                    
                    let itemsHtml = '';
                    data.pesanan.items.forEach(item => {
                        itemsHtml += `<tr>
                            <td>${item.nama_menu}</td>
                            <td>${item.jumlah}</td>
                            <td>Rp ${item.subtotal.toLocaleString('id-ID')}</td>
                        </tr>`;
                    });
                    document.getElementById('itemsTable').querySelector('tbody').innerHTML = itemsHtml;
                    
                    document.getElementById('result').style.display = 'block';
                    document.getElementById('errorResult').style.display = 'none';
                } else {
                    playBeep();
                    document.getElementById('errorMessage').innerText = data.message;
                    document.getElementById('errorResult').style.display = 'block';
                    document.getElementById('result').style.display = 'none';
                    stopScanner();
                }
                isProcessing = false;
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('errorMessage').innerText = 'Terjadi kesalahan: ' + error;
                document.getElementById('errorResult').style.display = 'block';
                document.getElementById('result').style.display = 'none';
                stopScanner();
                isProcessing = false;
            });
    }

    function onScanError(errorMessage) {
        // Tidak ditampilkan
    }

    // Tombol scan lagi
    document.getElementById('scanLagiBtn')?.addEventListener('click', () => {
        location.reload();
    });
    
    document.getElementById('scanLagiErrorBtn')?.addEventListener('click', () => {
        document.getElementById('errorResult').style.display = 'none';
        restartScanner();
    });

    // Mulai scanner
    initScanner();
</script>
@endsection