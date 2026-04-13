@extends('layouts.master')

@section('title','Tambah Barang')

@section('content')
<div class="card">
    <div class="card-body">
        <h4>Tambah Barang</h4>

        <form action="{{ route('barang.store') }}" method="POST">
            @csrf

            <input type="text" name="kode_barang" placeholder="Kode" class="form-control mb-2">
            <input type="text" name="nama_barang" placeholder="Nama" class="form-control mb-2">
            <input type="number" name="harga" placeholder="Harga" class="form-control mb-2">
            <input type="number" name="stok" placeholder="Stok" class="form-control mb-2">

            <button class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection