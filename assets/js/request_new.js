$(function ($) {
    const $body = $('body')
    // Новая или существующая заявка
    const isNewRequest = !$('input[name="id"]').length
    // Модальное окно для редактирования ячеек
    let currentEditCell = null
    window.labsList = window.labsList || []

    const redStar = '<span class="redStars">*</span>'
    const labelCompanyType = {
        "labelGovernment": "Организация",
        "labelCommercial": "Клиент"
    }

    initForm()
    initGovDeadlineValidation()

    $('.assigned-select').select2({
        theme: 'bootstrap-5',
        placeholder: $(this).data('placeholder')
    })


    /**
     * @desc Переключает тип заявки
     */
    function toggleRequestType() {
        const reqType = $('.req-type-field').val()
        const hasId = $('input[name="id"]').length > 0
        const $labelCompany = $('.label-company')

        // Сначала скрываем все специфичные для типов блоки
        $('.type-specific-block').addClass('visually-hidden')
        
        // Для сохранения заявки со скрытыми обязательными полями
        $('.type-specific-block input, .type-specific-block select').prop('disabled', true)
        $('.type-specific-block [data-conditionally-required="true"]').prop('required', false)
        
        if (!reqType) { return }
        
        destroyAllTooltips()
        
        // Гос. работы
        if (reqType === '9') {
            // Для гос. работ показываем текстовое поле и скрываем селект
            $('#contract-select').addClass('visually-hidden').prop('disabled', true)
            $('#contract-input').removeClass('visually-hidden').prop('disabled', false)
            
            $('.type-gov-block').removeClass('visually-hidden')
            
            // Активация полей и required для отображаемых блоков
            $('.type-gov-block input, .type-gov-block select').prop('disabled', false)
            $('.type-gov-block [data-conditionally-required="true"]').prop('required', true)

            $labelCompany.html(labelCompanyType.labelGovernment + ' ' + redStar)
            
            // Работа с таблицей гос. работ
            initializeResponsibleSelects()
            loadLaboratories()
            initializeExistingRows()
            initializeEditableCells()
            
            // if (isNewRequest && $('.gov-works-table tbody tr.gov-work-row').length === 0) {
            //     addGovWorkRow()
            // }
        } else if (reqType === 'SALE') {
            // Для коммерческих заявок показываем селект и скрываем текстовое поле
            $('#contract-select').removeClass('visually-hidden').prop('disabled', false)
            $('#contract-input').addClass('visually-hidden').prop('disabled', true)
            
            $('.type-sale-block').not('#sale-materials-block').removeClass('visually-hidden')
            
            if (!hasId) {
                $('#sale-materials-block').removeClass('visually-hidden')
                $('#sale-materials-block input, #sale-materials-block select').prop('disabled', false)
            } else {
                $('#sale-materials-block').addClass('visually-hidden')
                $('#sale-materials-block input, #sale-materials-block select').prop('disabled', true)
            }
            
            // Активация полей и required для отображаемых блоков
            $('.type-sale-block').not('#sale-materials-block').find('input, select').prop('disabled', false)
            $('.type-sale-block').not('#sale-materials-block').find('[data-conditionally-required="true"]').prop('required', true)

            $labelCompany.html(labelCompanyType.labelCommercial + ' ' + redStar)
        }
    }
    
    /**
     * @desc Инициализация при загрузке страницы
     */
    function initForm() {
        const reqType = $('.req-type-field').val()

        if (isNewRequest) {
            if (reqType) {
                toggleRequestType()
            } else {
                // Скрываем все блоки для новой заявки
                $('.type-specific-block').addClass('visually-hidden')

                // Отключаем required и делаем поля недоступными
                $('.type-specific-block input, .type-specific-block select').prop('disabled', true)
                $('.type-specific-block [data-conditionally-required="true"]').prop('required', false)
            }
        } else {
            if (reqType) {
                toggleRequestType()
            } else {
                $('.type-specific-block').addClass('visually-hidden')
            }
        }
    }
    
    $body.on('change', '.req-type-field', function() {
        toggleRequestType()
    })
    
    // Вероятно надо использовать id для формы
    $body.on('submit', 'form', function(e) {
        const reqType = $('.req-type-field').val()
        
        if (!reqType) {
            e.preventDefault()
            showErrorMessage('Пожалуйста, выберите тип заявки')
            return false
        }
        
        if (reqType === '9') {
            if (!validateGovWorksForm(e)) {
                e.preventDefault()
                return false
            }
        }

        // Валидация для коммерческой заявки
        if (reqType === 'SALE' && !validationSale()) {
            e.preventDefault()
            return false
        }
    })
    
    /**
     * @desc Валидация для заявок гос. работа
     */
    function validateGovWorksForm() {
        const company = $('#company').val(),
              responsible = $('#assigned0').val(),
              responsibleHidden = $('#assigned0-hidden').val(),
              workRows = $('.gov-works-table tbody tr.gov-work-row')
        
        if (!company || (!responsible && !responsibleHidden) || workRows.length === 0) {
            if (!company) {
                showErrorMessage('Пожалуйста, укажите организацию', '#company')
            } else if (!responsible && !responsibleHidden) {
                showErrorMessage('Пожалуйста, выберите ответственного', '#error-message')
            } else {
                showErrorMessage('Пожалуйста, добавьте хотя бы одну работу', '#error-message')
            }
            return false
        }
        
        // Обязательные поля в работах
        let hasEmptyRequired = false,
            emptyFieldName = '',
            emptyFieldRow = null
        
        workRows.each(function(rowIndex) {
            const row = $(this)
            
            // Поле названия работы
            const nameCell = row.find('[data-type="text"][data-required="true"]')
            if (nameCell.length && !nameCell.find('.cell-input').val()) {
                hasEmptyRequired = true
                emptyFieldName = 'Наименование работы'
                emptyFieldRow = row
                return false
            }
            
            // Поле материала
            const materialCell = row.find('[data-type="select"][data-required="true"]').filter(function() {
                return $(this).find('select[name^="gov_works[material]"]').length > 0
            })
            
            if (materialCell.length) {
                const materialSelect = materialCell.find('select')
                if (!materialSelect.val()) {
                    hasEmptyRequired = true
                    emptyFieldName = 'Материал'
                    emptyFieldRow = row
                    return false
                }
            }
            
            // Поле количества
            const quantityCell = row.find('[data-type="number"][data-required="true"]')
            if (quantityCell.length && !quantityCell.find('.cell-input').val()) {
                hasEmptyRequired = true
                emptyFieldName = 'Количество'
                emptyFieldRow = row
                return false
            }
            
            // Поле сроки
            const deadlineCell = row.find('[data-type="date"][data-required="true"]').filter(function() {
                return $(this).find('input[name^="gov_works[deadline]"]').length > 0
            })
            
            if (deadlineCell.length) {
                const deadlineInput = deadlineCell.find('input')
                if (!deadlineInput.val()) {
                    hasEmptyRequired = true
                    emptyFieldName = 'Сроки'
                    emptyFieldRow = row
                    return false
                }
            }
            
            // Поле ответственного
            const responsibleCell = row.find('[data-type="select"][data-required="true"]').filter(function() {
                return $(this).find('select[name^="gov_works[assigned_id]"]').length > 0
            })
            
            if (responsibleCell.length) {
                const responsibleSelect = responsibleCell.find('select')
                if (!responsibleSelect.val()) {
                    hasEmptyRequired = true
                    emptyFieldName = 'Ответственный'
                    emptyFieldRow = row
                    return false
                }
            }
            
            // Поле лаборатории
            const labCell = row.find('[data-type="select"][data-required="true"]').filter(function() {
                return $(this).find('select[name^="gov_works[lab_id]"]').length > 0
            })
            
            if (labCell.length) {
                const labSelect = labCell.find('select')
                if (!labSelect.val()) {
                    hasEmptyRequired = true
                    emptyFieldName = 'Испытания в лаборатории'
                    emptyFieldRow = row
                    return false
                }
            }
        })
        
        if (hasEmptyRequired) {
            const rowNumber = emptyFieldRow ? $('.gov-works-table tbody tr.gov-work-row').index(emptyFieldRow) + 1 : '',
                  rowText = rowNumber ? ` в строке ${rowNumber}` : ''
            
            showErrorMessage(`Пожалуйста, заполните обязательное поле "${emptyFieldName}"${rowText}`, '#error-message')
            return false
        }
        
        return true
    }

    /**
     * @desc Валидация для коммерческих заявок
     */
    function validationSale() {
        let isValidEmail = true
        $body.find('input[name="EMAIL"], input[name="POST_MAIL"], input[name="addEmail[]"]').each(function () {
            if (!validateEmailField($(this))) {
                isValidEmail = false
            }
        })

        if (!isValidEmail) {
            scrollToFirstError()
            return false
        }

        return true
    }

    $body.on('input change', 'input[name="EMAIL"], input[name="POST_MAIL"], input[name="addEmail[]"]', function() {
        validateEmailField($(this))
    })

    /**
     * @desc Получает список материалов
     */
    function getMaterialOptions() {
        let materialOptions = '<option value="">Выберите материал</option>'
        $('#materials option').each(function() {
            const value = $(this).data('value'),
                  text = $(this).text()
            if (value && text) {
                materialOptions += `<option value="${value}">${text}</option>`
            }
        })
        return materialOptions
    }
    
    /**
     * @desc Получает список лабораторий
     */
    function getLabOptions() {
        let labOptions = '<option value="">Выберите лабораторию</option>'
        if (window.labsList && window.labsList.length > 0) {
            window.labsList.forEach(function(lab) {
                labOptions += `<option value="${lab.ID}">${lab.NAME}</option>`
            })
        }
        return labOptions
    }
    
    /**
     * @desc Добавление новой строки с работой
     */
    function addGovWorkRow() {
        const objectValue = $('#object').val()
        
        let responsibleOptions = '<option value="">Выберите ответственного</option>'
        
        /* Запрет выбора ответственных
        const selectedResponsibles = []
        $('.gov-works-table tbody tr.gov-work-row select[name$="[assigned_id]"]').each(function() {
            const value = $(this).val()
            if (value) {
                selectedResponsibles.push(value)
            }
        })
        
        $('#assigned0 option').each(function() {
            const value = $(this).val(),
                  text = $(this).text()
            if (value && selectedResponsibles.indexOf(value) === -1) {
                responsibleOptions += `<option value="${value}">${text}</option>`
            }
        })
        */
        
        $('#assigned0 option').each(function() {
            const value = $(this).val(),
                  text = $(this).text()
            if (value) {
                responsibleOptions += `<option value="${value}">${text}</option>`
            }
        })
        
        const labOptions = getLabOptions(),
              materialOptions = getMaterialOptions()
        
        const newRow = `
            <tr class="gov-work-row">
                <td>
                    <div class="editable-cell" data-type="text" data-required="true">
                        <span class="cell-display"></span>
                        <input type="text" name="gov_works[name][]" class="cell-input visually-hidden form-control-sm" value="">
                        <input type="hidden" name="gov_works[work_id][]" value="">
                    </div>
                </td>
                <td>
                    <div class="editable-cell" data-type="text">
                        <span class="cell-display">${objectValue || ''}</span>
                        <input type="text" name="gov_works[object][]" class="cell-input visually-hidden form-control-sm" value="${objectValue || ''}">
                    </div>
                </td>
                <td>
                    <div class="editable-cell" data-type="select" data-required="true">
                        <span class="cell-display"></span>
                        <select name="gov_works[material][]" class="cell-input visually-hidden form-control-sm">
                            ${materialOptions}
                        </select>
                    </div>
                </td>
                <td>
                    <div class="editable-cell" data-type="number" data-required="true">
                        <span class="cell-display">1</span>
                        <input type="number" name="gov_works[quantity][]" class="cell-input visually-hidden form-control-sm" value="1" min="1" step="1">
                    </div>
                </td>
                <td>
                    <div class="editable-cell" data-type="date" data-required="true">
                        <span class="cell-display"></span>
                        <input type="date" name="gov_works[deadline][]" class="cell-input visually-hidden form-control-sm" value="">
                    </div>
                </td>
                <td>
                    <div class="editable-cell" data-type="select" data-required="true">
                        <span class="cell-display"></span>
                        <select name="gov_works[assigned_id][]" class="cell-input visually-hidden form-control-sm">
                            ${responsibleOptions}
                        </select>
                    </div>
                </td>
                <td>
                    <div class="editable-cell" data-type="date">
                        <span class="cell-display"></span>
                        <input type="date" name="gov_works[departure_date][]" class="cell-input visually-hidden form-control-sm" value="">
                    </div>
                </td>
                <td>
                    <div class="editable-cell" data-type="select" data-required="true">
                        <span class="cell-display"></span>
                        <select name="gov_works[lab_id][]" class="cell-input visually-hidden form-control-sm">
                            ${labOptions}
                        </select>
                    </div>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm btn-square-sm remove-gov-work">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </td>
            </tr>`
        
        $('#govWorksTable tbody').append(newRow)
        
        initializeEditableCells()
        updateGovWorksResponsibles()
    }

    $body.on('click', '.add_material', function() {
        const lastMaterial = $('.added_material').last()
        let index = 1
        
        if (lastMaterial.length > 0) {
            const lastMaterialId = lastMaterial.find('input[id^="material"]').attr('id')
            index = parseInt(lastMaterialId.replace('material', '')) + 1
        }
        
        const newMaterial = `
            <div class="form-group row added_material">
                <label class="col-sm-2 col-form-label"></label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <input
                            type="text"
                            id="material${index}"
                            list="materials"
                            name="material[${index}][name]"
                            class="form-control"
                            required
                            autocomplete="off"
                        >
                        <span class="input-group-text">Кол-во:</span>
                        <input type="number" name="material[${index}][count]" class="form-control material-count" min="1" step="1" required value="1">
                    </div>
                    <input type="hidden" name="material[${index}][id]" id="material${index}-hidden" class="material_id" value="">
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-danger remove_this btn-add-del" type="button">
                        <i class="fa-solid fa-minus icon-fix"></i>
                    </button>
                </div>
            </div>`
        
        if (lastMaterial.length > 0) {
            lastMaterial.after(newMaterial)
        } else {
            $('#material-block').after(newMaterial)
        }
    })

    /**
     * Обновляет список ответственных в дополнительных селектах.
     * Исключает из них главного ответственного.
     * Сохраняет текущий выбор, если он всё ещё доступен.
     */
    function updateAssignedSelects() {
        const $main = $('#assigned0')
        const optionsHTML = $main.html()
        const mainVal = $main.val()

        $('.added_assigned select.assigned-select').each(function() {
            const $select = $(this)
            const oldVal = $select.val()

            let $temp = $('<select>' + optionsHTML + '</select>')

            let $option = $temp.find('option[value=""][disabled]').filter(function() {
                return $(this).text().trim() === 'Выберите главного ответственного'
            });
            if ($option.length) {
                $option.text('Выберите ответственного')
            } else {
                $temp.find('option').each(function() {
                    if ($(this).text().trim() === 'Выберите главного ответственного') {
                        $(this).text('Выберите ответственного')
                    }
                });
            }

            if (mainVal) {
                $temp.find('option[value="' + mainVal + '"]').remove()
            }

            $select.html($temp.html())

            // Если ранее выбранное значение все еще присутствует в обновлённом списке, восстанавливаем его
            if ($select.find('option[value="' + oldVal + '"]').length > 0) {
                $select.val(oldVal)
            }
        });
    }

    $body.on('change', '#assigned0', function() {
        updateAssignedSelects();
        $('.add_assigned').prop('disabled', !$(this).val())
    })

    $body.on('click', '.add_assigned', function() {
        const lastAssigned = $('.added_assigned').last()
        let index = 1

        if (lastAssigned.length > 0) {
            const lastAssignedId = lastAssigned.find('select[id^="assigned"]').attr('id')
            index = parseInt(lastAssignedId.replace('assigned', '')) + 1
        }

        const mainVal = $('#assigned0').val()
        let optionsHTML = $('#assigned0').html()

        let $temp = $('<select>' + optionsHTML + '</select>');
        $temp.find('option[value="' + mainVal + '"]').remove();
        optionsHTML = $temp.html();

        const newAssigned = `
            <div class="form-group row added_assigned">
                <label class="col-sm-2 col-form-label">Ответственный</label>
                <div class="col-sm-8">
                    <select class="form-control assigned-select"
                            id="assigned${index}"
                            data-placeholder="Выберите ответственного"
                            name="ASSIGNED[]"
                    >
                    <option value=""></option>
                        ${optionsHTML}
                    </select>
                    <input name="id_assign[]" id="assigned${index}-hidden" class="assigned_id" type="hidden" value="">
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-danger remove_this btn-add-del" type="button">
                        <i class="fa-solid fa-minus icon-fix"></i>
                    </button>
                </div>
            </div>`

        if (lastAssigned.length > 0) {
            lastAssigned.after(newAssigned)
        } else {
            $('#main-responsible-block').after(newAssigned)
        }

        $body.find('.assigned-select').select2({
            theme: 'bootstrap-5',
            placeholder: $(this).data('placeholder')
        })
    })

    $body.on('click', '.add_email', function() {
        let $formGroupContainer = $(this).parents('.form-group'),
            countAddedEmail = $('.added_mail').length + 1

        if (countAddedEmail > 1) {
            $formGroupContainer = $('.form-horizontal .added_mail').last()
        }

        $formGroupContainer.after(
            `<div class="form-group row added_mail">
                <label class="col-sm-2 col-form-label">Дополнительный E-mail ${countAddedEmail}</label>
                <div class="col-sm-8">
                    <input type="email" name="addEmail[]" class="form-control" placeholder="_@_._">
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-danger remove_this btn-add-del" type="button"><i class="fa-solid fa-minus icon-fix"></i></button>
                </div>
            </div>`
        )
    })

    $body.on('change', '.assigned-select', function(e) {
        let $select = $(e.target),
            $hiddenInput = $('#' + $select.attr('id') + '-hidden')

        $hiddenInput.val($($select).val())
        $(this).parents('.form-group').find('.add_assigned').removeAttr('disabled')
    })
    
    $body.on('click', '#addGovWork', function() {
        addGovWorkRow()
    })
    
    $body.on('click', '.remove-gov-work', function() {
        $(this).closest('tr').remove()
    })
    
    // Синхронизация поля объекта между блоком гос. работ и таблицей
    $body.on('change', '#object', function() {
        const objectValue = $(this).val()
        
        $('.gov-work-row').each(function() {
            const objectCell = $(this).find('.editable-cell[data-type="text"]').eq(1)
            objectCell.find('.cell-display').text(objectValue)
            objectCell.find('input').val(objectValue)
            
            if (objectValue && objectValue.trim() !== '') {
                objectCell.find('.cell-display').attr('title', objectValue)
            } else {
                objectCell.find('.cell-display').removeAttr('title')
            }
        })
        
        destroyAllTooltips()
        if (typeof $.fn.tooltip === 'function') {
            $('.editable-cell .cell-display[title]').tooltip({
                container: 'body',
                trigger: 'hover'
            })
        }
    })
    
    /**
     * @desc Уничтожает все подсказки на странице
     */
    function destroyAllTooltips() {
        if (typeof $.fn.tooltip === 'function') {
            $('[data-bs-toggle="tooltip"]').tooltip('dispose')
            $('.tooltip').remove()
            $('[title]').tooltip('dispose')
            $('.editable-cell .cell-display[title]').tooltip('dispose')
        }
    }
    
    /**
     * @desc Инициализации редактируемых ячеек
     */
    function initializeEditableCells() {
        destroyAllTooltips()
        
        // Выделяем пустые обязательные поля
        $('.editable-cell[data-required="true"]').each(function() {
            const cell = $(this),
            //   input = cell.find('.cell-input'),
                display = cell.find('.cell-display')
            
            // if (!input.val()) {
            //     display.addClass('empty-required')
            // } else {
            //     display.removeClass('empty-required')
            // }
            
            // Удаляем data-bs-* атрибуты, если они есть от предыдущих инициализаций
            display.removeAttr('data-bs-original-title')
            display.removeAttr('data-bs-toggle')
            display.removeAttr('aria-describedby')
            
            if (display.text() && display.text().trim() !== '') {
                display.attr('title', display.text().trim())
            } else {
                display.removeAttr('title')
            }
        })
        
        // Установка min и step для полей количества
        $('.editable-cell[data-type="number"]').each(function() {
            const cell = $(this),
                  input = cell.find('.cell-input')
            
            if (input.attr('name').includes('quantity')) {
                input.attr('min', '1')
                input.attr('step', '1')
                
                // Если значение отрицательное или дробное, исправляем
                let value = input.val()
                if (value <= 0) {
                    input.val(1)
                } else if (value % 1 !== 0) {
                    input.val(Math.floor(value))
                }
            }
        })
        
        // Добавляем title ко всем непустым ячейкам (не только обязательным)
        $('.editable-cell .cell-display').each(function() {
            const display = $(this)
            // Удаляем data-bs-* атрибуты, если они есть от предыдущих инициализаций
            display.removeAttr('data-bs-original-title')
            display.removeAttr('data-bs-toggle')
            display.removeAttr('aria-describedby')
            
            if (display.text() && display.text().trim() !== '') {
                display.attr('title', display.text().trim())
            } else {
                display.removeAttr('title')
            }
        })
        
        // Инициализация Bootstrap tooltip'ов, если они доступны
        if (typeof $.fn.tooltip === 'function') {
            // Для Bootstrap 5 добавляем атрибут data-bs-toggle
            if (typeof bootstrap !== 'undefined') {
                $('.editable-cell .cell-display[title]').attr('data-bs-toggle', 'tooltip')
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl, {
                        container: 'body',
                        trigger: 'hover'
                    })
                })
            } else {
                // Для Bootstrap 4 и ниже используем jQuery метод
                $('.editable-cell .cell-display[title]').tooltip({
                    container: 'body',
                    trigger: 'hover'
                })
            }
        }
        
        // Обработчик клика по ячейке
        $body.off('click', '.editable-cell .cell-display').on('click', '.editable-cell .cell-display', function() {
            const cell = $(this).closest('.editable-cell')
            currentEditCell = cell
            
            const cellType = cell.data('type'),
                  inputElement = cell.find('.cell-input'),
                  currentValue = inputElement.val(),
                  isRequired = cell.data('required') === true
            
            let modalTitle = 'Редактирование поля',
                modalContent = '',
                modalSelect = null
            
            if (cellType === 'text' || cellType === 'number') {
                modalContent = `
                    <div class="modal-form-group">
                        <label class="form-label">${isRequired ? 'Введите значение *' : 'Введите значение'}</label>
                        <input type="${cellType}" class="form-control modal-input" value="${currentValue}" ${isRequired ? 'required' : ''}>
                        <div class="invalid-feedback">Это поле обязательно для заполнения</div>
                    </div>`
            } else if (cellType === 'date') {
                modalContent = `
                    <div class="modal-form-group">
                        <label class="form-label">${isRequired ? 'Выберите дату *' : 'Выберите дату'}</label>
                        <input type="date" class="form-control modal-input" value="${currentValue}" ${isRequired ? 'required' : ''}>
                        <div class="invalid-feedback">Это поле обязательно для заполнения</div>
                    </div>`
            } else if (cellType === 'select') {
                if (inputElement.attr('name').includes('assigned_id')) {
                    modalTitle = 'Выберите ответственного'
                    
                    if (!inputElement.html() || inputElement.find('option').length === 0) {
                        const responsibleOptions = $('#assigned0').html() || ''
                        inputElement.html(responsibleOptions)
                    }
                } else if (inputElement.attr('name').includes('lab_id')) {
                    modalTitle = 'Выберите лабораторию'
                    
                    if (!inputElement.html() || inputElement.find('option').length === 0) {
                        inputElement.html(getLabOptions())
                    }
                } else if (inputElement.attr('name').includes('material')) {
                    modalTitle = 'Выберите материал'
                    
                    // Сохраняем текущее значение материала перед обновлением опций
                    const currentMaterialValue = inputElement.val(),
                          currentMaterialText = currentMaterialValue ? inputElement.find(`option[value="${currentMaterialValue}"]`).text() : ''
                    
                    inputElement.html(getMaterialOptions())
                    
                    if (currentMaterialValue && inputElement.find(`option[value="${currentMaterialValue}"]`).length === 0) {
                        inputElement.append(`<option value="${currentMaterialValue}">${currentMaterialText || currentMaterialValue}</option>`)
                    }
                    
                    if (currentMaterialValue) {
                        inputElement.val(currentMaterialValue)
                    }
                }

                modalSelect = $(`<select class="form-control modal-input" ${isRequired ? 'required' : ''}></select>`)
                
                inputElement.find('option').each(function() {
                    const option = $(this).clone()
                    modalSelect.append(option)
                })
                
                modalSelect.val(currentValue)
                
                modalContent = `
                    <div class="modal-form-group">
                        <label class="form-label">${isRequired ? 'Выберите значение *' : 'Выберите значение'}</label>
                        <div class="select-container"></div>
                        <div class="invalid-feedback">Это поле обязательно для заполнения</div>
                    </div>`
            }
            
            const modalHtml = `
                <div class="modal-edit-cell">
                    <div class="modal-edit-content">
                        <div class="modal-edit-header">
                            <h5>${modalTitle}</h5>
                            <button type="button" class="btn-close modal-edit-close" aria-label="Close"></button>
                        </div>
                        <div class="modal-edit-body">
                            ${modalContent}
                        </div>
                        <div class="modal-edit-footer">
                            <button type="button" class="btn btn-secondary modal-edit-close">Отмена</button>
                            <button type="button" class="btn btn-primary modal-edit-save">Сохранить</button>
                        </div>
                    </div>
                </div>`
            
            $body.append(modalHtml)
            
            if (cellType === 'select') {
                if (modalSelect) {
                    $('.select-container').append(modalSelect)
                }
            } else {
                const modalInput = $('.modal-input')
                modalInput.val(currentValue)
            }
            
            /* Запрет выбора ответственных
            if (cellType === 'select' && inputElement.attr('name').includes('assigned_id')) {
                const modalInput = $('.modal-input')
                if (modalInput.length > 0) {
                    const selectedResponsibles = []
                    
                    $('.gov-works-table tbody tr.gov-work-row select[name$="[assigned_id]"]').each(function() {
                        const select = $(this),
                              value = select.val()
                        if (value && select.get(0) !== inputElement.get(0)) {
                            selectedResponsibles.push(value)
                        }
                    })
                    
                    modalInput.find('option').each(function() {
                        const option = $(this),
                              value = option.val()
                        if (value && selectedResponsibles.includes(value)) {
                            option.remove()
                        }
                    })
                    
                    if (modalInput.find('option').length <= 1) {
                        modalInput.html('<option value="" disabled>Нет доступных вариантов</option>')
                    }
                }
            }
            */
            
            $('.modal-edit-cell').css('display', 'flex').fadeIn(200)
            
            if ($('.modal-input').length > 0) {
                $('.modal-input').focus()
            }
        })
        
        setupModalHandlers()
    }
    
    /**
     * @desc Обработчик событий модального окна
     */
    function setupModalHandlers() {
        $body.off('click', '.modal-edit-close').on('click', '.modal-edit-close', function() {
            closeModal()
        })
        
        $body.off('change', '.modal-input').on('change', '.modal-input', function() {
            if (!currentEditCell) return
            
            const cellType = currentEditCell.data('type'),
                  inputElement = currentEditCell.find('.cell-input')
            
            if (cellType === 'select' && inputElement.attr('name').includes('material')) {
                const modalSelect = $(this),
                      newValue = modalSelect.val()
                
                if (newValue) {
                    inputElement.val(newValue)
                }
            }
            
            if (cellType === 'number' && inputElement.attr('name').includes('quantity')) {
                const modalInput = $(this)
                let value = modalInput.val()

                if (value <= 0) {
                    modalInput.val(1)
                } else if (value % 1 !== 0) {
                    modalInput.val(Math.floor(value))
                }
            }
        })
        
        $body.off('click', '.modal-edit-save').on('click', '.modal-edit-save', function() {
            if (currentEditCell) {
                const cellType = currentEditCell.data('type'),
                      inputElement = currentEditCell.find('.cell-input'),
                      displayElement = currentEditCell.find('.cell-display'),
                      modalInput = $('.modal-input'),
                      oldValue = inputElement.val()
                
                if (currentEditCell.data('required') && !modalInput.val()) {
                    modalInput.addClass('is-invalid')
                    return
                }
                
                let newValue = modalInput.val(),
                    displayValue = newValue
                
                if (cellType === 'number' && inputElement.attr('name').includes('quantity')) {
                    if (newValue <= 0) {
                        newValue = 1
                    } else if (newValue % 1 !== 0) {
                        newValue = Math.floor(newValue)
                    }
                    displayValue = newValue
                    modalInput.val(newValue)
                }
                
                if (cellType === 'select' && newValue) {
                    displayValue = modalInput.find('option:selected').text()
                    
                    if (inputElement.attr('name').includes('assigned_id')) {
                        inputElement.val(newValue)
                    } else if (inputElement.attr('name').includes('lab_id')) {
                        if (!inputElement.html() || inputElement.find('option').length <= 1) {
                            inputElement.html(getLabOptions())
                        }
                        inputElement.val(newValue)
                    } else if (inputElement.attr('name').includes('material')) {
                        if (!inputElement.html() || inputElement.find('option').length <= 1) {
                            inputElement.html(getMaterialOptions())
                        }
                        
                        if (newValue && inputElement.find(`option[value="${newValue}"]`).length === 0) {
                            inputElement.append(`<option value="${newValue}">${displayValue}</option>`)
                        }
                        
                        inputElement.val(newValue)
                    } else {
                        inputElement.val(newValue)
                    }
                    
                    if (inputElement.attr('name').includes('assigned_id') && oldValue !== newValue) {
                        updateGovWorksResponsibles()
                    }
                } else {
                    inputElement.val(newValue)
                }
                
                if (cellType === 'date' && newValue) {
                    const dateObj = new Date(newValue)
                    displayValue = dateObj.toLocaleDateString('ru-RU')
                }
                
                displayElement.text(displayValue || '')
                
                if (displayValue && displayValue.trim() !== '') {
                    displayElement.removeAttr('title')
                    displayElement.removeAttr('data-bs-original-title')
                    displayElement.removeAttr('data-bs-toggle')
                    displayElement.removeAttr('aria-describedby')
                    
                    displayElement.attr('title', displayValue)
                    
                    destroyAllTooltips()
                    
                    if (typeof $.fn.tooltip === 'function') {
                        displayElement.tooltip({
                            container: 'body',
                            trigger: 'hover'
                        })
                    }
                } else {
                    displayElement.removeAttr('title')
                    displayElement.removeAttr('data-bs-original-title')
                    displayElement.removeAttr('data-bs-toggle')
                    displayElement.removeAttr('aria-describedby')
                    
                    if (typeof $.fn.tooltip === 'function') {
                        displayElement.tooltip('dispose')
                    }
                }
                
                // if (currentEditCell.data('required') && !newValue) {
                //     displayElement.addClass('empty-required')
                // } else {
                //     displayElement.removeClass('empty-required')
                // }
                
                closeModal()
            }
        })
        
        $body.off('click', '.modal-edit-cell').on('click', '.modal-edit-cell', function(e) {
            if ($(e.target).hasClass('modal-edit-cell')) {
                closeModal()
            }
        })
        
        $body.off('keydown').on('keydown', function(e) {
            if (e.key === 'Escape' && $('.modal-edit-cell').is(':visible')) {
                closeModal()
            }
        })
        
        $body.off('keydown', '.modal-input').on('keydown', '.modal-input', function(e) {
            if (e.key === 'Enter' && !$(this).is('textarea') && !$(this).is('select')) {
                e.preventDefault()
                $('.modal-edit-save').click()
            }
        })
    }
    
    /**
     * @desc Закрытие модального окна
     */
    function closeModal() {
        const currentCell = currentEditCell,
              currentModalInput = $('.modal-input')
        
        // Сохраняем текущее значение перед закрытием, чтобы восстановить его при следующем открытии
        if (currentCell && currentCell.data('type') === 'select') {
            const inputElement = currentCell.find('.cell-input')
            
            if (inputElement.attr('name').includes('material')) {
                if (currentModalInput.length > 0 && currentModalInput.is('select')) {
                    const modalValue = currentModalInput.val()
                    if (modalValue) {
                        if (inputElement.find(`option[value="${modalValue}"]`).length === 0) {
                            const modalText = currentModalInput.find('option:selected').text()
                            inputElement.append(`<option value="${modalValue}">${modalText}</option>`)
                        }
                        inputElement.val(modalValue)
                    }
                }
            }
        }
        
        $('.modal-edit-cell').fadeOut(200, function() {
            $(this).remove()
            
            if (currentEditCell) {
                const displayElement = currentEditCell.find('.cell-display')
                const currentText = displayElement.text().trim()
                
                displayElement.removeAttr('data-bs-original-title')
                displayElement.removeAttr('data-bs-toggle')
                displayElement.removeAttr('aria-describedby')
                
                if (currentText) {
                    displayElement.attr('title', currentText)
                } else {
                    displayElement.removeAttr('title')
                }
                
                destroyAllTooltips()
                
                // Переинициализация подсказок для всех элементов с title
                if (typeof $.fn.tooltip === 'function') {
                    $('.editable-cell .cell-display[title]').tooltip({
                        container: 'body',
                        trigger: 'hover'
                    })
                }
            }
        })
        currentEditCell = null
    }
    
    /**
     * @desc Инициализация существующих строк таблицы
     */
    function initializeExistingRows() {
        $('.gov-works-table tbody tr').each(function() {
            const row = $(this)
            
            row.find('input, select').each(function() {
                const input = $(this),
                      cell = input.parent(),
                      inputType = input.attr('type') || (input.is('select') ? 'select' : 'text'),
                      isRequired = input.prop('required')
                
                if (cell.hasClass('editable-cell')) {
                    return
                }
                
                let currentValue = input.val(),
                    displayValue = currentValue
                
                if (inputType === 'select' || input.is('select')) {
                    displayValue = input.find('option:selected').text()
                }
                
                if (inputType === 'date' && currentValue) {
                    const dateObj = new Date(currentValue)
                    displayValue = dateObj.toLocaleDateString('ru-RU')
                }
                
                input.wrap(`<div class="editable-cell" data-type="${inputType}" ${isRequired ? 'data-required="true"' : ''}></div>`)
                input.addClass('cell-input visually-hidden')
                input.after(`<div class="cell-display truncate" title="${displayValue || ''}">${displayValue || ''}</div>`)
            })
        })
        
        initializeEditableCells()
    }
    
    /**
     * @desc Заполнения всех селектов ответственных в таблице гос. работ
     */
    function initializeResponsibleSelects() {
        const responsibleOptions = $('#assigned0').html() || ''
        
        $('.gov-works-table tbody tr.gov-work-row select[name$="[assigned_id]"]').each(function() {
            const select = $(this)
            
            if (!select.html() || select.find('option').length === 0) {
                select.html(responsibleOptions)
            }
            
            select.find('option[value=""]').remove()
        })
        
        updateGovWorksResponsibles()
    }
    
    /**
     * @desc Загрузка лабораторий
     */
    function loadLaboratories() {
        $.ajax({
            url: '/ulab/lab/getLabListAjax/',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response && Array.isArray(response)) {
                    window.labsList = response
                    
                    const labOptions = getLabOptions()
                    updateLabSelects(labOptions)
                }
            },
            error: function(xhr, status, error) {
                console.error('Ошибка при загрузке лабораторий:', error)
            }
        })
    }
    
    /**
     * @desc Обновление всех селектов лабораторий
     */
    function updateLabSelects(labOptions) {
        $('select[name$="[lab_id]"]').each(function() {
            const select = $(this),
                  currentValue = select.val()
            
            select.empty().html(labOptions)
            
            if (currentValue) {
                select.val(currentValue)
            }
        })
    }
    
    /**
     * @desc Обновление всех селектов ответственных в таблице гос. работ
     */
    function updateGovWorksResponsibles() {
        /* Запрет выбора ответственных
        const selectedResponsibles = []
        $('.gov-works-table tbody tr.gov-work-row select[name$="[assigned_id]"]').each(function() {
            const value = $(this).val()
            if (value) {
                selectedResponsibles.push(value)
            }
        })
        */
        
        const allOptions = $('#assigned0 option').clone()
        
        $('.gov-works-table tbody tr.gov-work-row select[name$="[assigned_id]"]').each(function() {
            const select = $(this),
                  currentValue = select.val()
            
            if (!currentValue) {
                select.empty()
                select.append('<option value="">Выберите ответственного</option>')
                
                allOptions.each(function() {
                    const option = $(this).clone(),
                          optionValue = option.val()
                    
                    if (!optionValue) {
                        return
                    }
                    
                    /* Запрет выбора ответственных
                    if (selectedResponsibles.indexOf(optionValue) === -1) {
                        select.append(option)
                    }
                    */
                    select.append(option)
                })
            } else {
                select.empty()
                
                allOptions.each(function() {
                    const option = $(this).clone(),
                          optionValue = option.val()
                    
                    if (!optionValue) {
                        return
                    }
                    
                    /* Запрет выбора ответственных
                    if (optionValue === currentValue || selectedResponsibles.indexOf(optionValue) === -1) {
                        select.append(option)
                    }
                    */
                    select.append(option)
                })
                
                select.val(currentValue)
            }
        })
    }

    /**
     * Проверка даты в модальном окне при выборе сроков
     */
    function initGovDeadlineValidation() {
        $body.on('change', '.modal-input[type="date"]', function() {
            if (currentEditCell && 
                currentEditCell.find('input[name^="gov_works[deadline]"]').length > 0) {
                
                const govDeadline = $('#gov_deadline').val()
                if (!govDeadline) return
                
                const selectedDate = $(this).val()
                
                if (new Date(selectedDate) > new Date(govDeadline)) {
                    $(this).val('')
                    $(this).addClass('is-invalid')
                    $(this).siblings('.invalid-feedback').text('Срок не может быть позже ' + 
                        new Date(govDeadline).toLocaleDateString('ru-RU'))
                        .show()
                } else {
                    $(this).removeClass('is-invalid')
                    $(this).siblings('.invalid-feedback').hide()
                }
            }
        })
        
        $body.on('change', '#gov_deadline', function() {
            const govDeadline = $(this).val()
            if (!govDeadline) return
            
            $('.gov-works-table tbody tr.gov-work-row').each(function() {
                const deadlineCell = $(this).find('[data-type="date"]').filter(function() {
                    return $(this).find('input[name^="gov_works[deadline]"]').length > 0
                })
                
                if (deadlineCell.length) {
                    const deadlineInput = deadlineCell.find('input')
                    const deadlineValue = deadlineInput.val()
                    
                    if (deadlineValue && new Date(deadlineValue) > new Date(govDeadline)) {
                        deadlineInput.val('')
                        deadlineCell.find('.cell-display').text('')
                        showErrorMessage('Срок работы №' + ($(this).index() + 1) + ' был сброшен, т.к. он позже общего срока', '#error-message')
                    }
                }
            })
        })
    }
})