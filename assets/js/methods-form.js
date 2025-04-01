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

    initDynamicRangeBlock()
    initSimpleRanges()
    $('.uncertainty-group').each(function() {
        initUncertainty($(this))
    })


    $('form').on('submit', function(e) {
        $('.range-from, .range-to, .dynamic-from, .dynamic-to, .uncertainty-from, .uncertainty-to').trigger('input')

        if ($('.is-invalid').length > 0) {
            e.preventDefault()
            scrollToFirstError()
        }
    })

    // Скролл к первой ошибке
    function scrollToFirstError() {
        const $firstError = $('.is-invalid').first()
        if ($firstError.length) {
            $('html, body').animate({
                    scrollTop: $firstError.offset().top - 100
                },
                500,
                function () {
                    $firstError.focus()
                })
        }
    }

    function showError($element, message) {
        $element.addClass('is-invalid')
        $element.tooltip({title: message, placement: 'top'}).tooltip('show')
    }

    function clearError($element) {
        $element.removeClass('is-invalid')
        $element.tooltip('dispose')
    }

    // Для диапазона определения
    function initDynamicRangeBlock() {
        const $block = $('#definition-range-block')
        const $from = $block.find('input[name="form[definition_range_1]"]').addClass('dynamic-from')
        const $to = $block.find('input[name="form[definition_range_2]"]').addClass('dynamic-to')
        const $radios = $block.find('input[name="form[definition_range_type]"]')

        const validate = () => {
            const rangeType = $radios.filter(':checked').val()

            clearError($from)
            clearError($to)

            const fromVal = $from.val()?.toString().trim() || ''
            const toVal = $to.val()?.toString().trim() || ''

            if (!fromVal || !toVal) {
                return // Не проверяем если хотя бы одно поле пустое
            }

            const numFrom = parseFloat(fromVal)
            const numTo = parseFloat(toVal)

            if (isNaN(numFrom)) {
                showError($from, 'Некорректное значение "от"')
                return
            }
            if (isNaN(numTo)) {
                showError($to, 'Некорректное значение "до"')
                return
            }

            let error = false
            if (rangeType === '1' && numTo < numFrom) {
                error = 'Для внутреннего диапазона: "до" ≥ "от"'
            } else if (rangeType === '2' && numTo < numFrom) {
                error = 'Для внешнего диапазона: "до" ≤ "от"'
            } else if (rangeType === '3' && numTo < numFrom) { // "Не нормируется"
                error = 'Не корректный диапазон'
            } else if (numTo < numFrom) {
                error = 'Неизвестный тип диапазона'
            }

            if (error) {
                showError($to, error)
            } else {
                clearError($to)
            }
        }

        $radios.add($from).add($to).on('change input', validate)
        validate() // При загрузки страницы
    }

    // Для температуры, влажности и давления
    function initSimpleRanges() {
        $('.condition-group').each(function() {
            const $group = $(this)
            const $from = $group.find('.range-from')
            const $to = $group.find('.range-to')

            const validate = () => {
                clearError($from)
                clearError($to)

                const fromVal = $from.val()?.toString().trim() || ''
                const toVal = $to.val()?.toString().trim() || ''

                if (!fromVal || !toVal) {
                    return // Не проверяем если хотя бы одно поле пустое
                }

                const numFrom = parseFloat(fromVal)
                const numTo = parseFloat(toVal)

                if (isNaN(numFrom)) {
                    showError($from, 'Введите числовое значение')
                    return
                }
                if (isNaN(numTo)) {
                    showError($to, 'Введите числовое значение')
                    return
                }

                if (numFrom > numTo) {
                    showError($to, 'Значение "до" должно быть больше "от"')
                }
            }

            $from.add($to).on('input', validate)
            validate() // При загрузки страницы
        })
    }

    // Для блоков неопределенности
    function initUncertainty($group) {
        const $from = $group.find('.uncertainty-from')
        const $to = $group.find('.uncertainty-to')

        const validate = () => {
            clearError($from)
            clearError($to)

            const fromVal = $from.val()?.toString().trim() || ''
            const toVal = $to.val()?.toString().trim() || ''

            if (!fromVal || !toVal) {
                return // Не проверяем если хотя бы одно поле пустое
            }

            const numFrom = parseFloat(fromVal)
            const numTo = parseFloat(toVal)


            if (isNaN(numFrom)) {
                showError($from, 'Введите числовое значение')
                return
            }
            if (isNaN(numTo)) {
                showError($to, 'Введите числовое значение')
                return
            }

            if (numFrom > numTo) {
                showError($to, 'Значение "до" не может быть меньше "от"')
            }
        };

        $from.add($to).on('input', validate)
        validate() // При загрузки страницы
    }

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
                    <div class="input-group uncertainty-group">
                        <span class="input-group-text">от</span>
                        <input
                                type="number"
                                step="0.001"
                                class="form-control uncertainty-from"
                                name="uncertainty[${countUncertainty}][uncertainty_1]"
                        >
                        <span class="input-group-text">до</span>
                        <input
                                type="number"
                                step="0.001"
                                class="form-control uncertainty-to"
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
        initUncertainty($('.uncertainty-group:last'))
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
