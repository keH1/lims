$(function ($) {
    const body = $('body')

    //Определение содержания зерен пластинчатой (лещадной) и игловатой формы ГОСТ 33053-2014 (2 параллельных определения с расхождением не более 1%; результат среднее арифметическое значение с точностью до первого знака после запятой)
    body.on("click", "#determinationOfLowerLimit", function () {
        const wrapperTrueDensity = $('.wrapper-soil-determination-of-lower-limit'),
            contentMassWithBottle = wrapperTrueDensity.find('.mass-with-bottle').val(),
            contentMassDriedSoil = wrapperTrueDensity.find('.mass-dried-soil').val(),
            contentMassEmptyBottle = wrapperTrueDensity.find('.mass-empty-bottle').val(),
            inputResult = wrapperTrueDensity.find('.result')

        let result = (100 * ((Number(contentMassWithBottle) - Number(contentMassDriedSoil)) / (Number(contentMassDriedSoil) - Number(contentMassEmptyBottle)))).toFixed(2)

        inputResult.val(result)
    })
})