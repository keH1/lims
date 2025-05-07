$(function ($) {
    const body = $('body')

    let ugtpId = $('#pmd_ugtp').val()

    function roundPlus(x, n) {
        if(isNaN(x) || isNaN(n)) return false;
        let m = Math.pow(10, n);
        return Math.round(x * m) / m;
    }

    // Средняя плотность
    function GetDensityAverage() {

        m_1 = $(`[name='form_data[${ugtpId}][density_average_P52129][weight_cylinder_with_mineral_dust][0]']`).val();
        m_2 = $(`[name='form_data[${ugtpId}][density_average_P52129][weight_cylinder_with_mineral_dust][1]']`).val();

        m_1_1 = $(`[name='form_data[${ugtpId}][density_average_P52129][weight_cylinder_with_pallet][0]']`).val();
        m_1_2 = $(`[name='form_data[${ugtpId}][density_average_P52129][weight_cylinder_with_pallet][1]']`).val();

        v_1 = $(`[name='form_data[${ugtpId}][density_average_P52129][capacity_dust][0]']`).val();
        v_2 = $(`[name='form_data[${ugtpId}][density_average_P52129][capacity_dust][1]']`).val();

        p_1 = roundPlus((m_1 - m_1_1) / v_1, 2);
        p_2 = roundPlus((m_2 - m_1_2) / v_2, 2);

        p_m = roundPlus((p_1 + p_2) / 2, 2);
        $(`[name='form_data[${ugtpId}][density_average_P52129][average_density]']`).val(p_m);

        console.log(`[name='form_data[${ugtpId}][density_average_P52129][weight_cylinder_with_mineral_dust][0]']`)
        console.log(m_1)
        console.log(`[name='form_data[${ugtpId}][density_average_P52129][weight_cylinder_with_mineral_dust][1]']`)
        console.log(m_2)
        console.log(m_1_1)
        console.log(m_1_2)

        let diff;
        if(p_1 > p_2) {
            diff = roundPlus((p_1 - p_2), 2);
        }
        else {
            diff = roundPlus((p_2 - p_1), 2);
        }

        let diffAbs = Math.abs(diff);

        if(diffAbs > 0.02) {
            alert('Разница между определениями превышает 0.02 г/см_3 и равна ' + diff + " г/см_3. Необходимо повторить испытание");
            // $("[name='average_density']").val("");
        }
    }

    body.on("click", "#density_average", function() {
        GetDensityAverage();
    });

    function SelectNonAcMP() {
        $('.average_density').removeClass("panel-hidden");
        $('.non_ac').removeClass("panel-hidden");
        $('.porosity').removeClass("panel-hidden");
        $('.v_mp_2').removeClass("panel-hidden");

        $('.ac').addClass("panel-hidden");
        $('.v_mp_1').addClass("panel-hidden");
    }

    function SelectMP1() {
        $('.mp_1').removeClass("panel-hidden");
        $('.average_density').addClass("panel-hidden");
        $('.ac').addClass("panel-hidden");
        $('.non_ac').addClass("panel-hidden");
        $('.porosity').addClass("panel-hidden");

        $('.v_mp_1').addClass("panel-hidden");
        $('.v_mp_2').addClass("panel-hidden");

        $('#select_type option[value=0]').prop('selected', true);
    }

    function SelectAcMP(nonAC = 0) {
        $('.average_density').removeClass("panel-hidden");

        if(nonAC == 1) {
            $('.ac').addClass("panel-hidden");
            $('.porosity').removeClass("panel-hidden");

            $('.non_ac').removeClass("panel-hidden");

            $('.v_mp_1').removeClass("panel-hidden");
            $('.v_mp_2').addClass("panel-hidden");
        }
        else {
            $('.ac').removeClass("panel-hidden");
            $('.porosity').removeClass("panel-hidden");

            $('.non_ac').addClass("panel-hidden");

            $('.v_mp_1').removeClass("panel-hidden");
            $('.v_mp_2').addClass("panel-hidden");
        }
    }

    function SelectMP2() {
        $('.mp_1').addClass("panel-hidden");
        $('.ac').addClass("panel-hidden");
        SelectNonAcMP();
        // $('.mp_1').addClass("panel-hidden");
        // $('.ac').addClass("panel-hidden");
    }

    body.on("change", "#select_type", function() {

        if($(this).val() == 1) {
            SelectAcMP();
        }

        if($(this).val() == 2 && $('#select_mp').val() == 1) {
            SelectAcMP(1);
        }

        if($(this).val() == 2 && $('#select_mp').val() == 2) {
            SelectNonAcMP();
        }
    });

    body.on("change", "#select_mp", function() {

        if($(this).val() == 1) {
            $("#porosity_save").trigger("click");
            if($(`[name='form_data[${ugtpId}][porosity_P52129][true_density_non_ac]']`).val()) {
                let ask = confirm("При изменении марки порошка удалится расчет значения истинной плотности для МП-2. Хотите продолжить?");
                if(ask) {
                    $(`[name='form_data[${ugtpId}][porosity_P52129][true_density_non_ac]']`).val("");
                    $(`[name='form_data[${ugtpId}][porosity_P52129][porosity_result]']`).val("");
                }
                else {
                    return false;
                }
            }
            SelectMP1();
        }

        if($(this).val() == 2) {
            $('#select_type option[value=2]').prop('selected', true);
            $("#porosity_save").trigger("click");
            if($(`[name='form_data[${ugtpId}][porosity_P52129][true_density_ac]']`).val()) {
                let ask = confirm("При изменении марки порошка удалится расчет значения истинной плотности для МП-1. Хотите продолжить?");
                if(ask) {
                    $(`[name='form_data[${ugtpId}][porosity_P52129][true_density_ac]']`).val("");
                    $(`[name='form_data[${ugtpId}][porosity_P52129][porosity_result]']`).val("");
                }
                else {
                    return false;
                }
            }
            SelectMP2();
        }
    });

    // Неактивированный порошок
    function calcNonActiveDust() {

        let p, p1;
        function calcDensity(m, m1, m2, m3) {

            p = roundPlus((m1 - m) / (Number(m1 - m) + Number(m3) - m2), 2);
            return p;
        }

        function calcDensity_2(m, m1, m2, m3) {

            p1 = roundPlus((m1 - m) / (Number(m1 - m) + Number(m3) - m2), 2);
            return p1;
        }

        p = calcDensity($(`[name='form_data[${ugtpId}][porosity_P52129][dust_flask_mass_non_ac][0]']`).val(),
            $(`[name='form_data[${ugtpId}][porosity_P52129][mass_empty_flask_non_ac][0]']`).val(),
            $(`[name='form_data[${ugtpId}][porosity_P52129][mass_flask_with_distilled_water][0]']`).val(),
            $(`[name='form_data[${ugtpId}][porosity_P52129][mass_flask_with_dust_and_distilled_water][0]']`).val());

        p1 = calcDensity_2($(`[name='form_data[${ugtpId}][porosity_P52129][dust_flask_mass_non_ac][1]']`).val(),
            $(`[name='form_data[${ugtpId}][porosity_P52129][mass_empty_flask_non_ac][1]']`).val(),
            $(`[name='form_data[${ugtpId}][porosity_P52129][mass_flask_with_distilled_water][1]']`).val(),
            $(`[name='form_data[${ugtpId}][porosity_P52129][mass_flask_with_dust_and_distilled_water][1]']`).val());

        let diffDensity;
        if(p > p1) {
            diffDensity = roundPlus((p - p1), 2);
        }
        else {
            diffDensity = roundPlus((p1 - p), 2);
        }

        if(diffDensity > 0.02) {
            alert('Разница между определениями превышает 0.02 г/см_3 и равна ' + diffDensity + " г/см_3. Необходимо повторить испытание");
        }

        arithmeticDensity_none_ac = roundPlus(((p + p1) / 2), 2);
        $(`[name='form_data[${ugtpId}][porosity_P52129][true_density_non_ac]']`).val(arithmeticDensity_none_ac);
    }

    // Активированный порошок
    function calcActiveDust() {

        let p, p1;
        function calcDensity(m, m1, m2, m3){
            p = roundPlus((m1 - m) / (Number(m1 - m) + Number(m3) - m2), 2);
            return p;
        }
        function calcDensity_2(m, m1, m2, m3, p_B){
            p1 = roundPlus((m1 - m) / (Number(m1 - m) + Number(m3) - m2), 2);
            return p1;
        }

        p = calcDensity($(`[name='form_data[${ugtpId}][porosity_P52129][dust_flask_mass_ac][0]']`).val(),
            $(`[name='form_data[${ugtpId}][porosity_P52129][mass_empty_flask_ac][0]']`).val(),
            $(`[name='form_data[${ugtpId}][porosity_P52129][mass_flask_with_wetting_agent][0]']`).val(),
            $(`[name='form_data[${ugtpId}][porosity_P52129][mass_flask_with_dust_and_wetting_agent][0]']`).val());

        p1 = calcDensity_2($(`[name='form_data[${ugtpId}][porosity_P52129][dust_flask_mass_ac][1]']`).val(),
            $(`[name='form_data[${ugtpId}][porosity_P52129][mass_empty_flask_ac][1]']`).val(),
            $(`[name='form_data[${ugtpId}][porosity_P52129][mass_flask_with_wetting_agent][1]']`).val(),
            $(`[name='form_data[${ugtpId}][porosity_P52129][mass_flask_with_dust_and_wetting_agent][1]']`).val());

        let diffDensity;

        if(p > p1) {
            diffDensity = roundPlus((p - p1), 2);
        }
        else {
            diffDensity = roundPlus((p1 - p), 2);
        }

        if(diffDensity > 0.02) {
            alert('Разница между определениями превышает 0.02 г/см_3 и равна ' + diffDensity + " г/см_3. Необходимо повторить испытание");
        }

        arithmeticDensity_ac = roundPlus(((p + p1) / 2), 2);
        $(`[name='form_data[${ugtpId}][porosity_P52129][true_density_ac]']`).val(arithmeticDensity_ac);
    }

    body.on("click", "#non_activ_dust", function() {
        calcNonActiveDust();
    });

    body.on("click", "#activ_dust", function() {
        calcActiveDust();
    });

    function GetPorosity(mp, type = 0) {

        let p_m = Number($(`[name='form_data[${ugtpId}][density_average_P52129][average_density]']`).val());
        let arithmeticDensity
        let prefix

        if (mp == 1 && type == 1) {
            arithmeticDensity = $(`[name='form_data[${ugtpId}][porosity_P52129][true_density_ac]']`).val();
            prefix = "ac"

        }
        if (mp == 1 && type == 2) {
            arithmeticDensity = $(`[name='form_data[${ugtpId}][porosity_P52129][true_density_non_ac]']`).val();
            prefix = "ac"
        }
        if (mp == 2) {
            arithmeticDensity = $(`[name='form_data[${ugtpId}][porosity_P52129][true_density_non_ac]']`).val();
            prefix = "non_ac"
        }

        V_por = Math.round((1 - (p_m / arithmeticDensity)) * 100);
        $(`[name='form_data[${ugtpId}][porosity_P52129][porosity_${prefix}]']`).val(V_por);

        return $(`[name='form_data[${ugtpId}][porosity_P52129][porosity_${prefix}]']`).val()
    }

    body.on("click", "#porosity", function() {
        let selectMp = Number($("#select_mp").val())
        let typeMp = Number($("#select_type").val())

        let result = GetPorosity(selectMp, typeMp);
        $(`[name='form_data[${ugtpId}][porosity_P52129][porosity_result]']`).val(result);
    });
})