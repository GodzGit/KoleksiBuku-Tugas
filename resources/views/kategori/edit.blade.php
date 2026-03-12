@extends('layouts.master')

@section('title','Edit Kategori')
@section('content')

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Edit Kategori</h4>

        <form class="form-crud" method="POST" action="{{ route('kategori.update', $kategori->idkategori) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nama Kategori</label>
                <input type="text"
                       name="nama_kategori"
                       class="form-control"
                       value="{{ $kategori->nama_kategori }}"
                       required>
            </div>

            <button type="button"
                    class="btn btn-gradient-primary mt-3 btn-submit">
                <span class="btn-text">
                    Simpan
                </span>
                <span class="spinner-border spinner-border-sm d-none"></span>
            </button>

            <a href="{{ route('kategori.index') }}"
               class="btn btn-secondary mt-3">
                Kembali
            </a>
        </form>

    </div>
</div>

@endsection
