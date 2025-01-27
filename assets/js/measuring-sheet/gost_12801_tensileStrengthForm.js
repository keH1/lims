<!-- Определение предела прочности на растяжение при расколе ГОСТ 12801-98 п.16 -->
$(function ($) {
    $('.gost_12801_tensileStrengthForm .change-trigger-tsf').on('input', function () {
        // Кол-во строк в таблице
        const rowCount = 3,
            $parent = $(this).closest('.measurement-wrapper');

        let arr = [];
        for (let i = 1; i <= rowCount; i++) {
            let p = $parent.find('.P-' + i).val(),
                h = $parent.find('.h-' + i).val(),
                d = $parent.find('.d-' + i).val();

            if (p === '' || h === '' || d === '') {
                continue;
            }

            let r = (p / (h * d)) *  (10 ** -2);
            arr.push(r);

            $parent.find('.r-' + i).val(round(r, 1));
        }
        $parent.find('.r-avr').val(round(average(arr), 1));
    });

    // // Редактирование
    // $('.gost_12801_tensileStrengthForm input:not([readonly])').on('input', function () {
    //     const inputReadonly = $(".gost_12801_tensileStrengthForm input[readonly]:not('.do-not-clean')");
    //     inputReadonly.each(function (index, item) {
    //         $(this).val("");
    //     });
    // });
});
