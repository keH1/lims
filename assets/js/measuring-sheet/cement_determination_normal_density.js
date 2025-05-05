$(function ($) {
    const body = $('body')

    //Определение содержания зерен пластинчатой (лещадной) и игловатой формы ГОСТ 33053-2014 (2 параллельных определения с расхождением не более 1%; результат среднее арифметическое значение с точностью до первого знака после запятой)
    body.on("click", "#calculateContentCementDeterminationNormalDensity", function () {
        const wrapperWaterAbsorption = $('.wrapper-cement-determination-normal-density'),
            contentMassOfCement= wrapperWaterAbsorption.find('.mass-of-cement').val(),
            contentMassOfWater = wrapperWaterAbsorption.find('.mass-of-water').val(),
            inputContentResult = wrapperWaterAbsorption.find('.result')

        let result = Number(contentMassOfWater) / Number(contentMassOfCement)

        inputContentResult.val(result.toFixed(2))
    })
})