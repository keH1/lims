$(function ($) {
    const body = $('body')

    let ugtpId = $('.wrapper-bitumen-flash-point').find('#ugtp_id').val()

    //Температура вспышки
    if($(`[name='form_data[${ugtpId}][form][flash_point_33133][actual_barometric_pressure]']`).val() > 101.3 || $(`[name='form_data[${ugtpId}][form][flash_point_33133][actual_barometric_pressure]']`).val() == ""){
        $('.hide-list-data').css('display', 'none');
        $('#flashPoint').css('display', 'none');
        $(`[name='form_data[${ugtpId}][form][flash_point_33133][flash_point_trial]']`).val("");
    }
    else{
        $('.hide-list-data').css('display', 'table');
        $('.t_block').css('display', 'none');
    }

    let p = Number($(`[name='form_data[${ugtpId}][form][flash_point_33133][actual_barometric_pressure]']`).val());

    $(`[name='form_data[${ugtpId}][form][flash_point_33133][actual_barometric_pressure]']`).change(function(){
        p = Number($(`[name='form_data[${ugtpId}][form][flash_point_33133][actual_barometric_pressure]']`).val());

        if($(this).val() < 101.3 && $(this).val() != ""){
            $('.hide-list-data').css('display', 'table');
            $('.t_block').css('display', 'none');
            $('#flashPoint').css('display', 'inline-block');
        }
        else{
            $('.hide-list-data').css('display', 'none');
            $('.t_block').css('display', 'block');
            $('#flashPoint').css('display', 'none');
        }
    });

    let Tb_ariphmetic;
    body.on("click", "#flashPoint", function () {

        function calcT(trial){

            let T_f;

            T_f = Number($(`[name='form_data[${ugtpId}][form][flash_point_33133][actual_flash_point][${trial}]']`).val());

            return T_f + (0.25 * (101.3 - p));
        }

        Tb_1 = calcT(0);
        Tb_2 = calcT(1);

        Tb_ariphmetic = Math.round((Tb_1 + Tb_2) / 2);

        console.log(ugtpId)

        $(`[name='form_data[${ugtpId}][form][flash_point_33133][flash_point_trial]']`).val(Tb_ariphmetic);
    });
})