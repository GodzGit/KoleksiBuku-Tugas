@extends('layouts.master')

@section('title','Edit Buku')
@section('content')

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Tambah Buku</h4>

        <form method="POST" action="{{ route('koleksi-buku.store') }}">
            @csrf

            <div class="form-group">
                <label>Kategori</label>
                <select name="idkategori" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategori as $k)
                        <option value="{{ $k->idkategori }}">
                            {{ $k->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mt-3">
                <label>Kode</label>
                <input type="text" name="kode"
                       class="form-control"
                       placeholder="Contoh: NV-01" required>
            </div>

            <div class="form-group mt-3">
                <label>Judul</label>
                <input type="text" name="judul"
                       class="form-control" required>
            </div>

            <div class="form-group mt-3">
                <label>Pengarang</label>
                <input type="text" name="pengarang"
                       class="form-control" required>
            </div>

            <button type="submit"
                    class="btn btn-gradient-primary mt-3">
                Simpan
            </button>
        </form>

    </div>
</div>

@endsection
