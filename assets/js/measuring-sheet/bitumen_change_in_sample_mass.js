$(function ($) {
    const body = $('body')

    let ugtpId = $('.wrapper-bitumen-change-in-sample-mass').find('#ugtp_id').val()

    function roundPlus(x, n) {
        if(isNaN(x) || isNaN(n)) return false;
        let m = Math.pow(10, n);
        return Math.round(x * m) / m;
    }

    //Изменение массы после старения
    function GetMassChahge(index){

        M0 = Number($(`[name='form_data[${ugtpId}][form][mass_change][mass_glass_container][${index}]']`).val());
        M1 = Number($(`[name='form_data[${ugtpId}][form][mass_change][mass_glass_container_with_bitumen_before_aging][${index}]']`).val());
        M2 = Number($(`[name='form_data[${ugtpId}][form][mass_change][mass_glass_container_with_bitumen_after_aging][${index}]']`).val());

        return roundPlus((((M1 - M2) / (M1 - M0)) * 100), 1);
    }

    body.on("click", "#massChange", function () {

        dM_1 = GetMassChahge(0);
        dM_2 = GetMassChahge(1);

        let diff;
        if(dM_1 > dM_2){
            diff = dM_1 - dM_2;
        }
        else{
            diff = dM_2 - dM_1;
        }

        if(diff > 0.2){
            alert("Разница между двумя параллельными определениями превышает допустимое значение(0.2%) и равна " + diff + " %. Испытание следует повторить.");
            $(`[name='form_data[${ugtpId}][form][mass_change][mass_change_after_aging]']`).val("");
        }
        else{
            dM_ariphmetic = (dM_1 + dM_2) / 2;
            $(`[name='form_data[${ugtpId}][form][mass_change][mass_change_after_aging]']`).val(dM_ariphmetic);
        }

    });
})