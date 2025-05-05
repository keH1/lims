$(function ($) {
    const body = $('body')

    body.on("click", "#determinationOfCementCompressive", function () {
        const wrapperTrueDensity = $('.wrapper-cement-determination-of-compressive'),
            inputResult = wrapperTrueDensity.find('.result')

        let results = []

        for(let i = 0; i < 3; i++) {
            let contentBreakingLoad = wrapperTrueDensity.find(`.breaking-load-${i}`).val(),
                contentSizeOfSide = wrapperTrueDensity.find(`.size-of-side-${i}`).val(),
                contentBetweenSupport = wrapperTrueDensity.find(`.between-support-${i}`).val(),
                inputCompressiveResult = wrapperTrueDensity.find(`.compressive-result-${i}`)

            let result = (1.5 * Number(contentBreakingLoad) * Number(contentBetweenSupport)) / Math.pow(Number(contentSizeOfSide), 3)

            inputCompressiveResult.val(result.toFixed(2))
            results.push(result)
        }

        let averageResult = average(results)

        inputResult.val(averageResult.toFixed(2))
    })

    body.on("click", "#determinationOfCementBending", function () {
        const wrapperTrueDensity = $('.wrapper-cement-determination-of-bending'),
            inputResult = wrapperTrueDensity.find('.result')

        let results = []
        let needReCalculate = false

        for(let i = 0; i < 6; i++) {
            let contentBreakingLoad = wrapperTrueDensity.find(`.breaking-load-${i}`).val(),
                contentWorkingSurface = wrapperTrueDensity.find(`.working-surface-${i}`).val(),
                inputBendingResult = wrapperTrueDensity.find(`.bending-result-${i}`),
                tdParent = wrapperTrueDensity.find(`.bending-result-${i}`).parent().parent().find('td')

            tdParent.each(function () {
                $(this).css('background-color', '')
            })

            let result = Number(contentBreakingLoad) / Number(contentWorkingSurface)

            inputBendingResult.val(result.toFixed(2))

            results.push(result)
        }

        let max = Math.max.apply(null, results)
        let min = Math.min.apply(null, results)

        let averageResult = average(results)

        let differentMax = ((Number(max) - Number(averageResult)) / Number(averageResult)) * 100
        let differentMin = ((Number(min) - Number(averageResult)) / Number(averageResult)) * 100

        let index = 0

        if(differentMin < -10 && differentMax > 10) {
            needReCalculate = true

            let plusMin = -differentMin

            if(differentMax > plusMin) {
                index = results.indexOf(max)

                results.splice(index, 1)
            }else {
                index = results.indexOf(min)

                results.splice(index, 1)
            }
        }else if(differentMin < -10) {
            needReCalculate = true

            index = results.indexOf(min)

            results.splice(index, 1)
        }else if(differentMax > 10) {
            needReCalculate = true

            index = results.indexOf(max)

            results.splice(index, 1)
        }

        if(needReCalculate) {
            let tdParent = wrapperTrueDensity.find(`.breaking-load-${index}`).parent().parent().find('td')

            tdParent.each(function () {
                $(this).css('background-color', 'red')
            })

            wrapperTrueDensity.find('.align-middle').css('background-color', '')

            max = Math.max.apply(null, results)
            min = Math.min.apply(null, results)

            let arraySum = 0
            averageResult = results.reduce((accumulator, currentValue) => accumulator + currentValue, arraySum) / 5

            differentMax = ((Number(max) - Number(averageResult)) / Number(averageResult)) * 100
            differentMin = ((Number(min) - Number(averageResult)) / Number(averageResult)) * 100

            if(differentMin < -10 || differentMax > 10) {
                alert("Два значения прочности на сжатия отличны от среднего значения более чем на 10%.");
                inputResult.val(0)

                return
            }
        }

        inputResult.val(averageResult.toFixed(2))
    })
})