@extends('layouts.master')

@section('title', 'Tambah Customer (Blob)')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">📷 Tambah Customer (Foto disimpan di Database)</h4>

                <form method="POST" action="{{ route('customer.store-blob') }}" id="customerForm">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama Customer</label>
                        <input type="text" name="nama_customer" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ambil Foto</label>
                        <div class="border rounded p-3 text-center bg-light">
                            <video id="video" width="100%" height="auto" autoplay style="max-width: 400px; border-radius: 8px;"></video>
                            <button type="button" id="captureBtn" class="btn btn-primary mt-2">
                                <i class="fas fa-camera"></i> Ambil Foto
                            </button>
                            <canvas id="canvas" style="display: none;"></canvas>
                        </div>
                        <div class="mt-2" id="previewArea" style="display: none;">
                            <label class="form-label">Hasil Foto:</label>
                            <img id="preview" src="" width="100" height="100" style="object-fit: cover; border-radius: 8px;">
                        </div>
                        <input type="hidden" name="foto" id="fotoBase64">
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('customer.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-page')
<script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const captureBtn = document.getElementById('captureBtn');
    const preview = document.getElementById('preview');
    const previewArea = document.getElementById('previewArea');
    const fotoBase64 = document.getElementById('fotoBase64');

    // Akses kamera
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            video.srcObject = stream;
        })
        .catch(err => {
            alert('Tidak dapat mengakses kamera: ' + err.message);
        });

    // Ambil foto
    captureBtn.addEventListener('click', () => {
        const context = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        const base64 = canvas.toDataURL('image/jpeg');
        fotoBase64.value = base64;
        preview.src = base64;
        previewArea.style.display = 'block';
    });
</script>
@endsection