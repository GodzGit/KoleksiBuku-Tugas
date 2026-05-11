@extends('layouts.master')

@section('title', 'Kelola Toko')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">🏪 Daftar Toko</h4>
            <a href="{{ route('toko.create') }}" class="btn btn-gradient-primary">
                <i class="mdi mdi-plus"></i> Tambah Toko
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Barcode</th>
                        <th>Nama Toko</th>
                        <th>Alamat</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Akurasi (m)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tokos as $toko)
                    <tr>
                        <td>{{ $toko->barcode }}</td>
                        <td>{{ $toko->nama_toko }}</td>
                        <td>{{ $toko->alamat ?? '-' }}</td>
                        <td>{{ $toko->latitude }}</td>
                        <td>{{ $toko->longitude }}</td>
                        <td>{{ $toko->accuracy }} m</td>
                        <td>
                            <a href="{{ route('toko.edit', $toko->id_toko) }}" class="btn btn-sm btn-warning">
                                <i class="mdi mdi-pencil"></i>
                            </a>
                            <a href="{{ route('toko.barcode', $toko->id_toko) }}" class="btn btn-sm btn-info" target="_blank">
                                <i class="mdi mdi-barcode"></i>
                            </a>
                            <form action="{{ route('toko.destroy', $toko->id_toko) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus toko ini?')">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data toko</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection