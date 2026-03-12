$(document).ready(function () {
    let idBarang = 1;
    let tableDT = $("#table-datatable").DataTable();
    let selectedRowHTML = null;
    let selectedRowDT = null;

    // --- EVENT: TAMBAH DATA ---
    $("#btnSubmit").click(function () {
        let nama = $("#nama_barang").val();
        let harga = $("#harga_barang").val();

        if (nama === "" || harga === "") {
            alert("Nama dan harga wajib diisi");
            return;
        }

        let btn = $(this);
        btn.find(".btn-text").hide();
        btn.find(".spinner-border").removeClass("d-none");

        setTimeout(function () {
            // Render ke Table HTML Biasa
            let rowHTML = `
                <tr>
                    <td>${idBarang}</td>
                    <td>${nama}</td>
                    <td>${harga}</td>
                </tr>`;
            $("#table-html tbody").append(rowHTML);

            // Render ke DataTable
            tableDT.row.add([idBarang, nama, harga]).draw(false);

            // Reset Input & UI
            $("#nama_barang").val("");
            $("#harga_barang").val("");
            btn.find(".btn-text").show();
            btn.find(".spinner-border").addClass("d-none");

            idBarang++;
        }, 500);
    });

    // --- EVENT: SELECT ROW (TABLE HTML) ---
    $(document).on("click", "#table-html tbody tr", function () {
        selectedRowHTML = $(this);
        selectedRowDT = null; // Reset agar tidak bentrok

        let id = $(this).find("td:eq(0)").text();
        let nama = $(this).find("td:eq(1)").text();
        let harga = $(this).find("td:eq(2)").text();

        fillModal(id, nama, harga);
    });

    // --- EVENT: SELECT ROW (DATATABLE) ---
    $("#table-datatable tbody").on("click", "tr", function () {
        selectedRowDT = tableDT.row(this);
        selectedRowHTML = null; // Reset agar tidak bentrok

        let data = selectedRowDT.data();
        fillModal(data[0], data[1], data[2]);
    });

    // Helper function untuk isi modal
    function fillModal(id, nama, harga) {
        $("#edit_id").val(id);
        $("#edit_nama").val(nama);
        $("#edit_harga").val(harga);
        $("#modalEdit").modal("show");
    }

    // --- EVENT: UPDATE DATA ---
    $("#btnUpdate").click(function () {
        let id = $("#edit_id").val();
        let nama = $("#edit_nama").val();
        let harga = $("#edit_harga").val();

        if (nama === "" || harga === "") {
            alert("Input wajib diisi");
            return;
        }

        if (selectedRowHTML) {
            selectedRowHTML.find("td:eq(1)").text(nama);
            selectedRowHTML.find("td:eq(2)").text(harga);
        }

        if (selectedRowDT) {
            selectedRowDT.data([id, nama, harga]).draw();
        }

        $("#modalEdit").modal("hide");
    });

    // --- EVENT: DELETE DATA ---
    $("#btnDelete").click(function () {
        if (selectedRowHTML !== null) {
            selectedRowHTML.remove();
            selectedRowHTML = null;
        }

        if (selectedRowDT !== null) {
            selectedRowDT.remove().draw();
            selectedRowDT = null;
        }

        $("#modalEdit").modal("hide");
    });
});