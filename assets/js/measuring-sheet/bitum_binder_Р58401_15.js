$(function () {
    $('#bb-select-method').change(function () {
        let $parent = $('#formBituminousBinder')
        let val = $(this).val()

        $parent.find('.block-method').hide()

        $parent.find(`.block-${val}`).show()
    })

    // Содержание битумного вяжущего
    $('.change-trigger-bb').input(function () {
        let $averR   = $('#result-bb'),
            $diff    = $('#diff-bb'),
            method   = $('#bb-select-method option:selected').val()

        $averR.val('')
        $diff.val('')

        let arrR = []
        let averR = false

        // метод выжигания
        if ( method === 'burning' ) {
            let $burningG = $('.bb-burning-g'),
                $burningG1 = $('.bb-burning-g1'),
                $burningG2 = $('.bb-burning-g2'),
                $burningR = $('.bb-burning-r'),
                $burningAverR = $('#result-bb-burning'),
                $burningDiff = $('#diff-bb-burning')

            $burningR.val('')
            $burningAverR.val('')
            $burningDiff.val('')

            for (let i = 0; i < 2; i++) {
                let g = parseFloat($($burningG.get(i)).val()),
                    g1 = parseFloat($($burningG1.get(i)).val()),
                    g2 = parseFloat($($burningG2.get(i)).val())

                if (isNaN(g) || isNaN(g1) || isNaN(g2) || g2 - g == 0) {
                    continue
                }

                let r = round((g1 - g2) / (g2 - g) * 100, 2)
                $($burningR.get(i)).val(r)
                arrR.push(r)
            }

            averR = average(arrR)
            // if (averR !== false) {
            //     $averR.val(round(averR, 2))
            // }
            // if (arrR.length === 2) {
            //     $diff.val(round(Math.abs(arrR[0] - arrR[1]), 2))
            // }
        } else {
            // метод экстрагирования
            let $extractionM1       = $('.bb-extraction-m1'),
                $extractionM2       = $('.bb-extraction-m2'),
                $extractionM3       = $('.bb-extraction-m3'),
                $extractionM4       = $('.bb-extraction-m4'),
                $extractionM5       = $('.bb-extraction-m5'),
                $extractionR        = $('.bb-extraction-r'),
                $extractionAverR    = $('#result-bb-extraction')

            $extractionR.val('')
            $extractionAverR.val('')

            for (let i = 0; i < 2; i++) {
                let m1 = parseFloat($($extractionM1.get(i)).val()),
                    m2 = parseFloat($($extractionM2.get(i)).val()),
                    m3 = parseFloat($($extractionM3.get(i)).val()),
                    m4 = parseFloat($($extractionM4.get(i)).val()),
                    m5 = parseFloat($($extractionM5.get(i)).val())

                if (isNaN(m1) || isNaN(m2) || isNaN(m3) || isNaN(m4) || isNaN(m5)) {
                    continue
                }

                let z = (m4 + m5) - (m1 + m2)

                if ( z == 0 ) {
                    continue
                }

                let r = round( ((m3 + m2) - (m4 + m5)) / z * 100, 2)
                $($extractionR.get(i)).val(r)
                arrR.push(r)
            }

            averR = average(arrR)
        }


        if ( averR !== false ) {
            $averR.val(round(averR, 2))
        }
        if ( arrR.length === 2 ) {
            $diff.val(round(Math.abs(arrR[0] - arrR[1]), 2))
        }
    })
})