/**
 *
 * @param arr
 * @returns {boolean|number[]}
 */
function getMinDiffAver(arr) {
    if (arr.length !== 6) {
        return false
    }

    let mask = [
        [0,1,2,3,4,5],
        [0,1,3,2,4,5],
        [0,1,4,2,3,5],
        [0,1,5,2,3,4],
        [0,2,3,1,4,5],
        [0,2,4,1,3,5],
        [0,2,5,1,3,4],
        [0,3,4,1,2,5],
        [0,3,5,1,2,4],
        [0,4,5,1,2,3]
    ]

    let lastMin = -1,
        lastKey = 0

    mask.forEach(function (item, k) {
        let arr1 = [],
            arr2 = []

        for (let i = 0, j = 3; i < 3; i++, j++) {
            arr1.push(arr[item[i]])
            arr2.push(arr[item[j]])
        }

        let aver1 = average(arr1),
            aver2 = average(arr2)

        if ( (aver1 === false || aver2 === false) && lastMin === -1 ) {
            return false
        }

        let diff = round(Math.abs(aver1 - aver2), 5)

        // если разница 0, то дальше считать нет смысла, меньше уже не будет
        if ( diff == 0 ) {
            return mask[k]
        }

        if ( lastMin === -1 || lastMin > diff ) {
            lastMin = diff
            lastKey = k
        }
    })

    return mask[lastKey]
}

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

/**
 * Количество воздушных пустот
 * @param g1 - Объемная плотность
 * @param g2 - Максимальная плотность
 * @returns {boolean|number}
 */
function airVoids (g1, g2) {
    if ( g1 > 0.0 && g2 > 0.0 ) {
        return 100 - g1 * 100 / g2
    } else {
        return false
    }
}

/**
 * Объем воздушных пустот
 * @param p - кол-во воздушных пустот, %
 * @param e - масса на воздухе после воды - масса в воде = E
 * @returns {number}
 */
function airVoidsVol (p, e) {
    return p * e / 100
}

/**
 * Объем поглощенной воды
 * @param massA - масса сухого
 * @param massB - масса после насыщения
 * @returns {boolean|number}
 */
function water (massA, massB) {
    let p = 0.997 // плотность воды

    if ( massA > 0.0 && massB > 0.0 ) {
        return (massB - massA) / p
    } else {
        return false
    }
}

/**
 * Степень насыщения
 * @param j - объем поглощенной воды
 * @param v - объемвоздушных пустот
 * @returns {boolean|number}
 */
function saturation (j, v) {
    if ( v > 0.0 ) {
        return 100 * j / v
    } else {
        return false
    }
}

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

$(function () {
    $('.change-trigger-wrs-2').on('input', function () {
        let $waterT        = $('.water-t'),
            $waterD        = $('.water-d'),
            $waterP        = $('.water-p'),
            $waterAverS1   = $('.water-aver_s1'),
            $waterAverS2   = $('.water-aver_s2'),
            $waterResult   = $('#water-resist-result')

        $waterAverS1.val('')
        $waterAverS2.val('')
        $waterResult.val('')

        for (let k = 1; k <= 2; k++) {

            let $waterS = $(`.water-s${k}`),
                $waterAverS  = $(`.water-aver_s${k}`)

            let sArray = [];

            for (let i = 0; i < 3; i++) {
                let j = (k - 1) * 3 + i
                let t = parseFloat($($waterT.get(j)).val()),
                    d = parseFloat($($waterD.get(j)).val()),
                    p = parseFloat($($waterP.get(j)).val())

                if ( isNaN(t) || isNaN(d) || isNaN(p) ) {
                    continue
                }

                let s = waterResist(t, d, p)

                if ( s === false ) {
                    continue
                }

                s = round(s, 2)

                $($waterS.get(i)).val( s )

                sArray.push(s)
            }

            let averS = average(sArray)

            if ( averS !== false ) {
                $waterAverS.val(round(averS, 3))
            } else {
                $waterAverS.val('')
            }
        }

        let s1 = parseFloat($waterAverS1.val()),
            s2 = parseFloat($waterAverS2.val())

        if ( !isNaN(s1) && !isNaN(s2) && s1 !== 0 ) {
            $waterResult.val( round(s2 / s1, 2) )
        }
    })

    // Степень насыщения
    $('.change-trigger-wrs-1').on('input', function () {
        let $saturationA        = $('.saturation-a'),
            $saturationB        = $('.saturation-b'),
            $saturationC        = $('.saturation-c'),
            $saturationB1       = $('.saturation-b1'),
            $saturationE        = $('.saturation-e'),
            $saturationGmb      = $('.saturation-gmb'),
            $saturationPa       = $('.saturation-pa'),
            $saturationVa       = $('.saturation-va'),
            $saturationJ        = $('.saturation-j'),
            $saturationW        = $('.saturation-w'),
            $saturationMaxDen   = $('.saturation-maxden'),
            $saturationAverGmb  = $('.saturation-aver_gmb'),
            $saturationGroup    = $('.saturation-group'),
            $tdGroup            = $('.type-group')

        $saturationGmb.val('')
        $saturationPa.val('')
        $saturationVa.val('')
        $saturationJ.val('')
        $saturationW.val('')
        $saturationAverGmb.val('')

        let arrayPa = []

        let denArray = []

        for (let i = 0; i < 6; i++) {
            let a = parseFloat($($saturationA.get(i)).val()),
                b = parseFloat($($saturationB.get(i)).val()),
                c = parseFloat($($saturationC.get(i)).val()),
                b1 = parseFloat($($saturationB1.get(i)).val())

            if ( isNaN(a) || isNaN(b) || isNaN(c) ) {
                continue
            }

            let e = Math.abs(round(b - c, 2))

            $($saturationE.get(i)).val( e )

            let den = density(a, b, c)

            if ( den !== false ) {
                let gmb = Math.abs(round(den, 3))
                denArray.push(gmb)
                $($saturationGmb.get(i)).val( gmb )

                let maxDen = parseFloat($saturationMaxDen.val())

                if ( !isNaN(maxDen) && maxDen !== 0 ) {
                    let pa = round(airVoids(gmb, maxDen), 2)
                    arrayPa.push(pa)

                    $($saturationPa.get(i)).val( pa )

                    let va = round(airVoidsVol(pa, e), 2)

                    $($saturationVa.get(i)).val( va )

                    if ( !isNaN(b1) ) {
                        let j = water(a, b1)

                        $($saturationJ.get(i)).val( round(j, 2) )

                        let w = saturation(j , va)

                        $($saturationW.get(i)).val( round(w, 2) )
                    }
                }
            } else {
                $($saturationGmb.get(i)).val( '' )
            }
        }

        let mask = getMinDiffAver(arrayPa)
        if ( mask !== false ) {
            for (let i = 0, j = 3; i < 3; i++, j++) {
                $($tdGroup.get(mask[i])).text('A')
                $($tdGroup.get(mask[j])).text('B')
                $($saturationGroup.get(mask[i])).val('A')
                $($saturationGroup.get(mask[j])).val('B')
            }

        }
        console.log(mask)

        let averDen = average(denArray)

        if ( averDen !== false ) {
            $saturationAverGmb.val(round(averDen, 3))
        } else {
            $saturationAverGmb.val('')
        }
    })

    $('#formWaterResistCoeffCalculate').click(function () {
        let s2 = $('#wrcS2').val(),
            s1 = $('#wrcS1').val()

        $('#tsr').val('')

        if ( s2 != '' && s1 != '' && s1 != 0 ) {
            $('#tsr').val( round(parseFloat(s2) / parseFloat(s1)), 2 )
        }
    })
})
