$(function ($) {
    const body = $('body')

    let ugtpId = $('.wrapper-emulsion-disintegration').find('#ugtp_id').val()

    function roundPlus(x, n) {
        if(isNaN(x) || isNaN(n)) return false;
        let m = Math.pow(10, n);
        return Math.round(x * m) / m;
    }

    // Индекс распада
    function GetDecayIndex() {

        let arrAverage = [];

        for(let i = 0; i < 2; i++) {
            m1 = Number($(`[name='form_data[${ugtpId}][form][decay_index][cup_spatula][${i}]']`).val());
            m2 = Number($(`[name='form_data[${ugtpId}][form][decay_index][cup_spatula_emulsion][${i}]']`).val());
            m3 = Number($(`[name='form_data[${ugtpId}][form][decay_index][cup_spatula_emulsion_filler][${i}]']`).val());

            if(m3 < m2) {
                alert("Значение m3 меньше значения m2 в испытании с номером чашки [" + (i + 1) + "] - разность отрицательная. Повторите испытание");
                return false;
            }

            if(m2 < m1) {
                alert("Значение m2 меньше значения m1 в испытании с номером чашки [" + (i + 1) + "] - разность отрицательная. Повторите испытание");
                return false;
            }

            diff_m3_m2 = roundPlus(m3 - m2, 2);
            $(`[name='form_data[${ugtpId}][form][decay_index][diff_m3_m2][${i}]']`).val(diff_m3_m2);

            diff_m2_m1 = roundPlus(m2 - m1, 2);
            $(`[name='form_data[${ugtpId}][form][decay_index][diff_m2_m1][${i}]']`).val(diff_m2_m1);

            m = round(((m3 - m2) / (m2 - m1)) * 100);
            $(`[name='form_data[${ugtpId}][form][decay_index][emulsion_index][${i}]']`).val(m);

            arrAverage.push(m);
        }

        m_average = round((arrAverage[0] + arrAverage[1]) / 2);

        let percentVal = m_average * (10 / 100);

        diff_index = Math.abs(arrAverage[0] - arrAverage[1]);

        arrAverage.forEach(function(value, index) {

            diff_decay_index = Math.abs(m_average - value);
            if(diff_decay_index > percentVal) {
                alert("Значение индекса распада в испытании [" + (index + 1) + "] превышает допустимую величину в 10% (" + percentVal + ") от среднего арифметического и равно [" + diff_decay_index + "]. Необходимо повторить испытания.");
                $(`[name='form_data[${ugtpId}][form][decay_index][emulsion_index_average]']`).val("");
                return false;
            }
            else
                $(`[name='form_data[${ugtpId}][form][decay_index][emulsion_index_average]']`).val(m_average);
        });

        if(diff_index > percentVal) {
            alert("Расхождение между двумя испытаниями больше допустимой величины в 10% (" + percentVal + ") от среднего арифметического и равно [" + diff_index + "]. Результат недостоверен.");
            $(`[name='form_data[${ugtpId}][form][decay_index][emulsion_index_average]']`).val("");
        }
        else
            $(`[name='form_data[${ugtpId}][form][decay_index][emulsion_index_average]']`).val(m_average);
    }

    body.on("click", "#decayIndex", function() {
        GetDecayIndex();
    });
})