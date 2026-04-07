@extends('layouts.master')

@section('title', 'Kelola Menu')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title mb-0">📋 Daftar Menu</h4>
            <a href="{{ route('vendor.menu.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Menu
            </a>
        </div>
        
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Menu</th>
                        <th>Harga</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menus as $menu)
                    <tr>
                        <td>{{ $menu->idmenu }}</td>
                        <td>{{ $menu->nama_menu }}</td>
                        <td>Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                        <td>
                            @if($menu->path_gambar)
                                <img src="{{ asset($menu->path_gambar) }}" alt="{{ $menu->nama_menu }}" width="50" height="50" style="object-fit: cover;">
                            @else
                                <span class="text-muted">Tidak ada</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('vendor.menu.edit', $menu->idmenu) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('vendor.menu.destroy', $menu->idmenu) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus menu ini?')">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada menu</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection