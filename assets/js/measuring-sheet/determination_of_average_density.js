$(function ($) {
    const body = $('body')

    //Определение содержания зерен пластинчатой (лещадной) и игловатой формы ГОСТ 33053-2014 (2 параллельных определения с расхождением не более 1%; результат среднее арифметическое значение с точностью до первого знака после запятой)
    body.on("click", "#calculateContentAverageDensity", function () {
        const wrapperAverageDensity = $('.wrapper-average-density'),
            contentDensityWater = wrapperAverageDensity.find('.density-water').val(),
            contentOvenDriedVolumetric = wrapperAverageDensity.find('.oven-dried-volumetric').val(),
            contentWaterSaturatedState = wrapperAverageDensity.find('.water-saturated-state').val(),
            contentBasketWaterSaturated = wrapperAverageDensity.find('.basket-water-saturated').val(),
            contentEmptyMesh = wrapperAverageDensity.find('.empty-mesh').val(),
            contentTrueDensity = $('.result-true-density').val(),
            inputContentPorosity = wrapperAverageDensity.find('.porosity-average-density'),
            inputContentResult = wrapperAverageDensity.find('.result-average-density'),
            contentDensityWater1 = wrapperAverageDensity.find('.density-water1').val(),
            contentOvenDriedVolumetric1 = wrapperAverageDensity.find('.oven-dried-volumetric1').val(),
            contentWaterSaturatedState1 = wrapperAverageDensity.find('.water-saturated-state1').val(),
            contentBasketWaterSaturated1 = wrapperAverageDensity.find('.basket-water-saturated1').val(),
            contentEmptyMesh1 = wrapperAverageDensity.find('.empty-mesh1').val(),
            contentTrueDensity1 = $('.result-true-density').val(),
            inputContentPorosity1 = wrapperAverageDensity.find('.porosity-average-density1'),
            inputContentResult1 = wrapperAverageDensity.find('.result-average-density1'),
            inputContentAvgAverageDensity = wrapperAverageDensity.find('.avg-result-average-density'),
            inputContentAvgPorosity = wrapperAverageDensity.find('.avg-porosity-average-density')

        let top = contentDensityWater * contentOvenDriedVolumetric
        let bottom = contentWaterSaturatedState - (contentBasketWaterSaturated - contentEmptyMesh)

        let result = top / bottom
        let porosity = (1 - result / contentTrueDensity) * 100

        inputContentResult.val(result.toFixed(2))
        inputContentPorosity.val(porosity.toFixed(2))

        let top1 = contentDensityWater1 * contentOvenDriedVolumetric1
        let bottom1 = contentWaterSaturatedState1 - (contentBasketWaterSaturated1 - contentEmptyMesh1)

        let result1 = top1 / bottom1
        let porosity1 = (1 - result1 / contentTrueDensity1) * 100

        inputContentResult1.val(result1.toFixed(2))
        inputContentPorosity1.val(porosity1.toFixed(2))

        let arr = [result, result1]
        let arr1 = [porosity, porosity1]

        inputContentAvgAverageDensity.val(average(arr).toFixed(2))
        inputContentAvgPorosity.val(average(arr1).toFixed(2))
    })
})