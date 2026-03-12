$(document).ready(function(){

    $(".btn-submit").click(function(){

        let btn = $(this);
        let form = btn.closest("form")[0];

        // cek validity
        if(!form.checkValidity()){
            form.reportValidity();
            return;
        }

        // ubah button jadi loading
        btn.find(".btn-text").hide();
        btn.find(".spinner-border").removeClass("d-none");

        // disable button
        btn.prop("disabled", true);

        // submit form
        form.submit();

    });

    $(".btn-delete").click(function(){

        let btn = $(this);
        let form = btn.closest("form")[0];

        if(!confirm("Yakin hapus?")){
            return;
        }

        // ubah button jadi loading
        btn.find(".btn-text").hide();
        btn.find(".spinner-border").removeClass("d-none");

        btn.prop("disabled", true);

        form.submit();

    });

});