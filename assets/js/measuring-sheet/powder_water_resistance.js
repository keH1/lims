$(function ($) {
    const body = $('body')

    let ugtpId = $('#pwr_ugtp').val()

    function roundPlus(x, n) {
        if(isNaN(x) || isNaN(n)) return false;
        let m = Math.pow(10, n);
        return Math.round(x * m) / m;
    }

    function getExponentiation(n, pow) {
        let minus;
        if(pow < 0) minus = true;

        let mul = 1;
        let p = minus ? -pow : pow;
        for(let i = 1; i <= p; i++) {
            mul *= n;
        }
        return minus ? 1 / mul : mul;
    }

    // Водостойкость
    function GetWaterResistance() {

        p_a = $(`[name='form_data[${ugtpId}][water_resistance_32761][breaking_load_after_saturation_water_and_temperature][0]']`).val();
        p_a1 = $(`[name='form_data[${ugtpId}][water_resistance_32761][breaking_load_after_saturation_water_and_temperature][1]']`).val();
        p_a2 = $(`[name='form_data[${ugtpId}][water_resistance_32761][breaking_load_after_saturation_water_and_temperature][2]']`).val();

        p_b = $(`[name='form_data[${ugtpId}][water_resistance_32761][breaking_load_aged_before_testing_water][0]']`).val();
        p_b1 = $(`[name='form_data[${ugtpId}][water_resistance_32761][breaking_load_aged_before_testing_water][1]']`).val();
        p_b2 = $(`[name='form_data[${ugtpId}][water_resistance_32761][breaking_load_aged_before_testing_water][2]']`).val();

        f_1 = $(`[name='form_data[${ugtpId}][water_resistance_32761][initial_cross_area][0]']`).val();
        f_2 = $(`[name='form_data[${ugtpId}][water_resistance_32761][initial_cross_area][1]']`).val();
        f_3 = $(`[name='form_data[${ugtpId}][water_resistance_32761][initial_cross_area][2]']`).val();

        let factor = getExponentiation(10, -2);

        // Предел прочности при сжатии образцов после насыщения водой и термостатирования
        r_a = (p_a / f_1) * factor;
        r_a1 = (p_a1 / f_2) * factor;
        r_a2 = (p_a2 / f_3) * factor;

        // Предел прочности при сжатии образцов, выдержанных перед испытанием в воде
        r_b = (p_b / f_1) * factor;
        r_b1 = (p_b1 / f_2) * factor;
        r_b2 = (p_b2 / f_3) * factor;

        r_after = roundPlus(((r_a + r_a1 + r_a2) / 3), 1);
        r_before = roundPlus(((r_b + r_b1 + r_b2) / 3), 1);

        $(`[name='form_data[${ugtpId}][water_resistance_32761][compressive_strength_with_water_temperature]']`).val(r_after);
        $(`[name='form_data[${ugtpId}][water_resistance_32761][compressive_strength_before_water]']`).val(r_before);

        k = r_after / r_before;

        $(`[name='form_data[${ugtpId}][water_resistance_32761][water_resistance]']`).val(k);
    }

    body.on("click", "#water_resistance_32761", function() {
        GetWaterResistance();
    });
})