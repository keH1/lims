$(function () {
    const $body = $('body');

    $('.select2').select2({
        theme: 'bootstrap-5',
        width: 'resolve',
    })

    $('.condition-group input[type=checkbox]').click(function () {
        $(this).parents('.condition-group').find('input[type=number]').prop('readonly', this.checked)
    })

    $('#r1').click(function () {
        $('.text-def-1').text('от')
        $('.text-def-2').text('до')
    })

    $('#r2').click(function () {
        $('.text-def-1').text('до')
        $('.text-def-2').text('от')
    })

    $('.is_range_check').change(function () {
        if ( $(this).prop('checked') ) {
            $('.range_text_elem').removeClass('d-none')
        } else {
            $('.range_text_elem').addClass('d-none')
        }
    })

    $body.on('input', '.usage-time', function(){
        let value = parseFloat($(this).val())
        if (!isNaN(value) && value < 0) {
            alert("Время использования не может быть отрицательным!")
            $(this).val(0)
        }
    })

    // $('#extended_field').click(function () {
    //     if ( this.checked ) {
    //         $('#in_field').prop('checked', true)
    //     }
    // })
    //
    // $('#in_field').click(function () {
    //     if ( !this.checked ) {
    //         $('#extended_field').prop('checked', false)
    //     }
    // })


    $('.add-result').click(function () {
        let html = ``
    })


    let countUncertainty = $('.uncertainty-block').length

    $('#add_uncertainty').click(function () {
        $('.uncertainty-block:last-child').after(
            `<div class="form-group row uncertainty-block">
                <label class="col-sm-2 col-form-label">
                </label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <span class="input-group-text">от</span>
                        <input
                                type="number"
                                step="0.001"
                                class="form-control"
                                name="uncertainty[${countUncertainty}][uncertainty_1]"
                        >
                        <span class="input-group-text">до</span>
                        <input
                                type="number"
                                step="0.001"
                                class="form-control"
                                name="uncertainty[${countUncertainty}][uncertainty_2]"
                        >
                        <span class="input-group-text">U(w)</span>
                        <input
                                type="number"
                                step="0.001"
                                class="form-control"
                                name="uncertainty[${countUncertainty}][uncertainty_3]"
                        >
                        <span class="input-group-text">Rл</span>
                        <input
                                type="number"
                                step="any"
                                class="form-control"
                                name="uncertainty[${countUncertainty}][Rl]"
                        >
                        <span class="input-group-text">r</span>
                        <input
                                type="number"
                                step="any"
                                class="form-control"
                                name="uncertainty[${countUncertainty}][r]"
                        >
                        <span class="input-group-text">Кт</span>
                        <input
                                type="number"
                                step="any"
                                class="form-control"
                                name="uncertainty[${countUncertainty}][Kt]"
                        >
                    </div>
                </div>
                <div class="col-sm-2">
                    <button
                            type="button"
                            class="btn btn-danger btn-square remove_uncertainty"
                            title="Удалить Неопределенность">
                        <i class="fa-solid fa-minus icon-fix"></i>
                    </button>
                </div>
            </div>`
        )

        countUncertainty++
    })

    let countOborud = $('.oborud-block').length

    $('#add_oborud').click(function () {
        let $optionList = $('#select-room').find('option:selected')
        let idList = []
        let html = '';

        $.each($optionList, function (i, item) {
            idList.push($(item).val())
        })

        if ( idList.length > 0 ) {
            $.ajax({
                url: "/ulab/gost/getOborudByRoomAjax/",
                data: {rooms: idList},
                dataType: "json",
                method: "POST",
                async: false,
                success: function (data) {
                    $.each(data, function (i, item) {
                        html += `<option value="${item.ID}">${item.OBJECT} | ${item.FACTORY_NUMBER} | ${item.REG_NUM}</option>`
                    })
                }
            })
        }


        $('.oborud-block:last-child').after(
            `<tr class="align-middle oborud-block">
                    <td>
                        <input
                                type="text"
                                class="form-control"
                                name="oborud[${countOborud}][gost]"
                                value=""
                        >
                    </td>
                    <td>
                        <select class="form-control select2 oborud-select" name="oborud[${countOborud}][id_oborud]">
                            <option value="">Выбрать оборудование</option>
                            <option value="0">Нет оборудования</option>
                            ${html}
                        </select>
                    </td>
                    <td class="link-place"></td>
                    <td class="ident-place"></td>
                    <td>
                        <input type="number" step="0.1" min="0" class="form-control usage-time" name="oborud[${countOborud}][usage_time]" value="">
                    </td>
                    <td>
                        <input
                                type="text"
                                class="form-control"
                                name="oborud[${countOborud}][comment]"
                                value=""
                        >
                    </td>
                    <td>
                        <button
                                type="button"
                                class="btn btn-danger btn-square remove_oborud"
                                title="Удалить оборутование">
                            <i class="fa-solid fa-minus icon-fix"></i>
                        </button>
                    </td>
                </tr>`
        )

        $('.oborud-block:last-child .select2').select2({
            theme: 'bootstrap-5',
            width: 'resolve',
        })

        countOborud++
    })

    $('body').on('change', '.oborud-select', function () {
        let id = $(this).find(":selected").val()
        let $identPlace = $(this).parents('.oborud-block').find('.ident-place')
        let $linkPlace = $(this).parents('.oborud-block').find('.link-place')

        $.ajax({
            url: "/ulab/gost/getOborudAjax/",
            data: {id: id},
            dataType: "json",
            method: "POST",
            success: function (data) {
                if ( data.IDENT !== undefined ) {
                    $identPlace.text(data.IDENT)
                    $linkPlace.html(`<a class="text-dark fs-4"  title="Перейти в оборудование" target="_blank" href="/ulab/oborud/edit/${data.ID}"><i class="fa-regular fa-clipboard"></i></a>`)
                } else {
                    $identPlace.text('')
                    $linkPlace.text('')
                }
            }
        })
    })

    $('body').on('click', '.remove_oborud', function () {
        $(this).parents('.oborud-block').remove()
    })

    $('body').on('click', '.remove_uncertainty', function () {
        $(this).parents('.uncertainty-block').remove()
    })

    $('#is_two_results').click(function () {
        if ( this.checked ) {
            $('#two_result_block').show('slow')
            $('#two_result_block input').prop('disabled', false)
        } else {
            $('#two_result_block').hide('slow')
            $('#two_result_block input').prop('disabled', true)
        }
    })

    $('#select-lab').change(function () {
        let $optionList = $('#select-lab').find('option:selected')
        let idList = []

        $.each($optionList, function (i, item) {
            idList.push($(item).val())
        })

        if ( idList.length > 0 ) {
            $.ajax({
                url: "/ulab/gost/getRoomByLabIdAjax/",
                data: {lab: idList},
                dataType: "json",
                method: "POST",
                success: function (data) {
                    let html = ''

                    $.each(data, function (i, item) {
                        if ( item.id < 100 ) {
                            html += `<option value="" disabled>${item.name}</option>`
                        } else {
                            html += `<option value="${item.id - 100}">${item.name}</option>`
                        }
                    })

                    $('#select-room ~ .select2-container').find('#select2-select-room-container').html('')
                    $('#select-room').html(html)
                }
            })

            // $.ajax({
            //     url: "/ulab/gost/getAssignedByLabIdAjax/",
            //     data: {"lab": idList},
            //     dataType: "json",
            //     method: "POST",
            //     success: function (data) {
            //         let html = ''
            //
            //         $.each(data, function (i, item) {
            //             if ( item.is_get === 1 ) {
            //                 html += `<option value="${item.ID}" selected>${item.LAST_NAME} ${item.NAME}</option>`
            //             } else {
            //                 html += `<option value="${item.ID}">${item.LAST_NAME} ${item.NAME}</option>`
            //             }
            //
            //         })
            //
            //         $('#select-assigned ~ .select2-container').find('#select2-select-assigned-container').html('')
            //         $('#select-assigned').html(html)
            //     }
            // })
        } else {
            $('#select-room').html('<option value="" disabled>Сначала выберите лаборатории</option>')
            // $('#select-assigned').html('<option value="" disabled>Сначала выберите лаборатории</option>')
        }
    })
})
