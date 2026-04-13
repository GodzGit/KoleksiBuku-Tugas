@extends('layouts.master')

@section('title','Edit Barang')

@section('content')
<div class="card">
    <div class="card-body">
        <h4>Edit Barang</h4>

        <form action="{{ route('barang.update', $barang->id_barang) }}" method="POST">
            @csrf
            @method('PUT')

            <input type="text" name="kode_barang" value="{{ $barang->kode_barang }}" class="form-control mb-2">
            <input type="text" name="nama_barang" value="{{ $barang->nama_barang }}" class="form-control mb-2">
            <input type="number" name="harga" value="{{ $barang->harga }}" class="form-control mb-2">
            <input type="number" name="stok" value="{{ $barang->stok }}" class="form-control mb-2">

            <button class="btn btn-success">Update</button>
        </form>
    </div>
</div>
@endsection