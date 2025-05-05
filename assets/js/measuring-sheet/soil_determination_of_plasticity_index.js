$(function ($) {
    const body = $('body')

    //Определение содержания зерен пластинчатой (лещадной) и игловатой формы ГОСТ 33053-2014 (2 параллельных определения с расхождением не более 1%; результат среднее арифметическое значение с точностью до первого знака после запятой)
    body.on("click", "#determinationOfMPlasticityIndex", function () {
        const wrapperTrueDensity = $('.wrapper-soil-determination-of-plasticity-index'),
            contentYieldPoint = wrapperTrueDensity.find('.content-at-yield-point').val(),
            contentRollingBoundary = wrapperTrueDensity.find('.rolling-boundary').val(),
            inputResult = wrapperTrueDensity.find('.result')

        let result = Number(contentYieldPoint) - Number(contentRollingBoundary)

        inputResult.val(result.toFixed(2))
    })
})