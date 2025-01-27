/**
 *
 * @param t - толщина
 * @param d - диаметр
 * @param p - нагрузка
 * @returns {boolean|number}
 */

function roundPlus(x, n) {
    if(isNaN(x) || isNaN(n)) return false;
    let m = Math.pow(10, n);
    return Math.round(x * m) / m;
}

function waterResist(t, d, p) {
    if ( d == 0 || t == 0 ) {
        return false
    }

    return 2000 * p / (t * d * Math.PI)
}

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

function pmz_pkz(gmb, ps, gsb) {
    if ( gsb == 0 ) { return false }

    return 100 - (gmb * ps / gsb)
}

function pmz(gmb, ps, gsb) {
    if ( gsb == 0 ) { return false }

    return 100 * (1 - (gmb - (ps / 100)) / gsb)
}

$(function () {


    function getMineralPartTrueDensity(q, p) {
        let sum = 0
        let sumP = 0

        if ((typeof q !== 'object' && $.isEmptyObject(q)) || (typeof p !== 'object' && $.isEmptyObject(p))) {
            return false
        }

        q.each(function (index, item) {
            let valQ = $(item).val()
            let valP = $(p[index]).val()

            if (valQ === '' || !valP) {
                return true
            }

            sum += Number(valQ)
            sumP += Number(valQ) / Number(valP)
        })

        if (sum === 0) {
            return false
        }

        $('#pmz_amount_mineral').val(round(sum, 2))

        return sum / sumP
    }

    $('.change-trigger-pmz').on('input', function() {
        console.log(23)
        const mineralTrueDensityWrapper = $('.mineral-true-density-wrapper'),
            inputMassFractionMineralMaterials = mineralTrueDensityWrapper.find('.mass-fraction-mineral-materials'),
            inputMineralMaterialsTrueDensity = mineralTrueDensityWrapper.find('.mineral-materials-true-density'),
            inputMineralPartTrueDensity = mineralTrueDensityWrapper.find('.mineral-part-true-density')

        //mineralTrueDensityWrapper.find(".messages").remove()

        let arrMassFractionMineralMaterials = $.map(inputMassFractionMineralMaterials, function (elem) {
            if ($(elem).val() !== '' && $(elem).val() !== null) {
                return +$(elem).val()
            }
        })

        let sumMassFractionMineralMaterials = roundPlus(arrMassFractionMineralMaterials.reduce((a, b) => (a + b)), 3)

        if (sumMassFractionMineralMaterials === 100 || sumMassFractionMineralMaterials > 100) {
            if(sumMassFractionMineralMaterials === 100) {
                // let messageError = "Внимание! Массовая доля минеральных заполнителей равна 100%"
                // alert(messageError);
            }
            if(sumMassFractionMineralMaterials > 100) {
                // let messageError = "Внимание! Массовая доля минеральных заполнителей больше 100%"
                // alert(messageError);
            }
            // let messageErrorContent = getMessageErrorContent(messageError)
            // mineralTrueDensityWrapper.prepend(messageErrorContent)
            inputMineralPartTrueDensity.val('')
            return false
        }

        let mineralPartTrueDensity = getMineralPartTrueDensity(inputMassFractionMineralMaterials, inputMineralMaterialsTrueDensity)

        inputMineralPartTrueDensity.val(round(mineralPartTrueDensity, 2).toFixed(2))

        $('#pmz_total_bulk_density').val(round(mineralPartTrueDensity, 2).toFixed(2))

        let gmb = $('#pmz_bulk_density').val(),
            ps  = $('#pmz_amount_mineral').val(),
            gsb = $('#pmz_total_bulk_density').val(),
            gsb2 = $('#pkz_bulk_density').val(),
            pca = $('#pkz_amount_coarse').val(),
            gmm = $('#pkz_total_bulk_density').val()

        if(!gmb) {
            // alert("Введите значение для объемной плотности уплотненного образца");
            return false;
        }

        let $pmzResult = $('#pmz_result'),
            $pkzResult = $('#pkz_result')

        $pmzResult.val('')
        $pkzResult.val('')

        let pmzResult = pmz_pkz(gmb, ps, gsb),
            pkzResult = pmz_pkz(gsb2, pca, gmm)

        if ( pmzResult !== false ) {
            pmzResult = round(pmzResult, 2)
            $('#pmz').val(pmzResult)
            $pmzResult.val( pmzResult )
        }

        if ( pkzResult !== false ) {
            pkzResult = round(pkzResult, 2)
            $pkzResult.val( pkzResult )
        }
    })
})
