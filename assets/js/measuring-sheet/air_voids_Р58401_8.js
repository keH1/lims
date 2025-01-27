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

$(function () {
    let $form = $('.formA')

    $form.on('input', '.change-trigger-av', function () {
        let $parent = $(this).closest('.measurement-wrapper')
        let g1 = $parent.find('.g1').val(),
            g2 = $parent.find('.g2').val()

        let $result = $parent.find('.result')

        let result = airVoids(g1, g2)

        if ( result !== false ) {
            result = round(result, 1)
            $result.val( result )
            $('.form_pnb .pa').val(result)
            $('#formVoidVolA').val( result )
        } else {
            $result.val('')
        }
    })
})