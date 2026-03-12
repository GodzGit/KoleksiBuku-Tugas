$(document).ready(function(){

    $("#btnSubmit").click(function(){

        let form = document.getElementById("formBuku");

        // cek validity
        if(!form.checkValidity()){

            // tampilkan pesan HTML5
            form.reportValidity();
            return;

        }

        // ubah button jadi loading
        $(".btn-text").hide();
        $("#spinner").removeClass("d-none");

        // disable button supaya tidak double click
        $("#btnSubmit").prop("disabled", true);

        // submit form
        form.submit();

    });

});