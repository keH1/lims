function runoff(mA, mB, mC, mD) {
    let M = mA - mB

    if ( M === 0.0 ) {
        return false
    }

    return (mC - mD) * 100 / M
}

function runoffShma(g1, g2, g3) {
    let M = g2 - g1

    if ( M === 0.0 ) {
        return false
    }

    return 100 * round((g3 - g1), 2) / round(M, 2)
}

$(function () {
    let $averSMA  = $('#averSMA')
    let $averShMA = $('#averShMA')

    $('.change-trigger-rnf-1').on('input', function () {
        let $definitionContainer = $('#runoff_formSMA').find('.definition')
        let arrResult = []

        $definitionContainer.each(function () {
            let massA = $(this).find('.mA').val(),
                massB = $(this).find('.mB').val(),
                massC = $(this).find('.mC').val(),
                massD = $(this).find('.mD').val(),
                $resultInput = $(this).find('.result')

            if (massA !== '' && massB !== '' && massC !== '' && massD !== '') {
                let result = runoff(massA, massB, massC, massD)

                if ( result !== false ) {
                    result = round(result, 3)
                    $resultInput.val(result)

                    arrResult.push(result)
                } else {
                    $resultInput.val('')
                }
            }
        })

        let aver = average(arrResult)

        if ( aver !== false ) {
            $averSMA.val( round(aver, 3) )
        } else {
            $averSMA.val( '' )
        }
    })

    $('.change-trigger-rnf-2').on('input', function () {
        let $definitionContainer = $('#runoff_formShMA').find('.definition')
        let arrResult = []

        $definitionContainer.each(function () {
            let massA = $(this).find('.mA').val(),
                massB = $(this).find('.mB').val(),
                massC = $(this).find('.mC').val(),
                $resultInput = $(this).find('.result')

            if (massA !== '' && massB !== '' && massC !== '') {
                let result = runoffShma(massA, massB, massC)

                if ( result !== false ) {
                    result = round(result, 2)
                    $resultInput.val(result)

                    arrResult.push(result)
                } else {
                    $resultInput.val('')
                }
            }
        })

        let aver = average(arrResult)

        if ( aver !== false ) {
            $averShMA.val( round(aver, 2) )
        } else {
            $averShMA.val( '' )
        }
    })
})