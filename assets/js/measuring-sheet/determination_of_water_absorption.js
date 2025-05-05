$(function ($) {
    const body = $('body')

    //Определение содержания зерен пластинчатой (лещадной) и игловатой формы ГОСТ 33053-2014 (2 параллельных определения с расхождением не более 1%; результат среднее арифметическое значение с точностью до первого знака после запятой)
    body.on("click", "#calculateContentWaterAbsorption", function () {
        const wrapperWaterAbsorption = $('.wrapper-water-absorption'),
            contentMassOfMeasured = wrapperWaterAbsorption.find('.mass-of-measured').val(),
            contentSampleOfCrushed = wrapperWaterAbsorption.find('.sample-of-crushed').val(),
            inputContentResult = wrapperWaterAbsorption.find('.result-water-absorption')

        let top = 100 * (contentMassOfMeasured - contentSampleOfCrushed)
        let result = top / contentSampleOfCrushed

        inputContentResult.val(result.toFixed(2))
    })
})