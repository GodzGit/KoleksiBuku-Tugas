@extends('layouts.master')

@section('title', 'Edit Menu')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">✏️ Edit Menu</h4>
                
                <form action="{{ route('vendor.menu.update', $menu->idmenu) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Menu</label>
                        <input type="text" name="nama_menu" class="form-control @error('nama_menu') is-invalid @enderror" 
                               value="{{ old('nama_menu', $menu->nama_menu) }}" required>
                        @error('nama_menu')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror" 
                               value="{{ old('harga', $menu->harga) }}" required min="0">
                        @error('harga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- TAMPILKAN GAMBAR LAMA --}}
                    <div class="mb-3">
                        <label class="form-label">Gambar Saat Ini</label>
                        @if($menu->path_gambar)
                            <div class="mb-2">
                                <img src="{{ asset($menu->path_gambar) }}" alt="{{ $menu->nama_menu }}" style="width: 150px; height: 100px; object-fit: cover; border-radius: 8px;">
                            </div>
                        @else
                            <p class="text-muted">Tidak ada gambar</p>
                        @endif
                    </div>
                    
                    {{-- UPLOAD GAMBAR BARU --}}
                    <div class="mb-3">
                        <label class="form-label">Ganti Gambar (opsional)</label>
                        <input type="file" name="gambar" class="form-control @error('gambar') is-invalid @enderror" accept="image/*">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar</small>
                        @error('gambar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update
                    </button>
                    <a href="{{ route('vendor.menu.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection