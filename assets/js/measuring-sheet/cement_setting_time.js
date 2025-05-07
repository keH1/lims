$(function ($) {
    const body = $('body')

    //Определение содержания зерен пластинчатой (лещадной) и игловатой формы ГОСТ 33053-2014 (2 параллельных определения с расхождением не более 1%; результат среднее арифметическое значение с точностью до первого знака после запятой)
    body.on("click", "#calculateContentCementSettingTime", function () {
        const wrapperWaterAbsorption = $('.wrapper-cement-setting-time'),
            contentStartTimeClosing = wrapperWaterAbsorption.find('.start-time-closing').val(),
            contentSettingTime = wrapperWaterAbsorption.find('.setting-time').val(),
            contentEndSettingTime = wrapperWaterAbsorption.find('.end-setting-time').val(),
            inputStartSetting = wrapperWaterAbsorption.find('.start-setting'),
            inputEndSetting = wrapperWaterAbsorption.find('.end-setting')

        let stcDate = new Date(`2025-01-21 ${contentStartTimeClosing}`)
        let stDate = new Date(`2025-01-21 ${contentSettingTime}`)
        let estDate = new Date(`2025-01-21 ${contentEndSettingTime}`)

        let startSettingResult = roundInt((stDate - stcDate) / 60000, 5)
        let endSettingResult = roundInt((estDate - stcDate) / 60000, 15)

        inputStartSetting.val(startSettingResult)
        inputEndSetting.val(endSettingResult)
    })

    function roundInt(value, toNumber) {
        return Math.round(value / toNumber) * toNumber
    }
})