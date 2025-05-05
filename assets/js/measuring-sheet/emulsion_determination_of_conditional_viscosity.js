$(function ($) {
    const body = $('body')

    let ugtpId = $('.wrapper-determination-of-conditional-viscosity').find('#ugtp_id').val()

    function roundPlus(x, n) {
        if(isNaN(x) || isNaN(n)) return false;
        let m = Math.pow(10, n);
        return Math.round(x * m) / m;
    }

    // Определение условной вязкости эмульсии при температуре 40
    function GetEmulsionViscosity() {

        t1 = Number($(`[name='form_data[${ugtpId}][form][emulsion_viscosity][expiration_emulsion][0]']`).val());
        t2 = Number($(`[name='form_data[${ugtpId}][form][emulsion_viscosity][expiration_emulsion][1]']`).val());
        t3 = Number($(`[name='form_data[${ugtpId}][form][emulsion_viscosity][expiration_emulsion][2]']`).val());

        t_average = round((t1 + t2 + t3) / 3);

        percent = 10;
        result = Number(t_average / 100 * percent);

        let checkArr = [];
        for(let i = 0; i < 3; i++) {
            if(Number(Math.abs($(`[name='form_data[${ugtpId}][form][emulsion_viscosity][expiration_emulsion][${i}]']`).val() - t_average)) > result) {
                checkArr.push(i + 1);
            }
        }

        if(checkArr.length > 1) {
            checkArr.forEach(function(value, index) {
                alert("Время истечения в испытании [" + value + "] превышает значение среднего арифметического более чем на 10%. Испытание необходимо повторить.");
            });
            $(`[name='form_data[${ugtpId}][form][emulsion_viscosity][expiration_emulsion_average]']`).val("");
            return false;
        }

        compareArr = [t1, t2, t3];
        sorted = compareArr.slice().sort(function(a, b) {
            return a - b;
        });

        let maxValue = sorted[sorted.length - 1];

        let diffArr = [roundPlus(Math.abs(t1 - t2), 2), roundPlus(Math.abs(t1 - t3), 2), roundPlus(Math.abs(t2 - t3), 2)];

        let arrValue = [];
        $.each(diffArr, function(index, value) {

            if(maxValue <= 20) {
                if(value > 1) {
                    if(index == 0) {
                        alert("Расхождение между разностью первого и второго испытания равно [" + diffArr[0] + "] и превышает сходимость метода в [1]");
                        arrValue.push(value);
                    }
                    if(index == 1) {
                        alert("Расхождение между разностью первого и третьего испытания равно [" + diffArr[1] + "] и превышает сходимость метода в [1]");
                        arrValue.push(value);
                    }
                    if(index == 2) {
                        alert("Расхождение между разностью второго и третьего испытания равно [" + diffArr[2] + "] и превышает сходимость метода в [1]");
                        arrValue.push(value);
                    }
                }
            }

            if(maxValue > 20 && maxValue <= 40) {
                if(value > 2) {
                    if(index == 0) {
                        alert("Расхождение между разностью первого и второго испытания равно [" + diffArr[0] + "] и превышает сходимость метода в [2]");
                        arrValue.push(value);
                    }
                    if(index == 1) {
                        alert("Расхождение между разностью первого и третьего испытания равно [" + diffArr[1] + "] и превышает сходимость метода в [2]");
                        arrValue.push(value);
                    }
                    if(index == 2) {
                        alert("Расхождение между разностью второго и третьего испытания равно [" + diffArr[2] + "] и превышает сходимость метода в [2]");
                        arrValue.push(value);
                    }
                }
            }

            if(maxValue > 40) {
                if(value > 4) {
                    if(index == 0) {
                        alert("Расхождение между разностью первого и второго испытания равно [" + diffArr[0] + "] и превышает сходимость метода в [4]");
                        arrValue.push(value);
                    }
                    if(index == 1) {
                        alert("Расхождение между разностью первого и третьего испытания равно [" + diffArr[1] + "] и превышает сходимость метода в [4]");
                        arrValue.push(value);
                    }
                    if(index == 2) {
                        alert("Расхождение между разностью второго и третьего испытания равно [" + diffArr[2] + "] и превышает сходимость метода в [4]");
                        arrValue.push(value);
                    }
                }
            }
        });

        if(arrValue.length > 2) {
            $(`[name='form_data[${ugtpId}][form][emulsion_viscosity][expiration_emulsion_average]']`).val("");
            alert("Результаты недостоверны");
        }
        else {
            $(`[name='form_data[${ugtpId}][form][emulsion_viscosity][expiration_emulsion_average]']`).val(t_average);
        }
    }

    body.on("click", "#emulsionViscosity", function() {
        GetEmulsionViscosity();
    });
})