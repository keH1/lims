$(function ($) {
    const body = $('body')

    let ugtpId = $('#pm_ugtp').val()

    function roundPlus(x, n) {
        if(isNaN(x) || isNaN(n)) return false;
        let m = Math.pow(10, n);
        return Math.round(x * m) / m;
    }

    // Влажность
    function GetHumidity() {

        m = $(`[name='form_data[${ugtpId}][humidity_32761][before_drying_mass_cup][0]']`).val();
        m1 = $(`[name='form_data[${ugtpId}][humidity_32761][after_drying_mass_cup][0]']`).val();
        m2 = $(`[name='form_data[${ugtpId}][humidity_32761][mass_cup][0]']`).val();

        m_2 = $(`[name='form_data[${ugtpId}][humidity_32761][before_drying_mass_cup][1]']`).val();
        m1_2 = $(`[name='form_data[${ugtpId}][humidity_32761][after_drying_mass_cup][1]']`).val();
        m2_2 = $(`[name='form_data[${ugtpId}][humidity_32761][mass_cup][1]']`).val();

        w = ((m - m1) / (m1 - m2)) * 100;
        w_2 = ((m_2 - m1_2) / (m1_2 - m2_2)) * 100;

        w_f = roundPlus(((w + w_2) / 2), 1);
        $(`[name='form_data[${ugtpId}][humidity_32761][humidity]']`).val(w_f);

        w_f_diff = roundPlus((w - w_2), 1);

        if(w_f_diff > 0.2){
            alert('Разница между определениями влажности минерального порошка превышает 0.2 % и равна ' + w_f_diff + " %. Необходимо повторить испытание");
        }
    }

    body.on("click", "#humidity_32761", function() {
        GetHumidity();
    });
})