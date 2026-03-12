@extends('layouts.master')

@section('title','Edit Buku')
@section('content')

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Edit Buku</h4>

        <form class="form-crud" method="POST" action="{{ route('koleksi-buku.update', $buku->idbuku) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Kategori</label>
                <select name="idkategori" class="form-control" required>
                    @foreach($kategori as $k)
                        <option value="{{ $k->idkategori }}"
                            {{ $k->idkategori == $buku->idkategori ? 'selected' : '' }}>
                            {{ $k->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mt-3">
                <label>Kode</label>
                <input type="text"
                       name="kode"
                       class="form-control"
                       value="{{ $buku->kode }}"
                       required>
            </div>

            <div class="form-group mt-3">
                <label>Judul</label>
                <input type="text"
                       name="judul"
                       class="form-control"
                       value="{{ $buku->judul }}"
                       required>
            </div>

            <div class="form-group mt-3">
                <label>Pengarang</label>
                <input type="text"
                       name="pengarang"
                       class="form-control"
                       value="{{ $buku->pengarang }}"
                       required>
            </div>

            <button type="button" class="btn btn-gradient-primary mt-3 btn-submit">
                <span class="btn-text">Simpan</span>
                <span class="spinner-border spinner-border-sm d-none"></span>
            </button>

            <a href="{{ route('koleksi-buku.index') }}"
               class="btn btn-secondary mt-3">
                Kembali
            </a>
        </form>

    </div>
</div>

@endsection
