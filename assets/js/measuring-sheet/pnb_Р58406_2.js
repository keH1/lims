$(function () {
    $('.change-trigger-pnb').on('input', function () {
        let pmz = $('#pmz').val(),
            pa = $('#pa').val()

        let $pnbResult = $('#pnb_result')

        $pnbResult.val('')


        if ( pmz != 0 ) {
            $pnbResult.val( round(100 * (pmz - pa) / pmz, 1) )
        }
    })
})