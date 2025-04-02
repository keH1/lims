$(function ($) {
    const $body = $('#workarea-content')

    $('.delete_file').click(function () {
        $(this).parents('.input-group').find('input').val('')
        $(this).parents('.input-group').find('input').attr('placeholder', 'Файл удалится после сохранения')
    })

    $('.select2').select2({
        theme: 'bootstrap-5',
        // templateResult: select2FormatState,
        // templateSelection: select2FormatState,
    })

    $('#is_return_check').change(function () {
        if ( $(this).prop('checked') ) {
            $('#is_new_check').prop('checked', false)
            $('#place-moving-block').hide()
            $('#place-moving-block').find('input').val('Возвращено')
        } else {
            $('#place-moving-block').find('input').val('')
            $('#place-moving-block').show()
        }
    })

    $('#is_new_check').change(function () {
        if ( $(this).prop('checked') ) {
            $('#is_return_check').prop('checked', false)
            $('#place-moving-block').hide()
            $('#place-moving-block').find('input').val('Куплено')
        } else {
            $('#place-moving-block').find('input').val('')
            $('#place-moving-block').show()
        }
    })

    $('#select-ident').change(function () {
        if ( $(this).val() === 'IO' ) {
            $('#certified-block-values').show()
        } else {
            $('#certified-block-values').find('input').val('')
            $('#certified-block-values').hide()
        }
    })

    $('.add-inter-oborud').click(function () {
        const $block = $('.head-inter-oborud').last()
        let optionEquipment = ''

        const url = window.location.pathname,
              urlParts = url.split('/'),
              excludeId = urlParts[urlParts.length - 1],
              isNewEquipment = url.includes('/new/')

        $.ajax({
            method: 'POST',
            url: '/ulab/Oborud/getListEquipmentAjax',
            dataType: 'json',
            success: function (equipmentList) {
                if (!isNewEquipment) {
                    const isValidId = /^\d+$/.test(excludeId),
                          isIdInList = equipmentList.some(equipment => equipment.ID == excludeId)
    
                    if (!isValidId || !isIdInList) {
                        return
                    }
                }

                equipmentList.forEach(function (equipment) {
                    if (equipment.ID != excludeId) { 
                        optionEquipment += `
                            <option value="${equipment.ID}">
                                ${equipment.view_name}
                            </option>`
                    }
                })

                $block.after(`
                    <div class="form-group row head-inter-oborud border-bottom pb-3">
                        <label class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <select class="form-control select2 inter-equipment" name="inter[]">
                                    <option value="">Не выбрано</option>
                                    ${optionEquipment}
                                </select>
                                <a class="btn btn-outline-secondary disabled"  title="Перейти в оборудование">
                                    <i class="fa-solid fa-right-to-bracket"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-danger btn-square delete-inter-oborud" title="Отвязать оборудование">
                                <i class="fa-solid fa-minus icon-fix"></i>
                            </button>
                        </div>
                    </div>
                `)

                $('.select2').select2({
                    theme: 'bootstrap-5'
                })
            }
        })
    })

    $body.on('change', '.inter-equipment', function () {
        const $select = $(this),
              selectedValue = $select.val(),
              $link = $select.closest('.input-group').find('a.btn-outline-secondary')

        if (selectedValue) {
            $link.removeClass('disabled')
            $link.attr('href', `/ulab/oborud/edit/${selectedValue}`)
        } else {
            $link.addClass('disabled')
            $link.removeAttr('href')
        }
    })

    $body.on('click', '.delete-inter-oborud', function () {
        $(this).closest('.head-inter-oborud').remove()
    })

    $body.on('click', '.delete-precision', function () {
        $(this).parents('.precision_table--block').remove()
    })

    $body.on('click', '.add-precision', function () {

        let num = $('.precision_table--block').map(function() {
            return $(this).data('number-row');
        }).get();

        let countRow = Math.max.apply(Math, num) + 1;

        $('.precision_table--container').append(
            `<tr class="precision_table--block" data-number-row="${countRow}">
                <td>
                    <button type="button" class="btn btn-danger btn-square delete-precision" title="Удалить наименование показателя/характеристики">
                        <i class="fa-solid fa-minus icon-fix"></i>
                    </button>
                </td>
                <td>
                    <input type="text" class="form-control" name="precision_table[${countRow}][name]">
                </td>
                <td>
                    <input type="text" class="form-control" name="precision_table[${countRow}][unit1]">
                </td>
                <td class="precision_table--range-container">
                    <div class="input-group precision_table--range-block" data-subrow_number="0">
                        <span class="input-group-text">от</span>
                        <input type="text" class="form-control" name="precision_table[${countRow}][ot][]">
                        <span class="input-group-text">до</span>
                        <input type="text" class="form-control" name="precision_table[${countRow}][do][]">
                    </div>
                </td>
                <td>
                    <div class="input-group precision_table--pg-block">
                        <input type="text" class="form-control" name="precision_table[${countRow}][pg][]">
                    </div>
                </td>
                <td>
                    <div class="input-group precision_table--unit2-block">
                        <input type="text" class="form-control" name="precision_table[${countRow}][unit2][]">
                    </div>
                </td>
                <td>
                    <div class="precision_table--btn-block">
                        <button type="button" class="btn btn-success btn-square add-range" title="Добавить диапазон измерения">
                            <i class="fa-solid fa-plus icon-fix"></i>
                        </button>
                    </div>
                </td>
            </tr> `
        )
    })

    $body.on('click', '.delete-range', function () {
        let subRowStr = $(this).data('subrow')
        $(`.subrow_${subRowStr}`).remove()
    })

    $body.on('click', '.add-range', function () {
        let $parent = $(this).parents('.precision_table--block')
        let i = $parent.data('number-row')

        let num = $parent.find('.precision_table--range-block').map(function() {
            return $(this).data('subrow_number');
        }).get();

        let maxCount = Math.max.apply(Math, num) + 1

        $parent.find('.precision_table--range-container').append(
            `<div class="input-group precision_table--range-block pt-2 added_row_${i} subrow_${i}_${maxCount}" data-subrow_number="${maxCount}">
                <span class="input-group-text">от</span>
                <input type="text" class="form-control" name="precision_table[${i}][ot][]">
                <span class="input-group-text">до</span>
                <input type="text" class="form-control" name="precision_table[${i}][do][]">
            </div>`
        )

        $parent.find('.precision_table--pg-block:last-child').after(
            `<div class="precision_table--pg-block pt-2 added_row_${i} subrow_${i}_${maxCount}">
                <input type="text" class="form-control" name="precision_table[${i}][pg][]" value="">
            </div>`
        )

        $parent.find('.precision_table--unit2-block:last-child').after(
            `<div class="precision_table--unit2-block pt-2 added_row_${i} subrow_${i}_${maxCount}">
                <input type="text" class="form-control" name="precision_table[${i}][unit2][]" value="">
            </div>`
        )

        $parent.find('.precision_table--btn-block:last-child').after(
            `<div class="precision_table--btn-block pt-2 added_row_${i} subrow_${i}_${maxCount}">
                <button type="button" class="btn btn-danger btn-square delete-range" data-number="${i}" data-subrow="${i}_${maxCount}" title="Удалить диапазон измерения">
                    <i class="fa-solid fa-minus icon-fix"></i>
                </button>
            </div>`
        )
    })

    $('#add-certificate-modal-form')
        .add('#add-moving-modal-form')
        .add('#long-storage-modal-form')
        .add('#decommissioned-modal-form')
        .on('submit', handleModalFormSubmit)

    function handleModalFormSubmit(event) {
        event.preventDefault()
        const $form = $(this),
                formId = $form.attr('id'),
                formData = new FormData(this)
    
        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    $.magnificPopup.close()
    
                    const $formGroup = $(`a[href="#${formId}"]`).closest('.form-group')
                    let html = ''
    
                    switch (formId) {
                        case 'add-certificate-modal-form':
                            const $lastDashedLine = $('#certificate-block .line-dashed').last()
                            html = addNewCertificateFields(response.data)
                            $lastDashedLine.after(html)
                            break
                        case 'add-moving-modal-form':
                            $('.moving-place').val(response.data.place)
    
                            const $selectAssigned = $('.moving-assigned'),
                                    $selectAssignedGet = $('.moving-assigned-get')
    
                            if ($selectAssigned.length && response.data.responsible_user_id) {
                                $selectAssigned.val(response.data.responsible_user_id).trigger('change')
                            }
    
                            if ($selectAssignedGet.length && response.data.receiver_user_id) {
                                $selectAssignedGet.val(response.data.receiver_user_id).trigger('change')
                            }
                            break
                        case 'long-storage-modal-form':
                            $formGroup.empty()
                            html = `
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">На длительном хранении</label>
                                    <div class="col-sm-8 pt-2">
                                        <input type="checkbox" name="oborud[LONG_STORAGE]" class="form-check-input" value="1" checked="">
                                    </div>
                                    <div class="col-sm-2"></div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Дата постановки на длительное хранение</label>
                                    <div class="col-sm-8">
                                        <input type="date" name="oborud[LONG_STORAGE_DATE]"
                                               class="form-control" value="${response.data.LONG_STORAGE_DATE}">
                                    </div>
                                    <div class="col-sm-2"></div>
                                </div>`
                            $formGroup.append(html)
                            break
                        case 'decommissioned-modal-form':
                            $formGroup.empty()
                            html = `
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Основание для списания</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="${response.data.SPISANIE}" readonly>
                                    </div>
                                    <div class="col-sm-2"></div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Дата списания</label>
                                    <div class="col-sm-8">
                                        <input type="date" class="form-control" value="${response.data.DATE_SP}" readonly>
                                    </div>
                                    <div class="col-sm-2"></div>
                                </div>`
                            $formGroup.append(html)
                            break
                    }
                }
            },
            error: function(xhr, status, error) {
                alert('Произошла ошибка: ' + error)
            }
        })
    }

    function addNewCertificateFields(data) {
        let html = `
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">
                    Актуальный документ
                </label>
                <div class="col-sm-8 pt-2">
                    <input type="checkbox" name="certificate[${data.id}][is_actual]"
                           class="form-check-input" value="1"
                           ${data.is_actual ? 'checked' : ''}
                    >
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">
                    Дата документа
                </label>
                <div class="col-sm-8">
                    <input type="date" name="certificate[${data.id}][date_start]"
                           class="form-control" value="${data.date_start ?? ''}"
                    >
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">
                    Срок действия
                </label>
                <div class="col-sm-8">
                    <input type="date" name="certificate[${data.id}][date_end]"
                           class="form-control" value="${data.date_end ?? ''}"
                    >
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">
                    Номер документа
                </label>
                <div class="col-sm-8">
                    <input type="text" name="certificate[${data.id}][name]"
                           class="form-control" value="${data.name ?? ''}"
                    >
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">
                    Файл
                </label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <input type="file" name="certificate[${data.id}]" class="form-control" value="">
                        <input type="text" name="certificate[${data.id}][file]"
                               class="form-control" placeholder="Нет сохраненного файла"
                               value="${data.file ?? ''}" readonly
                        >
                        <a class="btn btn-outline-secondary btn-square-2 btn-icon"
                           title="Скачать/Открыть"
                           href="/file_oborud/${data.oborud_id}/${data.file}"
                           download="/file_oborud/${data.oborud_id}/${data.file}"
                        >
                            <i class="fa-regular fa-file-lines"></i>
                        </a>
                        <a class="btn btn-outline-danger btn-square btn-icon delete_file"
                           style="border-color: #ced4da;"title="Удалить">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    </div>
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">
                    Ссылка на ФГИС Аршин
                </label>
                <div class="col-sm-8">
                    <input type="text" name="certificate[${data.id}][link_fgis]"
                           maxlength="255" class="form-control" value="${data.link_fgis ?? ''}"
                    >
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">
                    Аттестованные значения
                </label>
                <div class="col-sm-8">
                    <input type="text" name="certificate[${data.id}][certified_values]"
                           maxlength="255" class="form-control" value="${data.certified_values ?? ''}">
                </div>
                <div class="col-sm-2"></div>
            </div>
            <div class="line-dashed"></div>`

        return html
    }

    const $firstSelect = $('.equipment-assigned')
    const $secondSelect = $('.add-equipment-assigned')
    const removedOptions = {
        first: {},
        second: {}
    }
   
    function removeOption(value, $targetSelect, storageKey) {
        if (!value) 
            return
        
        const $option = $targetSelect.find(`option[value="${value}"]`)
        if ($option.length) {
            removedOptions[storageKey][value] = {
                text: $option.text(),
                index: $option.index()
            }
            $option.remove()
        }
    }
    
    function restoreOption(value, storageKey) {
        if (!value) 
            return
        
        const optionData = removedOptions[storageKey][value]
        if (optionData) {
            const $targetSelect = storageKey === 'first' ? $firstSelect : $secondSelect
            const $newOption = $(`<option value="${value}">${optionData.text}</option>`)
            
            const $options = $targetSelect.find('option')
            if (optionData.index < $options.length)
                $options.eq(optionData.index).before($newOption)
            else
                $targetSelect.append($newOption)
            
            delete removedOptions[storageKey][value]
        }
    }
    
    function restoreAll(storageKey) {
        Object.keys(removedOptions[storageKey]).forEach(value => {
            restoreOption(value, storageKey)
        })
    }
    
    function initSelects() {
        const firstVal = $firstSelect.val()
        if (firstVal)
            removeOption(firstVal, $secondSelect, 'second')
        
        const secondVal = $secondSelect.val()
        if (secondVal)
            removeOption(secondVal, $firstSelect, 'first')
        
        $firstSelect.data('prev-val', firstVal)
        $secondSelect.data('prev-val', secondVal)
    }
    
    initSelects()
    
    $firstSelect.on('change', function() {
        const prevVal = $(this).data('prev-val')
        const newVal = $(this).val()
        
        if (prevVal && prevVal !== newVal)
            restoreOption(prevVal, 'second')
        
        if (newVal)
            removeOption(newVal, $secondSelect, 'second')
        else
            restoreAll('second')
        
        $(this).data('prev-val', newVal)
        $secondSelect.trigger('change.select2')
    })
    
    $secondSelect.on('change', function() {
        const prevVal = $(this).data('prev-val')
        const newVal = $(this).val()
        
        if (prevVal && prevVal !== newVal)
            restoreOption(prevVal, 'first')
        
        if (newVal)
            removeOption(newVal, $firstSelect, 'first')
        else
            restoreAll('first')
        
        $(this).data('prev-val', newVal)
        $firstSelect.trigger('change.select2')
    })
})