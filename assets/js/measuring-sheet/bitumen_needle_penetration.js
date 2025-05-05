$(function ($) {
    const body = $('body')

    let ugtpId = $('.wrapper-bitumen-needle-penetration').find('#ugtp_id').val()

    function roundPlus(x, n) {
        if(isNaN(x) || isNaN(n)) return false;
        let m = Math.pow(10, n);
        return Math.round(x * m) / m;
    }

    // Глубина проникания иглы
    function GetPenetrationDepth(array, index, temperature, num){

        let t_ariphmetic;

        t1 = Number($(`[name='form_data[${ugtpId}][form][needle_penetration_depth][penetration_depth][${array[0]}]']`).val());
        t2 = Number($(`[name='form_data[${ugtpId}][form][needle_penetration_depth][penetration_depth][${array[1]}]']`).val());
        t3 = Number($(`[name='form_data[${ugtpId}][form][needle_penetration_depth][penetration_depth][${array[2]}]']`).val());

        let compareArr = [t1, t2, t3];

        let sign = true;
        $.each(compareArr, function(index, value){
            if(!value){
                $(`[name='form_data[${ugtpId}][form][needle_penetration_depth][penetration_depth_average][${num}]']`).val("");
                sign = false;
                return false;
            }
        });

        if(sign){
            let sorted = compareArr.slice().sort(function(a, b) {
                return a - b;
            });

            let minValue = sorted[0],
                maxValue  = sorted[sorted.length - 1];

            let diffMaxMin = maxValue - minValue;

            t_ariphmetic = round((t1 + t2 + t3) / 3);

            let percentVal = t_ariphmetic * (3 / 100);

            if(maxValue <= 50){
                if(diffMaxMin > 2){
                    alert("Допустимое расхождение в величину 2 между наименьшим и наибольшим значениями при температуре в " + temperature + "°С превышено. Повторите испытания.");
                    $(`[name='form_data[${ugtpId}][form][needle_penetration_depth][penetration_depth_average][${index}]']`).val("");
                }
                else{
                    $(`[name='form_data[${ugtpId}][form][needle_penetration_depth][penetration_depth_average][${index}]']`).val(t_ariphmetic);
                }
            }

            if(maxValue > 50 && maxValue <= 150){
                if(diffMaxMin > 4){
                    alert("Допустимое расхождение в величину 4 между наименьшим и наибольшим значениями при температуре в " + temperature + "°С превышено. Повторите испытания.");
                    $(`[name='form_data[${ugtpId}][form][needle_penetration_depth][penetration_depth_average][${index}]']`).val("");
                }
                else{
                    $(`[name='form_data[${ugtpId}][form][needle_penetration_depth][penetration_depth_average][${index}]']`).val(t_ariphmetic);
                }
            }

            if(maxValue > 150 && maxValue <= 250){
                if(diffMaxMin > 6){
                    alert("Допустимое расхождение в величину 6 между наименьшим и наибольшим значениями при температуре в " + temperature + "°С превышено. Повторите испытания.");
                    $(`[name='form_data[${ugtpId}][form][needle_penetration_depth][penetration_depth_average][${index}]']`).val("");
                }
                else{
                    $(`[name='form_data[${ugtpId}][form][needle_penetration_depth][penetration_depth_average][${index}]']`).val(t_ariphmetic);
                }
            }

            if(maxValue > 250){
                if(diffMaxMin > percentVal){
                    alert("Допустимое расхождение в величину 3% от среднего арифметического и разностью наименьшего и наибольшего значениями при температуре в " + temperature + "°С превышено. Повторите испытания.");
                    $(`[name='form_data[${ugtpId}][form][needle_penetration_depth][penetration_depth_average][${index}]']`).val("");
                }
                else{
                    $(`[name='form_data[${ugtpId}][form][needle_penetration_depth][penetration_depth_average][${index}]']`).val(t_ariphmetic);
                }
            }
        }
    }

    body.on("click", "#penetrationDepth", function () {
        arr_1 = [0, 1, 2];
        arr_2 = [3, 4, 5];

        GetPenetrationDepth(arr_1, 0, 0, 0);
        GetPenetrationDepth(arr_2, 1, 25, 1);

    });
})