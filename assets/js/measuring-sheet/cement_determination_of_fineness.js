$(function ($) {
    const body = $('body')

    //Определение содержания зерен пластинчатой (лещадной) и игловатой формы ГОСТ 33053-2014 (2 параллельных определения с расхождением не более 1%; результат среднее арифметическое значение с точностью до первого знака после запятой)
    body.on("click", "#calculateContentCementDeterminationOfFineness", function () {
        const wrapperWaterAbsorption = $('.wrapper-cement-determination-of-fineness'),
            trHidden = wrapperWaterAbsorption.find('.hidden-fineness'),
            inputContentResult = wrapperWaterAbsorption.find('.result')

        let results = []

        let count = (typeof trHidden.attr('hidden') !== typeof undefined && trHidden.attr('hidden') !== false) ? 2 : 3;

        for(let i = 0; i < count; i++) {
            let contentMassOfCement = wrapperWaterAbsorption.find(`.mass-of-cement-${i}`).val(),
                contentMassResidue = wrapperWaterAbsorption.find(`.mass-residue-${i}`).val(),
                inputContentSieveResidue = wrapperWaterAbsorption.find(`.sieve-residue-${i}`)

            let result = (Number(contentMassResidue) / Number(contentMassOfCement)) * 100

            inputContentSieveResidue.val(result.toFixed(2))

            results.push(result)
        }

        let averageValue = average(results)

        let max = Math.max.apply(null, results)
        let min = Math.min.apply(null, results)

        let diffMin = ((min - averageValue) / averageValue) * 100
        let diffMax = ((max - averageValue) / averageValue) * 100

        if(diffMax > 1 || diffMin < -1) {
            if(typeof trHidden.attr('hidden') !== typeof undefined && trHidden.attr('hidden') !== false) {
                alert('Расхождение одного из результатов испытания и среднеарифм. значения более 1%. Заполните определение №3')

                trHidden.removeAttr('hidden')

                return
            }
        }

        inputContentResult.val(averageValue.toFixed(2))
    })

    body.on('change', '.hide-tr', function () {
        const wrapperWaterAbsorption = $('.wrapper-cement-determination-of-fineness'),
            trHidden = wrapperWaterAbsorption.find('.hidden-fineness'),
            inputContentSieveResidue = wrapperWaterAbsorption.find(`.sieve-residue-2`)

        if(!(typeof trHidden.attr('hidden') !== typeof undefined && trHidden.attr('hidden') !== false)) {
            trHidden.attr('hidden', true)

            inputContentSieveResidue.val('')
        }
    })
})