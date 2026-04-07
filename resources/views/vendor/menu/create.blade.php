@extends('layouts.master')

@section('title', 'Tambah Menu')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">➕ Tambah Menu Baru</h4>
                
                {{-- TAMBAHKAN enctype="multipart/form-data" --}}
                <form action="{{ route('vendor.menu.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Menu</label>
                        <input type="text" name="nama_menu" class="form-control @error('nama_menu') is-invalid @enderror" required>
                        @error('nama_menu')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror" required min="0">
                        @error('harga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- UBAH DARI TEXT MENJADI FILE UPLOAD --}}
                    <div class="mb-3">
                        <label class="form-label">Gambar Menu</label>
                        <input type="file" name="gambar" class="form-control @error('gambar') is-invalid @enderror" accept="image/*">
                        <small class="text-muted">Format: JPG, PNG, JPEG. Maksimal 2MB</small>
                        @error('gambar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="{{ route('vendor.menu.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection