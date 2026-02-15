@extends('layouts.master')

@section('title','Data Buku')

@section('content')

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Data Buku</h4>

        <a href="{{ route('koleksi-buku.create') }}"
           class="btn btn-gradient-primary mb-3">
            Tambah Buku
        </a>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Kode</th>
                    <th>Judul</th>
                    <th>Pengarang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($buku as $key => $b)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $b->kategori->nama_kategori ?? '-' }}</td>
                    <td>{{ $b->kode }}</td>
                    <td>{{ $b->judul }}</td>
                    <td>{{ $b->pengarang }}</td>
                    <td>
                        <a href="{{ route('koleksi-buku.edit', $b->idbuku) }}"
                           class="btn btn-sm btn-warning">Edit</a>

                        <form action="{{ route('koleksi-buku.destroy', $b->idbuku) }}"
                              method="POST"
                              style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Yakin hapus?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

@endsection
