$(function ($) {
    const body = $('body')

    let ugtpId = $('.wrapper-determination-with-emulsifier').find('#ugtp_id').val()

    function roundPlus(x, n) {
        if(isNaN(x) || isNaN(n)) return false;
        let m = Math.pow(10, n);
        return Math.round(x * m) / m;
    }

    function decimalAdjust(type, value, exp) {
        // Если степень не определена, либо равна нулю...
        if (typeof exp === "undefined" || +exp === 0) {
            return Math[type](value);
        }
        value = +value;
        exp = +exp;
        // Если значение не является числом, либо степень не является целым числом...
        if (isNaN(value) || !(typeof exp === "number" && exp % 1 === 0)) {
            return NaN;
        }
        // Сдвиг разрядов
        value = value.toString().split("e");
        value = Math[type](+(value[0] + "e" + (value[1] ? +value[1] - exp : -exp)));
        // Обратный сдвиг
        value = value.toString().split("e");
        return +(value[0] + "e" + (value[1] ? +value[1] + exp : exp));
    }

    // Определение содержания вяжущего с эмульгатором
    function GetBinderEmulsifier() {

        let arrAverage = [];
        for(let i = 0; i < 2; i++) {

            let g1 = Number($(`[name='form_data[${ugtpId}][form][binder_emulsifier][m_cup_stick][${i}]']`).val());
            let g2 = Number($(`[name='form_data[${ugtpId}][form][binder_emulsifier][m_cup_stick_emulsion][${i}]']`).val());
            let g3 = Number($(`[name='form_data[${ugtpId}][form][binder_emulsifier][m_cup_stick_evaporation_emulsion][${i}]']`).val());

            console.log($(`[name='form_data[${ugtpId}][form][binder_emulsifier][m_cup_stick][${i}]']`))
            console.log(`[name='form_data[${ugtpId}][form][binder_emulsifier][m_cup_stick][${i}]']`)
            console.log(g3)

            let diff_g3_g1 = roundPlus(Math.abs(g3 - g1), 2);
            let diff_g2_g1 = roundPlus(Math.abs(g2 - g1), 2);

            console.log(diff_g3_g1)
            console.log(diff_g2_g1)

            $(`[name='form_data[${ugtpId}][form][binder_emulsifier][diff_g3_g1][${i}]']`).val(diff_g3_g1);
            $(`[name='form_data[${ugtpId}][form][binder_emulsifier][diff_g2_g1][${i}]']`).val(diff_g2_g1);

            let m = roundPlus((diff_g3_g1 / diff_g2_g1) * 100, 1);

            $(`[name='form_data[${ugtpId}][form][binder_emulsifier][content_binder_emulsifier][${i}]']`).val(m);

            arrAverage.push(m);
        }

        // m_average = roundPlus((arrAverage[0] + arrAverage[1]) / 2, 1);

        Math.round10  = function (value, exp) {
            return decimalAdjust("round", value, exp);
        };

        let vl_average = roundPlus((arrAverage[0] + arrAverage[1]) / 2, 2);
        let m_average = Math.round10(vl_average, -1);

        let percentVal = roundPlus(m_average * (1 / 100), 2);

        let diff_index = roundPlus(Math.abs(arrAverage[0] - arrAverage[1]), 1);

        if(diff_index > percentVal) {
            alert("Расхождение между двумя испытаниями больше допустимой величины в 1% (" + percentVal + ") от среднего арифметического и равно [" + diff_index + "]. Испытание некорректно. Необходимо повторить всю процедуру испытания на новых образцах.");

            for(let i = 0; i < 2; i++) {

                // $(`[name='form_data[${ugtpId}][form][binder_emulsifier][m_cup_stick][${i}]']`).val("");
                // $(`[name='form_data[${ugtpId}][form][binder_emulsifier][m_cup_stick_emulsion][${i}]']`).val("");
                // $(`[name='form_data[${ugtpId}][form][binder_emulsifier][m_cup_stick_evaporation_emulsion][${i}]']`).val("");

                // $(`[name='form_data[${ugtpId}][form][binder_emulsifier][diff_g3_g1][${i}]']`).val("");
                // $(`[name='form_data[${ugtpId}][form][binder_emulsifier][diff_g2_g1][${i}]']`).val("");

                $(`[name='form_data[${ugtpId}][form][binder_emulsifier][content_binder_emulsifier][${i}]']`).val("");
            }
            $(`[name='form_data[${ugtpId}][form][binder_emulsifier][average_binder_emulsifier]']`).val("");
            return false;
        }

        arrAverage.forEach(function(value, index) {

            let diff_binder_index = roundPlus(Math.abs(m_average - value), 1);
            if(diff_binder_index > percentVal) {
                alert("Значение содержания вяжущего с эмульгатором в испытании [" + (index + 1) + "] превышает допустимую величину в 1% (" + percentVal + ") от среднего арифметического и равно [" + diff_binder_index + "]. Результаты недостоверны.");
                $(`[name='form_data[${ugtpId}][form][binder_emulsifier][average_binder_emulsifier]']`).val("");
                return false;
            }
        });

        $(`[name='form_data[${ugtpId}][form][binder_emulsifier][average_binder_emulsifier]']`).val(m_average);
    }

    body.on("click", "#binderEmulsifier", function() {
        console.log('CLICK')

        GetBinderEmulsifier();
    });
})