$(function ($) {
    const body = $('body')

    let ugtpId = $('.wrapper-bitumen-brittleness').find('#ugtp_id').val()

    /**
     * Округление
     * @param num
     * @param decimalPlaces
     * @returns {number}
     */
    function round(num, decimalPlaces = 0) {
        if (num < 0) {
            return -round(-num, decimalPlaces);
        }
        let p = Math.pow(10, decimalPlaces);
        let n = num * p;
        let f = n - Math.floor(n);
        let e = Number.EPSILON * n;

        return (f >= 0.5 - e) ? Math.ceil(n) / p : Math.floor(n) / p;
    }

    // Температура хрупкости
    function GetBrittlenessTemperature(){

        t1 = Number($(`[name='form_data[${ugtpId}][form][brittleness][brittleness_temperature][0]']`).val());
        t2 = Number($(`[name='form_data[${ugtpId}][form][brittleness][brittleness_temperature][1]']`).val());

        let t_ariphmetic = round((t1 + t2) / 2);

        let diff = round(Math.abs(t1 - t2));

        if(diff > 3){
            alert("Расхождение между двумя определениями превышает норму в 3°С и равно " + diff + "°С");
            $(`[name='form_data[${ugtpId}][form][brittleness][brittleness_temperature_average]']`).val("");
        }
        else{
            $(`[name='form_data[${ugtpId}][form][brittleness][brittleness_temperature_average]']`).val(t_ariphmetic);
        }
    }

    body.on("click", "#brittleness", function () {
        GetBrittlenessTemperature();
    });
})