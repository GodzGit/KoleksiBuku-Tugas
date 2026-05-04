@extends('layouts.master')

@section('title', 'Scan Barcode')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-body text-center">
                <h4 class="card-title">📷 Scan Barcode Barang</h4>

                <ul class="nav nav-tabs mb-3" id="scanTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="kamera-tab" data-bs-toggle="tab" data-bs-target="#kamera" type="button">📷 Scan Kamera</button>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="kamera" role="tabpanel">
                        <div id="reader" style="width: 100%;"></div>
                        <p class="text-muted mt-2">Arahkan kamera ke barcode</p>
                    </div>
                </div>

                <div id="result" class="mt-4" style="display: none;">
                    <div class="alert alert-success">
                        <h5>✅ Hasil Scan</h5>
                        <p><strong>ID Barang:</strong> <span id="id_barang"></span></p>
                        <p><strong>Nama Barang:</strong> <span id="nama_barang"></span></p>
                        <p><strong>Harga:</strong> Rp <span id="harga"></span></p>
                    </div>
                    <button id="scanLagiBtn" class="btn btn-primary">Scan Lagi</button>
                </div>

                <div id="errorResult" class="mt-4" style="display: none;">
                    <div class="alert alert-danger">
                        <h5>❌ Barang Tidak Ditemukan</h5>
                        <p>Kode barcode <strong><span id="kodeError"></span></strong> tidak terdaftar.</p>
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

    function playBeep() {
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
    }

    function stopScanner() {
        if (html5QrCode && isScanning) {
            html5QrCode.stop().then(() => {
                isScanning = false;
                console.log('Scanner berhenti');
            }).catch(err => console.error(err));
        }
    }

    // Fungsi untuk memulai ulang scanner dari awal
    function restartScanner() {
        const readerElement = document.getElementById('reader');
        
        // Bersihkan element reader
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

        // Refresh halaman saat tombol "Scan Lagi" diklik
        document.getElementById('scanLagiErrorBtn')?.addEventListener('click', () => {
            location.reload();
        });
    }

    function initScanner() {
        const readerElement = document.getElementById('reader');
        
        // Kosongkan dulu
        readerElement.innerHTML = '';
        
        html5QrCode = new Html5Qrcode("reader");
        html5QrCode.start(
            { facingMode: "environment" },
            { 
                fps: 10, 
                qrbox: { width: 250, height: 100 },
                rememberLastUsedCamera: true
            },
            onScanSuccess,
            onScanError
        ).then(() => {
            isScanning = true;
            console.log('Scanner berhasil dimulai');
        }).catch(err => {
            console.error('Gagal start kamera:', err);
            alert('Tidak dapat mengakses kamera. Pastikan Anda memberikan izin akses kamera.');
        });
    }

    function onScanSuccess(decodedText, decodedResult) {
        if (!isScanning) return;
        
        console.log('Barcode terbaca:', decodedText);
        processBarcode(decodedText);
    }

    function onScanError(errorMessage) {
        // Tidak perlu ditampilkan ke user
        // console.log('Scan error:', errorMessage);
    }

    function processBarcode(barcode) {
        fetch('/cek-barang/' + encodeURIComponent(barcode))
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    playBeep();
                    stopScanner();
                    document.getElementById('id_barang').innerText = data.barang.id_barang;
                    document.getElementById('nama_barang').innerText = data.barang.nama_barang;
                    document.getElementById('harga').innerText = data.barang.harga.toLocaleString('id-ID');
                    document.getElementById('result').style.display = 'block';
                    document.getElementById('errorResult').style.display = 'none';
                } else {
                    playBeep();
                    document.getElementById('kodeError').innerText = barcode;
                    document.getElementById('errorResult').style.display = 'block';
                    document.getElementById('result').style.display = 'none';
                    stopScanner();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('kodeError').innerText = barcode;
                document.getElementById('errorResult').style.display = 'block';
                document.getElementById('result').style.display = 'none';
                stopScanner();
            });
    }

    // Event listener untuk tombol scan lagi
    document.getElementById('scanLagiBtn')?.addEventListener('click', () => {
        location.reload();
    });
    
    document.getElementById('scanLagiErrorBtn')?.addEventListener('click', () => {
        document.getElementById('errorResult').style.display = 'none';
        restartScanner();
    });

    // Mulai scanner saat halaman load
    window.addEventListener('load', () => {
        initScanner();
    });
</script>
@endsection