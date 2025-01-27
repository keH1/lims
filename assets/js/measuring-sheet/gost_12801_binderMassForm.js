<!-- Определение массовой доли вяжущего ГОСТ 12801-98 п.23.3 -->
$(function ($) {
    $('.change-trigger-bmf').on('input', function () {
        // Кол-во строк в таблице
        const rowCount = 2,
            $parent = $(this).closest('.measurement-wrapper');

        let binderAmount = $parent.find('#binderAmount').val();

        // Рассчитать массовую долю вяжущего, при дозировке вяжущего, включенного в 100 % состава асфальтобетонной смеси
        if (binderAmount === '100') {
            let arr = [];
            for (let i = 1; i <= rowCount; i++) {
                let g = $parent.find('.g-' + i).val(),
                    g1 = $parent.find('.g1-' + i).val(),
                    g2 = $parent.find('.g2-' + i).val();

                if (g === '' || g1 === '' || g2 === '' || g1 == g) {
                    continue;
                }

                let q = ((g1 - g2) / (g1 - g)) * 100;
                arr.push(q);

                $parent.find('.q-' + i).val(round(q, 2));
            }
            $parent.find('.q-avr').val(round(average(arr), 2));
        } else if (binderAmount === 'over_100') { // Рассчитать массовую долю вяжущего, при дозировке вяжущего сверх 100 % минеральной части смеси
            let arr = [];
            for (let i = 1; i <= rowCount; i++) {
                let g = $parent.find('.g-' + i).val(),
                    g1 = $parent.find('.g1-' + i).val(),
                    g2 = $parent.find('.g2-' + i).val();

                if (g === '' || g1 === '' || g2 === '' || g2 == g) {
                    continue;
                }

                let q = ((g1 - g2) / (g2 - g)) * 100;
                arr.push(q);

                $parent.find('.q-' + i).val(round(q, 2));
            }
            $parent.find('.q-avr').val(round(average(arr), 2));
        }
    });

    $('.gost_12801_binderMassForm #binderAmount').on('change', function () {
        const $parent = $(this).closest('.measurement-wrapper');

        let q = $parent.find("[class^='q-']");

        q.each(function (index, item) {
            $(this).val("");
        });
        $parent.find('.q-avr').val('');
    });

    // // Редактирование
    // $('.gost_12801_binderMassForm input:not([readonly])').on('input', function () {
    //     const inputReadonly = $(".gost_12801_binderMassForm input[readonly]:not('.do-not-clean')");
    //     inputReadonly.each(function (index, item) {
    //         $(this).val("");
    //     });
    // });
});
