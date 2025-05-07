$(function ($) {
    const body = $('body')

    let ugtp_id = $('#ugtp_id').val()

    function roundPlus(x, n) {
        if(isNaN(x) || isNaN(n)) return false;
        let m = Math.pow(10, n);
        return Math.round(x * m) / m;
    }

    // Гидрофобность
    body.on("change", `[name="form_data[${ugtp_id}][hydrophobicity_32761][admixture]"]`, function() {
        if($(this).val() == "Содержит загрязняющие примеси") {
            $('#admixture').css("display", "");
        }
        else {
            $('#admixture').css("display", "none");
        }
    });
})