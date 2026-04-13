@extends('layouts.master')

@section('title','Cetak Label Barang')

@section('content')

<div class="card">
    <div class="card-body">

        <h4 class="card-title">Cetak Label Barang</h4>
        <a href="{{ route('barang.create') }}" class="btn btn-primary mb-3">Tambah Barang</a>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form  method="POST" action="{{ route('barang.cetak') }}">
            @csrf
            

            <div class="table-responsive">
                <table id="datatable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th width="80">Pilih</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $item)
                        <tr>
                            <td>
                                <input type="checkbox"
                                       name="selected[]"
                                       value="{{ $item->id_barang }}">
                            </td>
                            <td>{{ $item->kode_barang }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>Rp {{ number_format($item->harga,0,',','.') }}</td>
                            <td>
                                <a href="{{ route('barang.edit', $item->id_barang) }}" class="btn btn-warning btn-sm">Edit</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row mt-3">
                <div class="col-md-2">
                    <label>Posisi X</label>
                    <input type="number" name="x"
                           class="form-control"
                           min="1" max="5" required>
                </div>

                <div class="col-md-2">
                    <label>Posisi Y</label>
                    <input type="number" name="y"
                           class="form-control"
                           min="1" max="8" required>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit"
                            class="btn btn-gradient-primary w-100">
                        Cetak Label
                    </button>
                </div>
            </div>

        </form>
        {{-- FORM DELETE DI LUAR --}}
        <form id="form-delete" method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>

        <script>
        function hapus(id) {
            let form = document.getElementById('form-delete');
            form.action = '/barang/' + id;
            form.submit();
        }
        </script>
    </div>
</div>

@endsection