@extends('layouts.master')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Transaksi Penjualan</h4>
        
        <div class="row mb-3">
            <div class="col-md-2">
                <input type="text" id="kode_barang" class="form-control" placeholder="Kode Barang">
            </div>
            <div class="col-md-4">
                <input type="text" id="nama_barang" class="form-control" placeholder="Nama Barang" readonly>
            </div>
            <div class="col-md-2">
                <input type="text" id="harga_barang" class="form-control" placeholder="Harga" readonly>
            </div>
            <div class="col-md-2">
                <input type="number" id="qty" class="form-control" value="1" min="1">
            </div>
            <div class="col-md-2">
                <button type="button" id="btn-tambah" class="btn btn-primary" disabled>
                    <span id="spin-tambah" class="spinner-border spinner-border-sm d-none" role="status"></span>
                    <span id="txt-tambah" class="btn-text">Tambahkan</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
            </div>
        </div>

        <table class="table table-bordered" id="tabel-kasir">
            <thead>
                <tr>
                    <th>Kode</th><th>Nama</th><th>Harga</th><th>Qty</th><th>Subtotal</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-right">Total</th>
                    <th id="total-bayar">0</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>

        <button type="button" id="btn-bayar" class="btn btn-gradient-primary mt-3">
            <span class="btn-text">Bayar Sekarang</span>
            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
        </button>
    </div>
</div>
@endsection

@section('js-page')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Setting global Axios untuk CSRF Token (PENTING!)
    axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
    
    let idBarangTemp = null;

    // 1. CEK BARANG (ENTER)
    $('#kode_barang').on('keypress', function(e) {
        if(e.which == 13) {
            let kode = $(this).val();
            if(!kode) return;

            $('#spin-tambah').removeClass('d-none');
            axios.get('/cek-barang/' + kode)
                .then(res => {
                    if(res.data.success) {
                        $('#nama_barang').val(res.data.data.nama_barang);
                        $('#harga_barang').val(res.data.data.harga);
                        idBarangTemp = res.data.data.id_barang;
                        $('#btn-tambah').prop('disabled', false);
                        $('#qty').focus();
                    } else {
                        Swal.fire('Error', 'Barang tidak ditemukan', 'error');
                        resetInput();
                    }
                })
                .finally(() => {
                    $('#spin-tambah').addClass('d-none');
                });
        }
    });

    // 2. TAMBAH KE TABEL
    $('#btn-tambah').click(function() {
        let kode = $('#kode_barang').val();
        let nama = $('#nama_barang').val();
        let harga = parseInt($('#harga_barang').val());
        let qty = parseInt($('#qty').val());
        let subtotal = harga * qty;

        if (qty <= 0 || isNaN(qty)) return;

        let rowExist = $(`#tabel-kasir tbody tr[data-id="${idBarangTemp}"]`);
        
        if(rowExist.length > 0) {
            let qtyOld = parseInt(rowExist.find('.td-qty').text());
            let qtyNew = qtyOld + qty;
            rowExist.find('.td-qty').text(qtyNew);
            rowExist.find('.td-sub').text(qtyNew * harga);
        } else {
            $('#tabel-kasir tbody').append(`
                <tr data-id="${idBarangTemp}">
                    <td>${kode}</td>
                    <td>${nama}</td>
                    <td>${harga}</td>
                    <td class="td-qty">${qty}</td>
                    <td class="td-sub">${subtotal}</td>
                    <td><button type="button" class="btn btn-danger btn-sm btn-hapus">X</button></td>
                </tr>
            `);
        }
        updateTotal();
        resetInput();
    });

    // 3. HAPUS BARIS
    $(document).on('click', '.btn-hapus', function() {
        $(this).closest('tr').remove();
        updateTotal();
    });

    // 4. SIMPAN TRANSAKSI (BAYAR) + SPINNER
    $('#btn-bayar').click(function() {
        let items = [];
        let btn = $(this);

        $('#tabel-kasir tbody tr').each(function() {
            items.push({
                id_barang: $(this).data('id'),
                qty: parseInt($(this).find('.td-qty').text()),
                subtotal: parseInt($(this).find('.td-sub').text())
            });
        });

        if(items.length === 0) {
            return Swal.fire('Peringatan', 'Keranjang belanja masih kosong', 'warning');
        }

        // TAMPILKAN SPINNER (Sesuai Poin L & Ref Form-Handler)
        btn.prop('disabled', true);
        btn.find('.btn-text').addClass('d-none');
        btn.find('.spinner-border').removeClass('d-none');

        axios.post('/simpan-transaksi', {
            total: parseInt($('#total-bayar').text()),
            items: items
        })
        .then(res => {
            if(res.data.success) {
                Swal.fire('Berhasil', 'Pembayaran transaksi berhasil disimpan', 'success')
                .then(() => {
                    $('#tabel-kasir tbody').empty();
                    $('#total-bayar').text('0');
                    resetInput();
                });
            } else {
                Swal.fire('Gagal', res.data.msg, 'error');
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire('Gagal', 'Terjadi kesalahan sistem', 'error');
        })
        .finally(() => {
            // MATIKAN SPINNER
            btn.prop('disabled', false);
            btn.find('.btn-text').removeClass('d-none');
            btn.find('.spinner-border').addClass('d-none');
        });
    });

    function updateTotal() {
        let grandTotal = 0;
        $('.td-sub').each(function() {
            grandTotal += parseInt($(this).text());
        });
        $('#total-bayar').text(grandTotal);
    }

    function resetInput() {
        $('#kode_barang').val('').focus();
        $('#nama_barang').val('');
        $('#harga_barang').val('');
        $('#qty').val(1);
        $('#btn-tambah').prop('disabled', true);
        idBarangTemp = null;
    }
});
</script>
@endsection