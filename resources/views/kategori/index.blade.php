@extends('layouts.master')

@section('title','Data Kategori')
@section('content')

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Data Kategori</h4>

        <a href="{{ route('kategori.create') }}"
           class="btn btn-gradient-primary mb-3">
            Tambah Kategori
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
                    <th>Nama Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kategori as $key => $k)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $k->nama_kategori }}</td>
                    <td>
                        <a href="{{ route('kategori.edit', $k->idkategori) }}"
                           class="btn btn-sm btn-warning">Edit</a>

                        <form action="{{ route('kategori.destroy', $k->idkategori) }}"
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
