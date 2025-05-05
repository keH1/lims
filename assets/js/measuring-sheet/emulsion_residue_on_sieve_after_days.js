$(function ($) {
    const body = $('body')

    let ugtpId = $('.wrapper-resiude-sieve-after-days').find('#ugtp_id').val()

    function roundPlus(x, n) {
        if(isNaN(x) || isNaN(n)) return false;
        let m = Math.pow(10, n);
        return Math.round(x * m) / m;
    }

    // Остаток на сите 0,14 после 7 суток
    function GetSeaveResidue_0_14_7() {

        let arrAverage = [];
        for(let i = 0; i < 2; i++) {

            m1 = Number($(`[name='form_data[${ugtpId}][form][sieve_residue_0_14_7][m_sieve_cup][${i}]']`).val());
            m2 = Number($(`[name='form_data[${ugtpId}][form][sieve_residue_0_14_7][m_glasses][${i}]']`).val());
            m3 = Number($(`[name='form_data[${ugtpId}][form][sieve_residue_0_14_7][m_emulsion][${i}]']`).val());
            m4 = Number($(`[name='form_data[${ugtpId}][form][sieve_residue_0_14_7][m_glass_emulsion][${i}]']`).val());
            m5 = Number($(`[name='form_data[${ugtpId}][form][sieve_residue_0_14_7][m_sieve_cup_emulsion_after_drying][${i}]']`).val());

            diff_m5_m1 = roundPlus((m5 - m1), 2);
            diff_m4_m2 = roundPlus((m4 - m2), 2);

            m = roundPlus((diff_m5_m1 / (m3 - diff_m4_m2)) * 100, 2);
            $(`[name='form_data[${ugtpId}][form][sieve_residue_0_14_7][sieve_residue_percent][${i}]']`).val(m);

            arrAverage.push(m);
        }

        m_average = roundPlus((arrAverage[0] + arrAverage[1]) / 2, 3);

        diff_index = roundPlus(Math.abs(arrAverage[0] - arrAverage[1]), 2);

        if(diff_index > 0.03) {
            alert("Расхождение между двумя испытаниями больше допустимой величины в 0.03% от среднего арифметического и равно [" + diff_index + "]. Результаты недостоверны.");
            $(`[name='form_data[${ugtpId}][form][sieve_residue_0_14_7][average_sieve_residue]']`).val("");
            return false;
        }

        $(`[name='form_data[${ugtpId}][form][sieve_residue_0_14_7][average_sieve_residue]']`).val(m_average);


        // let percentVal = roundPlus(m_average * (0.03 / 100), 2);

        // diff_index = roundPlus(Math.abs(arrAverage[0] - arrAverage[1]), 2);

        // if(diff_index > percentVal) {
        //     alert("Расхождение между двумя испытаниями больше допустимой величины в 0.03% (" + percentVal + ") от среднего арифметического и равно [" + diff_index + "]. Результаты недостоверны.");
        //     $("[name='form_data[${ugtpId}][form][sieve_residue_0_14_7][average_sieve_residue]']").val("");
        //     return false;
        // }

        // $("[name='form_data[${ugtpId}][form][sieve_residue_0_14_7][average_sieve_residue]']").val(m_average);
    }

    body.on("click", "#sieveResidue_0_14_7", function() {
        GetSeaveResidue_0_14_7();
    });
})