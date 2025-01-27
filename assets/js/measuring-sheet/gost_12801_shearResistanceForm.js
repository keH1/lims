<!-- Определение характеристик сдвигоустойчивости ГОСТ 12801-98 п.18 -->
$(function ($) {
    $('.gost_12801_shearResistanceForm .change-trigger-srf').on('input', function () {
        const rowCount = 3;
        const $parent = $(this).closest('.measurement-wrapper');


        let arrAc = [];
        let arrRc = [];
        for (let i = 1; i <= rowCount; i++) {
            let pc = $parent.find('.pc-' + i).val(),
                lc = $parent.find('.lc-' + i).val(),
                fc = $parent.find('.fc-' + i).val();

            // Предел прочности при сжатии
            if (pc !== '' && fc !== '' &&  fc !== '0') {
                let rc = (pc * 1000 / fc) * (10 ** -2);
                arrRc.push(rc);

                $parent.find('.rc-' + i).val(round(rc, 1));
            } else { // Если "Предел прочности при сжатии" не рассчитывается, то получаем заполненные данные "Предел прочности при сжатии"
                let rc = +$parent.find('.rc-' + i).val();
                arrRc.push(rc);
            }

            // Рассчитать работу Аc, Дж
            if (pc !== '' && lc !== '') {
                let ac = (pc * lc) / 2;
                arrAc.push(ac);

                $parent.find('.ac-' + i).val(round(ac, 2));
            } else { // Если "Работа Аc" не рассчитывается, то получаем заполненные данные "Работы Аc"
                let ac = +$parent.find('.ac-' + i).val();
                arrAc.push(ac);
            }
        }
        let avrAc = arrAc.length ? round(average(arrAc), 2) : null;
        let avrRc = arrRc.length ? round(average(arrRc), 1) : null;
        $parent.find('.ac-avr').val(avrAc);
        $parent.find('.rc-avr').val(avrRc);


        // Рассчитать работу Аm, Дж
        let arrM = [];
        for (let i = 1; i <= rowCount; i++) {
            let pm = $parent.find('.pm-' + i).val(),
                lm = $parent.find('.lm-' + i).val();

            if (pm !== '' && lm !== '') {
                let am = (pm * lm) / 2;
                arrM.push(am);

                $parent.find('.am-' + i).val(round(am, 2));
            } else { // Если "Работа Аm" не рассчитывается, то получаем заполненные данные "Работы Аm"
                let am = +$parent.find('.am-' + i).val();
                arrM.push(am);
            }
        }
        let avrAm = arrM.length ? round(average(arrM), 2) : null;
        $parent.find('.am-avr').val(avrAm);


        // Коэффициент внутреннего трения
        if (avrAm !== null && avrAc !== null) {
            let tg = (3 * (avrAm - avrAc)) / (3 * avrAm - 2 * avrAc);
            $parent.find('#tg').val(round(tg, 2));

            if (avrRc === null) {
                return false;
            }

            // Лабораторный показатель сцепления при сдвиге
            let c = (1 / 6) * (3 - 2 * tg) * avrRc;
            $parent.find('#c').val(round(c, 2));
        }
    });
    $('.gost_12801_shearResistanceForm .change-trigger-srf-1').on('change', function () {
        const rowCount = 3;
        const $parent = $(this).closest('.measurement-wrapper');


        let arrAc = [];
        let arrRc = [];
        for (let i = 1; i <= rowCount; i++) {
            let pc = $parent.find('.pc-' + i).val(),
                lc = $parent.find('.lc-' + i).val(),
                fc = $parent.find('.fc-' + i).val();

            // Предел прочности при сжатии
            if (pc !== '' && fc !== '' &&  fc !== '0') {
                let rc = (pc / fc) * (10 ** -2);
                arrRc.push(rc);

                $parent.find('.rc-' + i).val(round(rc, 1));
            } else { // Если "Предел прочности при сжатии" не рассчитывается, то получаем заполненные данные "Предел прочности при сжатии"
                let rc = +$parent.find('.rc-' + i).val();
                arrRc.push(rc);
            }

            // Рассчитать работу Аc, Дж
            if (pc !== '' && lc !== '') {
                let ac = (pc * lc) / 2;
                arrAc.push(ac);

                $parent.find('.ac-' + i).val(round(ac, 2));
            } else { // Если "Работа Аc" не рассчитывается, то получаем заполненные данные "Работы Аc"
                let ac = +$parent.find('.ac-' + i).val();
                arrAc.push(ac);
            }
        }
        let avrAc = arrAc.length ? round(average(arrAc), 2) : null;
        let avrRc = arrRc.length ? round(average(arrRc), 1) : null;
        $parent.find('.ac-avr').val(avrAc);
        $parent.find('.rc-avr').val(avrRc);


        // Рассчитать работу Аm, Дж
        let arrM = [];
        for (let i = 1; i <= rowCount; i++) {
            let pm = $parent.find('.pm-' + i).val(),
                lm = $parent.find('.lm-' + i).val();

            if (pm !== '' && lm !== '') {
                let am = (pm * lm) / 2;
                arrM.push(am);

                $parent.find('.am-' + i).val(round(am, 2));
            } else { // Если "Работа Аm" не рассчитывается, то получаем заполненные данные "Работы Аm"
                let am = +$parent.find('.am-' + i).val();
                arrM.push(am);
            }
        }
        let avrAm = arrM.length ? round(average(arrM), 2) : null;
        $parent.find('.am-avr').val(avrAm);


        // Коэффициент внутреннего трения
        if (avrAm !== null && avrAc !== null) {
            let tg = (3 * (avrAm - avrAc)) / (3 * avrAm - 2 * avrAc);
            $parent.find('#tg').val(round(tg, 2));

            if (avrRc === null) {
                return false;
            }

            // Лабораторный показатель сцепления при сдвиге
            let c = (1 / 6) * (3 - 2 * tg) * avrRc;
            $parent.find('#c').val(round(c, 2));
        }
    });

    // Редактирование
    // $('.gost_12801_shearResistanceForm input:not([readonly])').on('input', function () {
    //     const inputReadonly = $(".gost_12801_shearResistanceForm input[readonly]");
    //     inputReadonly.each(function (index, item) {
    //         $(this).val("");
    //     });
    // });
});