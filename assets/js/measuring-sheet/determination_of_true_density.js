$(function ($) {
    const body = $('body')


    //Определение содержания зерен пластинчатой (лещадной) и игловатой формы ГОСТ 33053-2014 (2 параллельных определения с расхождением не более 1%; результат среднее арифметическое значение с точностью до первого знака после запятой)
    body.on("click", "#calculateContentTrueDensity", function () {
        const wrapperTrueDensity = $('.wrapper-true-density'),
            contentPycnometerMaterial = wrapperTrueDensity.find('.pycnometer-material').val(),
            contentEmpty = wrapperTrueDensity.find('.empty-mass').val(),
            contentDensityDistilledWater = wrapperTrueDensity.find('.density-of-distilled-water').val(),
            contentPycnometerDistilledWater = wrapperTrueDensity.find('.pycnometer-distilled-water').val(),
            contentPycnometerDistilledWaterMaterial = wrapperTrueDensity.find('.pycnometer-distilled-water-material').val(),
            inputContentResult = wrapperTrueDensity.find('.result-true-density'),
            contentPycnometerMaterial1 = wrapperTrueDensity.find('.pycnometer-material1').val(),
            contentEmpty1 = wrapperTrueDensity.find('.empty-mass1').val(),
            contentDensityDistilledWater1 = wrapperTrueDensity.find('.density-of-distilled-water1').val(),
            contentPycnometerDistilledWater1 = wrapperTrueDensity.find('.pycnometer-distilled-water1').val(),
            contentPycnometerDistilledWaterMaterial1 = wrapperTrueDensity.find('.pycnometer-distilled-water-material1').val(),
            inputContentResult1 = wrapperTrueDensity.find('.result-true-density1'),
            inputContentAvgValue = wrapperTrueDensity.find('.result-avg-value')

        let top = (contentPycnometerMaterial - contentEmpty) * contentDensityDistilledWater
        let bottom = contentPycnometerMaterial - Number(contentEmpty) + Number(contentPycnometerDistilledWater) - contentPycnometerDistilledWaterMaterial;

        let result = top / bottom

        let top1 = (contentPycnometerMaterial1 - contentEmpty1) * contentDensityDistilledWater1
        let bottom1 = contentPycnometerMaterial1 - Number(contentEmpty1) + Number(contentPycnometerDistilledWater1) - contentPycnometerDistilledWaterMaterial1;

        let result1 = top1 / bottom1

        inputContentResult.val(result.toFixed(2))
        inputContentResult1.val(result1.toFixed(2))

        let arr = [result, result1]

        inputContentAvgValue.val(average(arr).toFixed(2))
    })
})