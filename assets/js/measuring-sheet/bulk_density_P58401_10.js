/**
 * Объемная плотность
 * @param massA - масса сухого
 * @param massB - масса после воды
 * @param massC - масса в воде
 * @returns {boolean|number}
 */
function density (massA, massB, massC) {
    let p = 0.997 // плотность воды

    if ( massA > 0.0 && massB > 0.0 && massC > 0.0 && (massB - massC) !== 0 ) {
        return massA / (massB - massC) * p
    } else {
        return false
    }
}

$(function () {
    $('.change-trigger-bd').on('input', function () {
        let massA1 = $('#formEmassA1').val(),
            massB1 = $('#formEmassB1').val(),
            massC1 = $('#formEmassC1').val(),
            massA2 = $('#formEmassA2').val(),
            massB2 = $('#formEmassB2').val(),
            massC2 = $('#formEmassC2').val(),
            massA3 = $('#formEmassA3').val(),
            massB3 = $('#formEmassB3').val(),
            massC3 = $('#formEmassC3').val()

        let $density1 = $('#formEdensity1'),
            $density2 = $('#formEdensity2'),
            $density3 = $('#formEdensity3'),
            $average  = $('#formEaverage'),
            $difference  = $('#formEdifference')

        let density1 = density(massA1, massB1, massC1)
        let density2 = density(massA2, massB2, massC2)
        let density3 = density(massA3, massB3, massC3)

        if ( density1 !== false ) {
            density1 = round(density1, 3)
            $density1.val( density1 )
        } else {
            $density1.val('')
        }
        if ( density2 !== false ) {
            density2 = round(density2, 3)
            $density2.val( density2 )
        } else {
            $density2.val('')
        }
        if ( density3 !== false ) {
            density3 = round(density3, 3)
            $density3.val( density3 )
        } else {
            $density3.val('')
        }

        if ( density1 && density2 && density3 ) {
            let resultAvg = round(average([density1, density2, density3]), 3)
            let max = Math.max(density1, density2, density3),
                min = Math.min(density1, density2, density3)
            let resultDif = round(Math.abs(max - min), 3)

            $average.val( resultAvg )
            $('#g1').val(resultAvg)
            $('#pmz_bulk_density').val(resultAvg)
            $('#pkz_bulk_density').val(resultAvg)
            $difference.val( resultDif )

            if ( resultDif > 0.010 ) {
                // let msg = getErrorMessage('Расхождение между полученными значениями не должно превышать 0.010 г/см<sup>3</sup>')
                $(this).parents('.panel-body').prepend(msg)
            }
        } else {
            $average.val('')
            $difference.val('')
        }
    })
})