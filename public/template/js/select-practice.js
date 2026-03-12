$(document).ready(function () {

    // --- INISIALISASI SELECT2 ---
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: "Pilih Kota"
    });

    // --- LOGIKA SELECT BIASA ---

    // Tambah kota ke select biasa
    $("#btnTambahKota").click(function () {
        let kota = $("#input_kota").val();

        if (kota === "") {
            alert("Input kota dulu");
            return;
        }

        $("#select_kota").append(`<option value="${kota}">${kota}</option>`);
        $("#input_kota").val("");
    });

    // Tampilkan hasil select biasa
    $("#select_kota").change(function () {
        let kota = $(this).val();
        $("#hasil_kota").text(kota || "-");
    });


    // --- LOGIKA SELECT2 ---

    // Tambah kota ke Select2
    $("#btnTambahKota2").click(function () {
        let kota = $("#input_kota2").val();

        if (kota === "") {
            alert("Input kota dulu");
            return;
        }

        // Tambahkan option baru dan trigger 'change' agar Select2 memperbarui tampilannya
        let newOption = new Option(kota, kota, false, false);
        $("#select_kota2").append(newOption).trigger('change');

        $("#input_kota2").val("");
    });

    // Tampilkan hasil Select2
    $("#select_kota2").on("change", function () {
        let kota = $(this).val();
        $("#hasil_kota2").text(kota || "-");
    });

});