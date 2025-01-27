/**
 * Максимальная плотность
 * @param massA
 * @param massB
 * @param massC
 * @returns {boolean|number}
 */
function maximumDensity (massA, massB, massC) {
    let p = 0.997 // плотность воды

    if ( massA > 0.0 && massB > 0.0 && massC > 0.0 && (massA - (massB - massC)) !== 0 ) {
        return massA / (massA - (massB - massC)) * p
    } else {
        return false
    }
}


$(function () {
    $('#formBcalculate').on('click', function () {
        let massA1 = $('#formBmassA1').val(),
            massB1 = $('#formBmassB1').val(),
            massC1 = $('#formBmassC1').val(),
            massA2 = $('#formBmassA2').val(),
            massB2 = $('#formBmassB2').val(),
            massC2 = $('#formBmassC2').val()

        let $density1 = $('#formBdensity1'),
            $density2 = $('#formBdensity2'),
            $average  = $('#formBaverage'),
            $difference  = $('#formBdifference')

        let maxDensity1 = maximumDensity(massA1, massB1, massC1)
        let maxDensity2 = maximumDensity(massA2, massB2, massC2)

        if ( maxDensity1 !== false ) {
            maxDensity1 = round(maxDensity1, 3)
            $density1.val( maxDensity1 )
        } else {
            $density1.val('')
        }
        if ( maxDensity2 !== false ) {
            maxDensity2 = round(maxDensity2, 3)
            $density2.val( maxDensity2 )
        } else {
            $density2.val('')
        }

        if ( maxDensity1 && maxDensity2 ) {
            let resultAvg = round(average([maxDensity1, maxDensity2]), 3)
            let resultDif = round(Math.abs(maxDensity1 - maxDensity2), 3)
            $average.val( resultAvg )
            $('#g2').val( resultAvg )
            $difference.val( resultDif )

            if ( resultDif > 0.020 ) {
                // let msg = getErrorMessage('Расхождение между полученными значениями не должно превышать 0.020 г/см<sup>3</sup>')
                // $(this).parents('.panel-body').prepend(msg)
            }
        } else {
            $average.val('')
            $difference.val('')
        }
    })
})