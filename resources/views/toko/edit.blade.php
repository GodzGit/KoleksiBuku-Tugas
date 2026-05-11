@extends('layouts.master')

@section('title', 'Edit Toko')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">✏️ Edit Toko</h4>

                <form action="{{ route('toko.update', $toko->id_toko) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Barcode</label>
                        <input type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror" 
                               value="{{ old('barcode', $toko->barcode) }}" required>
                        @error('barcode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Toko</label>
                        <input type="text" name="nama_toko" class="form-control @error('nama_toko') is-invalid @enderror" 
                               value="{{ old('nama_toko', $toko->nama_toko) }}" required>
                        @error('nama_toko')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="2">{{ old('alamat', $toko->alamat) }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Latitude</label>
                            <input type="text" name="latitude" class="form-control @error('latitude') is-invalid @enderror" 
                                   value="{{ old('latitude', $toko->latitude) }}" required>
                            @error('latitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Longitude</label>
                            <input type="text" name="longitude" class="form-control @error('longitude') is-invalid @enderror" 
                                   value="{{ old('longitude', $toko->longitude) }}" required>
                            @error('longitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Akurasi (meter)</label>
                        <input type="number" name="accuracy" class="form-control @error('accuracy') is-invalid @enderror" 
                               value="{{ old('accuracy', $toko->accuracy) }}" required min="0">
                        <small class="text-muted">Toleransi GPS toko dalam meter</small>
                        @error('accuracy')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save"></i> Update
                    </button>
                    <a href="{{ route('toko.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection