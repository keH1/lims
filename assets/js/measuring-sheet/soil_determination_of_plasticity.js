$(function ($) {
    const body = $('body')

    //Определение содержания зерен пластинчатой (лещадной) и игловатой формы ГОСТ 33053-2014 (2 параллельных определения с расхождением не более 1%; результат среднее арифметическое значение с точностью до первого знака после запятой)
    body.on("click", "#determinationOfPlasticity", function () {
        const wrapperTrueDensity = $('.wrapper-soil-determination-of-plasticity'),
            contentMassWithRingAndPlates = wrapperTrueDensity.find('.mass-with-ring-and-plates').val(),
            contentMassRing = wrapperTrueDensity.find('.mass-ring').val(),
            contentMassPlates = wrapperTrueDensity.find('.mass-plates').val(),
            contentVolumeRing = wrapperTrueDensity.find('.volume-ring').val(),
            inputResult = wrapperTrueDensity.find('.result')

        let result = (Number(contentMassWithRingAndPlates) - Number(contentMassRing) - Number(contentMassPlates)) * contentVolumeRing;

        inputResult.val(result)
    })
})