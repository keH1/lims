$(function ($) {
    const body = $('body')

    let ugtpId = $('.wrapper-penetration-index').find('#ugtp_id').val()

    function roundPlus(x, n) {
        if(isNaN(x) || isNaN(n)) return false;
        let m = Math.pow(10, n);
        return Math.round(x * m) / m;
    }

    // Индекс пенетрации
    function GetPenetrationIndex(){

        P = Number($(`[name='form_data[${ugtpId}][form][penetration_index_33134][penetration_depth_25]']`).val());
        T = Number($(`[name='form_data[${ugtpId}][form][penetration_index_33134][temperature_RaB]']`).val());

        logP = roundPlus((Math.log10(P)), 4);

        A = roundPlus(((2.9031 - logP) / (T - 25)), 4);
        $(`[name='form_data[${ugtpId}][form][penetration_index_33134][coefficient]']`).val(A);

        indexP = roundPlus(((30 / (1 + 50 * A)) - 10), 1);
        $(`[name='form_data[${ugtpId}][form][penetration_index_33134][penetration_index_value]']`).val(indexP);
    }

    body.on("click", "#penetrationIndex", function () {
        GetPenetrationIndex();
    });
})