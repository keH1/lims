$(function ($) {
    const body = $('body')

    //Определение содержания зерен пластинчатой (лещадной) и игловатой формы ГОСТ 33053-2014 (2 параллельных определения с расхождением не более 1%; результат среднее арифметическое значение с точностью до первого знака после запятой)
    body.on("click", "#calculateContentDensityAndAbsorption", function () {
        const wrapperDensityAndAbsorption = $('.wrapper-density-and-absorption'),
            contentMassInAir = wrapperDensityAndAbsorption.find('.mass-in-air').val(),
            contentMassInAirWater = wrapperDensityAndAbsorption.find('.mass-in-air-water').val(),
            contentMassInWater = wrapperDensityAndAbsorption.find('.mass-in-water').val(),
            contentWaterDensity = wrapperDensityAndAbsorption.find('.water-density').val(),
            inputContentWaterAbsorption = wrapperDensityAndAbsorption.find('.result-water-absorption'),
            inputContentResult = wrapperDensityAndAbsorption.find('.result-water-density'),
            contentMassInAir1 = wrapperDensityAndAbsorption.find('.mass-in-air1').val(),
            contentMassInAirWater1 = wrapperDensityAndAbsorption.find('.mass-in-air-water1').val(),
            contentMassInWater1 = wrapperDensityAndAbsorption.find('.mass-in-water1').val(),
            contentWaterDensity1 = wrapperDensityAndAbsorption.find('.water-density1').val(),
            inputContentWaterAbsorption1 = wrapperDensityAndAbsorption.find('.result-water-absorption1'),
            inputContentResult1 = wrapperDensityAndAbsorption.find('.result-water-density1'),
            inputContentAvgWaterDensity = wrapperDensityAndAbsorption.find('.avg-water-density'),
            inputContentAvgWaterAbsorption = wrapperDensityAndAbsorption.find('.avg-water-absorption')

        let bottomResult = contentMassInAirWater - contentMassInWater
        let topAbsorption = contentMassInAirWater * contentMassInAir
        let bottomResult1 = contentMassInAirWater1 - contentMassInWater1
        let topAbsorption1 = contentMassInAirWater1 * contentMassInAir1

        let result = contentMassInAir / bottomResult * contentWaterDensity
        let resultAbsorption = topAbsorption / contentMassInAir * 100

        let result1 = contentMassInAir1 / bottomResult1 * contentWaterDensity1
        let resultAbsorption1 = topAbsorption1 / contentMassInAir1 * 100

        inputContentResult.val(result.toFixed(2))
        inputContentWaterAbsorption.val(resultAbsorption.toFixed(2))

        inputContentResult1.val(result1.toFixed(2))
        inputContentWaterAbsorption1.val(resultAbsorption1.toFixed(2))

        let arr = [result, result1]
        let arr1 = [resultAbsorption, resultAbsorption1]

        inputContentAvgWaterDensity.val(average(arr).toFixed(2))
        inputContentAvgWaterAbsorption.val(average(arr1).toFixed(2))
    })
})