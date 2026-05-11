@extends('layouts.master')

@section('title', 'Kunjungan Toko')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">📍 Kunjungan Toko</h4>
                
                {{-- Step 1: Scan Barcode --}}
                <div id="stepScan">
                    <label>Scan Barcode Toko</label>
                    <div id="reader" style="width: 100%;"></div>
                    <input type="hidden" id="id_toko">
                </div>
                
                {{-- Step 2: Data Toko & Ambil Lokasi --}}
                <div id="stepLokasi" style="display: none;">
                    <div class="mb-3">
                        <label>Nama Sales</label>
                        <input type="text" id="nama_sales" class="form-control" placeholder="Masukkan nama Anda" required>
                    </div>
                    <div class="alert alert-info" id="infoToko"></div>
                    
                    <button id="ambilLokasiBtn" class="btn btn-primary w-100 mb-3">
                        📍 Ambil Lokasi Saya
                    </button>
                    
                    <div id="lokasiResult" style="display: none;">
                        <div class="alert alert-secondary">
                            <p><strong>Lokasi Sales:</strong></p>
                            <p>Lat: <span id="sales_lat"></span></p>
                            <p>Lng: <span id="sales_lng"></span></p>
                            <p>Akurasi: <span id="sales_acc"></span> meter</p>
                        </div>
                        
                        <button id="submitKunjunganBtn" class="btn btn-success w-100">
                            ✅ Submit Kunjungan
                        </button>
                    </div>
                </div>
                
                {{-- Step 3: Hasil --}}
                <div id="stepHasil" style="display: none;">
                    <div id="hasilAlert"></div>
                    <div class="mt-3">
                        <a href="{{ route('toko.kunjungan') }}" class="btn btn-primary">Kunjungan Baru</a>
                        <a href="{{ route('toko.history') }}" class="btn btn-secondary">Lihat History</a>
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
    let html5QrCode;
    let tokoData = {};
    let lokasiSales = {};
    
    // Scanner barcode
    function initScanner() {
        html5QrCode = new Html5Qrcode("reader");
        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: { width: 250, height: 100 } },
            onScanSuccess,
            onScanError
        );
    }
    
    function onScanSuccess(decodedText) {
        fetch('/toko/cek-barcode/' + decodedText)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    html5QrCode.stop();
                    tokoData = data.toko;
                    document.getElementById('infoToko').innerHTML = `
                        <strong>${tokoData.nama_toko}</strong><br>
                        Alamat: ${tokoData.alamat || '-'}<br>
                        Lat: ${tokoData.latitude}, Lng: ${tokoData.longitude}<br>
                        Akurasi Toko: ${tokoData.accuracy} meter
                    `;
                    document.getElementById('id_toko').value = tokoData.id_toko;
                    document.getElementById('stepScan').style.display = 'none';
                    document.getElementById('stepLokasi').style.display = 'block';
                } else {
                    alert('Barcode tidak ditemukan!');
                }
            });
    }
    
    function onScanError(err) {}
    
    // Fungsi ambil lokasi dengan akurasi terbaik (Lampiran 1)
    function getAccuratePosition(targetAccuracy = 50, maxWait = 20000) {
        return new Promise((resolve, reject) => {
            let bestResult = null;
            const startTime = Date.now();
            let attempts = 0;
            
            const options = {
                enableHighAccuracy: true,  // WAJIB true
                timeout: 10000,            // timeout per attempt
                maximumAge: 0              // jangan pakai cache
            };
            
            const watchId = navigator.geolocation.watchPosition(
                (position) => {
                    attempts++;
                    const acc = position.coords.accuracy;
                    console.log(`Attempt ${attempts}: accuracy = ${acc} meters`);
                    
                    // Simpan hasil terbaik
                    if (!bestResult || acc < bestResult.coords.accuracy) {
                        bestResult = position;
                    }
                    
                    // Jika akurasi sudah bagus (≤ target), berhenti
                    if (acc <= targetAccuracy) {
                        navigator.geolocation.clearWatch(watchId);
                        console.log(`✅ Akurasi bagus: ${acc} meter, berhenti`);
                        resolve(bestResult);
                    }
                    
                    // Jika timeout, resolve dengan hasil terbaik
                    if (Date.now() - startTime >= maxWait) {
                        navigator.geolocation.clearWatch(watchId);
                        if (bestResult) {
                            console.log(`⏱️ Timeout, pakai akurasi terbaik: ${bestResult.coords.accuracy} meter`);
                            resolve(bestResult);
                        } else {
                            reject(new Error("Tidak dapat mengambil lokasi"));
                        }
                    }
                },
                (error) => {
                    console.error("Geolocation error:", error);
                    reject(error);
                },
                options
            );
        });
    }
    
    document.getElementById('ambilLokasiBtn').addEventListener('click', async () => {
        const btn = document.getElementById('ambilLokasiBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengambil lokasi...';
        
        try {
            const pos = await getAccuratePosition(50, 15000);
            lokasiSales = {
                lat: pos.coords.latitude,
                lng: pos.coords.longitude,
                accuracy: pos.coords.accuracy
            };
            
            document.getElementById('sales_lat').innerText = lokasiSales.lat;
            document.getElementById('sales_lng').innerText = lokasiSales.lng;
            document.getElementById('sales_acc').innerText = lokasiSales.accuracy;
            document.getElementById('lokasiResult').style.display = 'block';
            
            btn.style.display = 'none';
        } catch (err) {
            alert('Gagal mengambil lokasi: ' + err.message);
            btn.disabled = false;
            btn.innerHTML = '📍 Ambil Lokasi Saya';
        }
    });
    
    // Submit kunjungan
    document.getElementById('submitKunjunganBtn').addEventListener('click', () => {
        fetch('/toko/proses-kunjungan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                id_toko: document.getElementById('id_toko').value,
                nama_sales: document.getElementById('nama_sales').value,
                latitude_sales: lokasiSales.lat,
                longitude_sales: lokasiSales.lng,
                accuracy_sales: lokasiSales.accuracy
            })
        })
        .then(res => res.json())
        .then(data => {
            let hasilHtml = '';
            if (data.status === 'diterima') {
                hasilHtml = `<div class="alert alert-success">
                    <h5>✅ KUNJUNGAN DITERIMA</h5>
                    <p>Jarak: ${data.jarak} meter</p>
                    <p>Threshold Efektif: ${data.threshold_efektif} meter</p>
                    <p>(${data.threshold} + ${data.accuracy_toko} + ${data.accuracy_sales})</p>
                </div>`;
            } else {
                hasilHtml = `<div class="alert alert-danger">
                    <h5>❌ KUNJUNGAN DITOLAK</h5>
                    <p>Jarak: ${data.jarak} meter</p>
                    <p>Threshold Efektif: ${data.threshold_efektif} meter</p>
                    <p>Jarak melebihi batas yang ditentukan</p>
                </div>`;
            }
            
            document.getElementById('hasilAlert').innerHTML = hasilHtml;
            document.getElementById('stepLokasi').style.display = 'none';
            document.getElementById('stepHasil').style.display = 'block';
        });
    });
    
    initScanner();
</script>
@endsection