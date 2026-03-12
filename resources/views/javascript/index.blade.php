@extends('layouts.master')

@section('title','Data Javascript')
<style>
    .table-hover-row tbody tr:hover { cursor: pointer; }
</style>

@section('content')

<div class="card">
    <div class="card-body">

        <h4 class="card-title">Latihan Javascript</h4>

        <div class="row">

            <div class="col-md-6">

                <label>Nama Barang</label>
                <input type="text" id="nama_barang" class="form-control" required>

            </div>

            <div class="col-md-6">

                <label>Harga Barang</label>
                <input type="number" id="harga_barang" class="form-control" required>

            </div>

        </div>

        <button type="button" id="btnSubmit" class="btn btn-primary mt-3">

            <span class="btn-text">Submit</span>
            <span class="spinner-border spinner-border-sm d-none"></span>

        </button>

        <hr>

        <h5>Table HTML Biasa</h5>

        <table class="table table-bordered table-hover-row" id="table-html">

            <thead>
                <tr>
                    <th>ID Barang</th>
                    <th>Nama</th>
                    <th>Harga</th>
                </tr>
            </thead>

            <tbody>
            </tbody>

        </table>


        <h5 class="mt-4">Table DataTables</h5>

        <table class="table table-bordered table-hover-row" id="table-datatable">

            <thead>
                <tr>
                    <th>ID Barang</th>
                    <th>Nama</th>
                    <th>Harga</th>
                </tr>
            </thead>

            <tbody>
            </tbody>

        </table>


    </div>
    <div class="modal fade" id="modalEdit" tabindex="-1">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Edit Barang</h5>
                </div>

                <div class="modal-body">

                    <div class="mb-2">
                        <label>ID Barang</label>
                        <input type="text" id="edit_id" class="form-control" readonly>
                    </div>

                    <div class="mb-2">
                        <label>Nama Barang</label>
                        <input type="text" id="edit_nama" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label>Harga Barang</label>
                        <input type="number" id="edit_harga" class="form-control" required>
                    </div>

                </div>

                <div class="modal-footer">

                    <button id="btnUpdate" class="btn btn-primary">Ubah</button>

                    <button id="btnDelete" class="btn btn-danger">Hapus</button>

                </div>

            </div>
        </div>
    </div>
</div>



@endsection


@section('js-page')

<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

@endsection