@extends('layouts.master')

@section('title','Tambah Kategori')
@section('content')

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Tambah Kategori</h4>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form class="form-crud" method="POST" action="{{ route('kategori.store') }}">
            @csrf

            <div class="form-group">
                <label>Nama Kategori</label>
                <input type="text"
                       name="nama_kategori"
                       class="form-control"
                       required>
            </div>

            <button type="button" class="btn btn-gradient-primary mt-3 btn-submit">
                <span class="btn-text">
                    Simpan
                </span>
                <span class="spinner-border spinner-border-sm d-none"></span>
            </button>
        </form>
    </div>
</div>

@endsection
