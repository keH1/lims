$(function ($) {
    const body = $('body')


    //Определение содержания зерен пластинчатой (лещадной) и игловатой формы ГОСТ 33053-2014 (2 параллельных определения с расхождением не более 1%; результат среднее арифметическое значение с точностью до первого знака после запятой)
    body.on("click", "#calculateContentHumidity", function () {
        const wrapperHumidity = $('.wrapper-humidity'),
            contentFirstMass = wrapperHumidity.find('.first-hum-mass').val(),
            contentSecondMass = wrapperHumidity.find('.second-hum-mass').val(),
            inputContentResult = wrapperHumidity.find('.result-mass')

        let top = (contentFirstMass - contentSecondMass)
        let second = top / contentSecondMass

        let result = second * 100

        inputContentResult.val(result.toFixed(2))
    })
})