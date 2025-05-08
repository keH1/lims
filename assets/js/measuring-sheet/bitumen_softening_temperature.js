$(function ($) {
    const body = $('body')

    let ugtpId = $('.wrapper-bitumen-softening-temperature').find('#ugtp_id').val()

    // Температура по кольцу и шару
    function GetTemperatureRaB(){

        t1 = Number($(`[name='form_data[${ugtpId}][form][temperatureRaB][temperature][0]']`).val());
        t2 = Number($(`[name='form_data[${ugtpId}][form][temperatureRaB][temperature][1]']`).val());

        let diff;
        if(t1 > t2){
            diff = t1 - t2;
        }
        else{
            diff = t2 - t1;
        }

        if(diff > 1){
            alert("Разница между двумя параллельными определениями превышает норму и равна " + diff + ". Испытание следует повторить.");
            $(`[name='form_data[${ugtpId}][form][temperatureRaB][temperature_average]']`).val("");
        }
        else{
            // t_ariphmetic = roundPlus(((t1 + t2) / 2), 1);
            t_ariphmetic = round((t1 + t2) / 2);
            $(`[name='form_data[${ugtpId}][form][temperatureRaB][temperature_average]']`).val(t_ariphmetic);
        }
    }

    body.on("click", "#temperatureRaB", function () {
        GetTemperatureRaB();
    });
})