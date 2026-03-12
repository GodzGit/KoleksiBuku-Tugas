@extends('layouts.master')

@section('title', 'Select Practice')
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    .select2-container { width: 100% !important; }

    .select2-container .select2-selection--single {
        height: 38px;
        padding: 4px 10px;
    }

    .select2-selection__rendered { line-height: 28px !important; }
</style>
@section('content')

<div class="row">

    <div class="col-md-6">

        <div class="card">
            <div class="card-body">

                <h4>Select</h4>

                <label>Kota</label>
                <input type="text" id="input_kota" class="form-control">

                <button id="btnTambahKota" class="btn btn-primary mt-2">
                    Tambahkan
                </button>

                <hr>

                <label>Select Kota</label>
                <select id="select_kota" class="form-control">
                    <option value="">Pilih Kota</option>
                </select>

                <br>

                <p class="mt-2">
                    Kota Terpilih : <b id="hasil_kota">-</b>
                </p>

            </div>
        </div>

    </div>

    <div class="col-md-6">

        <div class="card">
            <div class="card-body">

                <h4>Select2</h4>

                <label>Kota</label>
                <input type="text" id="input_kota2" class="form-control">

                <button id="btnTambahKota2" class="btn btn-primary mt-2">
                    Tambahkan
                </button>

                <hr>

                <label>Select Kota</label>
                <select id="select_kota2" class="form-control select2">
                    <option value="">Pilih Kota</option>
                </select>

                <br>

                <p class="mt-2">
                    Kota Terpilih : <b id="hasil_kota2">-</b>
                </p>

            </div>
        </div>

    </div>

</div>

@endsection

@section('js-page')

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('template/js/select-practice.js') }}"></script>

@endsection