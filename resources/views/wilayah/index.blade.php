@extends('layouts.master')
@section('title', 'Tugas AJAX vs Axios')

@section('content')
<div class="page-header">
    <h3 class="page-title"> Implementasi Dropdown Wilayah </h3>
</div>

<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-primary">Versi 1: JQuery AJAX</h4>
                <p class="card-description"> Menggunakan <code>$.ajax()</code> </p>
                <form class="forms-sample">
                    <div class="form-group">
                        <label>Provinsi</label>
                        <select class="form-control" id="ajax-provinsi">
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach($provinces as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kota (AJAX)</label>
                        <select class="form-control" id="ajax-kota">
                            <option value="">-- Pilih Kota --</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kecamatan (AJAX)</label>
                        <select class="form-control" id="ajax-kecamatan">
                            <option value="">-- Pilih Kecamatan --</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kelurahan (AJAX)</label>
                        <select class="form-control" id="ajax-kelurahan">
                            <option value="">-- Pilih Kelurahan --</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-success">Versi 2: Axios</h4>
                <p class="card-description"> Menggunakan <code>axios.get()</code> </p>
                <form class="forms-sample">
                    <div class="form-group">
                        <label>Provinsi</label>
                        <select class="form-control" id="axios-provinsi">
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach($provinces as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kota (Axios)</label>
                        <select class="form-control" id="axios-kota">
                            <option value="">-- Pilih Kota --</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kecamatan (Axios)</label>
                        <select class="form-control" id="axios-kecamatan">
                            <option value="">-- Pilih Kecamatan --</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kelurahan (Axios)</label>
                        <select class="form-control" id="axios-kelurahan">
                            <option value="">-- Pilih Kelurahan --</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-page')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
$(document).ready(function() {
    
    // --- LOGIKA JQUERY AJAX ---
    // (Jika kode a ke b: Jika Event Change ke $.ajax)
    $('#ajax-provinsi').on('change', function() {
        let id = $(this).val();
        $('#ajax-kota').empty().append('<option value="">-- Pilih Kota --</option>');
        
        if(id) {
            $.ajax({
                url: '/get-kota/' + id,
                method: 'GET',
                success: function(piringData) { 
                    // JQuery: Piring data langsung bisa di-loop
                    $.each(piringData, function(key, val) {
                        $('#ajax-kota').append(`<option value="${val.id}">${val.name}</option>`);
                    });
                }
            });
        }
    });

    $('#ajax-kota').on('change', function() {
        let id = $(this).val();
        $('#ajax-kecamatan').empty().append('<option value="">-- Pilih Kecamatan --</option>');
        
        if(id) {
            $.ajax({
                url: '/get-kecamatan/' + id,
                method: 'GET',
                success: function(piringData) { 
                    // JQuery: Piring data langsung bisa di-loop
                    $.each(piringData, function(key, val) {
                        $('#ajax-kecamatan').append(`<option value="${val.id}">${val.name}</option>`);
                    });
                }
            });
        }
    });

    $('#ajax-kecamatan').on('change', function() {
        let id = $(this).val();
        $('#ajax-kelurahan').empty().append('<option value="">-- Pilih Kelurahan --</option>');
        
        if(id) {
            $.ajax({
                url: '/get-kelurahan/' + id,
                method: 'GET',
                success: function(piringData) { 
                    // JQuery: Piring data langsung bisa di-loop
                    $.each(piringData, function(key, val) {
                        $('#ajax-kelurahan').append(`<option value="${val.id}">${val.name}</option>`);
                    });
                }
            });
        }
    });





    // --- LOGIKA AXIOS ---
    // (Jika kode a ke b: Jika Event Change ke axios.get)
    $('#axios-provinsi').on('change', function() {
        let id = $(this).val();
        $('#axios-kota').empty().append('<option value="">-- Pilih Kota --</option>');

        if(id) {
            // Pelayan Axios berangkat
            axios.get('/get-kota/' + id)
                .then(function (kotakResponse) {
                    // Axios: Harus buka kotakResponse.data dulu
                    let piringData = kotakResponse.data; 
                    
                    $.each(piringData, function(key, val) {
                        $('#axios-kota').append(`<option value="${val.id}">${val.name}</option>`);
                    });
                })
                .catch(function (error) {
                    console.error("Ada masalah kawan:", error);
                });
        }
    });

    $('#axios-kota').on('change', function() {
        let id = $(this).val();
        $('#axios-kecamatan').empty().append('<option value="">-- Pilih Kecamatan --</option>');

        if(id) {
            axios.get('/get-kecamatan/' + id)
                .then(function (kecamatanResponse) {
                    let piringData = kecamatanResponse.data; 
                    
                    $.each(piringData, function(key, val) {
                        $('#axios-kecamatan').append(`<option value="${val.id}">${val.name}</option>`);
                    });
                })
                .catch(function (error) {
                    console.error("Ada masalah kawan:", error);
                });
        }
    });

    $('#axios-kecamatan').on('change', function() {
        let id = $(this).val();
        $('#axios-kelurahan').empty().append('<option value="">-- Pilih Kelurahan --</option>');

        if(id) {
            axios.get('/get-kelurahan/' + id)
                .then(function (kelurahanResponse) {
                    let piringData = kelurahanResponse.data; 
                    
                    $.each(piringData, function(key, val) {
                        $('#axios-kelurahan').append(`<option value="${val.id}">${val.name}</option>`);
                    });
                })
                .catch(function (error) {
                    console.error("Ada masalah kawan:", error);
                });
        }
    });

});
</script>
@endsection