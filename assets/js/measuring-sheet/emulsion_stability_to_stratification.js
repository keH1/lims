$(function ($) {
    const body = $('body')

    let ugtpId = $('.wrapper-stability-to-stratification').find('#ugtp_id').val()

    function roundPlus(x, n) {
        if(isNaN(x) || isNaN(n)) return false;
        let m = Math.pow(10, n);
        return Math.round(x * m) / m;
    }

    // Устойчивости к расслоению при хранении до 7 суток
    function GetDelaminationResistance() {

        v = Number($(`[name='form_data[${ugtpId}][form][delamination_resistance][capacity_emulsion]']`).val());
        let arrAverage = [];

        for(let i = 0; i < 2; i++) {

            d1 = 100 - Number($(`[name='form_data[${ugtpId}][form][delamination_resistance][day][${i}][0]']`).val());
            d2 = 100 - Number($(`[name='form_data[${ugtpId}][form][delamination_resistance][day][${i}][1]']`).val());
            d3 = 100 - Number($(`[name='form_data[${ugtpId}][form][delamination_resistance][day][${i}][2]']`).val());
            d4 = 100 - Number($(`[name='form_data[${ugtpId}][form][delamination_resistance][day][${i}][3]']`).val());
            d5 = 100 - Number($(`[name='form_data[${ugtpId}][form][delamination_resistance][day][${i}][4]']`).val());
            d6 = 100 - Number($(`[name='form_data[${ugtpId}][form][delamination_resistance][day][${i}][5]']`).val());
            d7 = 100 - Number($(`[name='form_data[${ugtpId}][form][delamination_resistance][day][${i}][6]']`).val());

            p = roundPlus(((d1 + d2 + d3 + d4 + d5 + d6 + d7) / (7 * v)) * 100, 1);
            arrAverage.push(p);

            $(`[name='form_data[${ugtpId}][form][delamination_resistance][delamination_resistance_value][${i}]']`).val(p);
        }

        p_average = roundPlus((arrAverage[0] + arrAverage[1]) / 2, 1);

        console.log(arrAverage)
        console.log(p_average)

        let percentVal = roundPlus(p_average * (4 / 100), 1);

        diff_index = roundPlus(Math.abs(arrAverage[0] - arrAverage[1]), 1);

        if(diff_index > percentVal) {
            alert("Расхождение между двумя испытаниями больше допустимой величины в 4% (" + percentVal + ") от среднего арифметического и равно [" + diff_index + "]. Результаты недостоверны.");
            $(`[name='form_data[${ugtpId}][form][delamination_resistance][average_delamination_resistance]']`).val("");
            return false;
        }

        $(`[name='form_data[${ugtpId}][form][delamination_resistance][average_delamination_resistance]']`).val(p_average);
    }

    body.on("click", "#delaminationResistance", function() {
        GetDelaminationResistance();
    });
})