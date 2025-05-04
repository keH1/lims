$(function ($) {
    const $body = $('body')

    $('[data-bs-toggle="popover"]').popover()

    $('.popup-with-form').magnificPopup({
        type: 'inline',
        closeBtnInside:true,
        closeOnBgClick: false,
        fixedContentPos: false
    })

    $('.reloadS').on('click', function () {
        let url = $(this).data('href')
        window.open (url, '_blank')
        setTimeout( function() {location.reload()}, 1500);
    })

    $('.reload').on('click', function () {
        setTimeout( function() {location.reload()}, 1500);
    })

    $('.popup-mail').on('click', function () {
        $('#TYPE').val($(this).data('type'))
        $('#TITLE').val($(this).data('title'))
        $('#ATTACH').val($(this).data('attach'))
        $('#SIG').val($(this).data('sig'))
        $('#PDF').val($(this).data('pdf'))
        $('#YEAR').val($(this).data('year'))
        $('#ID_P').val($(this).data('id_p'))
        $('#ID').val($(this).data('id'))
    })

    $('.popup-mail').magnificPopup({
        type: 'inline',
        closeBtnInside:true,
        closeOnBgClick: false,
        fixedContentPos: false
    })

    $('.act-manual-edit').click(function () {
        $('.act-manual-block').toggleClass('visually-hidden')
    })

    $('#act-work-modal-form').on('submit', function(e) {
        e.preventDefault()

        const $form = $(this)
        const $email = $form.find('[name=Email]')

        const fieldsToValidate = [
            { $el: $form.find('[name=actNumber]'), message: 'Номер акта обязателен' },
            { $el: $form.find('[name=actDate]'), message: 'Дата акта обязательна' },
            { $el: $form.find('[name=lead]'), message: 'Руководитель обязателен' },
            { $el: $email, message: 'Email обязателен' },
        ];

        let hasErrors = false

        // Сброс предыдущих ошибок
        fieldsToValidate.forEach(({ $el }) => clearElementError($el))

        // Проверка на пустоту
        fieldsToValidate.forEach(({ $el, message }) => {
            if ($.trim($el.val()) === '') {
                showElementError($el, message)
                hasErrors = true
            }
        })

        if (hasErrors) {
            return
        }

        if (!validateEmailField($email)) {
            return
        }

        const params = {
            ID: $.trim($form.find('[name=deal_id]').val()),
            TZ_ID: $.trim($form.find('[name=tz_id]').val()),
            NUM: $.trim($form.find('[name=actNumber]').val()),
            DATE: $.trim($form.find('[name=actDate]').val()),
            LEAD: $.trim($form.find('[name=lead]').val()),
            ACCMAIL: $.trim($form.find('[name=Email]').val())
        }

        $form.find('button[type="submit"]').replaceWith(
            `<button class="btn btn-primary" type="button" disabled>
                <span class="spinner-grow spinner-grow-sm spinner-save" role="status" aria-hidden="true"></span>
                Сохранение...
            </button>`
        )

        $.ajax({
            url: '/protocol_generator/akt_vr.php',
            type: 'GET',
            data: { ...params, ajax: 1 },
            dataType: 'json',
            success: function(response) {
                if (response && response.success) {
                    const downloadUrl = `/protocol_generator/akt_vr.php?${new URLSearchParams(params).toString()}`
                    const downloadWindow = window.open(downloadUrl, '_blank')

                    // if (!downloadWindow || downloadWindow.closed || typeof downloadWindow.closed === 'undefined') {
                    //     alert('Всплывающие окна')
                    //     location.reload()
                    //     return
                    // }

                    setInterval(function() {
                        if (downloadWindow.closed) {
                            location.reload()
                        }
                    }, 1500)
                }
            }
        })
    })

    $body.on('input change', '#act-work-modal-form input[name="Email"]', function() {
        validateEmailField($(this))
    })

    $('.akt-finish').click(function() {
        let tzId = $('#finish-modal-form').find('input[name=tz_id]').val()
        let stage = $(this).data('stage')

        $.ajax({
            method: 'POST',
            url: '/ulab/request/updateApplicationStageAjax',
            data: {
                stage_id: stage,
                tz_id: tzId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    location.reload()
                } else {
                    showErrorMessage(response.message, '#error-message')
                }
            },
            error: function (jqXHR, exception) {
                let msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Отсутствует соединение. Проверьте сеть.';
                } else if (jqXHR.status === 404) {
                    msg = 'Запрашиваемая страница не найдена [404].';
                } else if (jqXHR.status === 500) {
                    msg = 'Внутренняя ошибка сервера [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Ошибка при обработке ответа сервера.';
                } else if (exception === 'timeout') {
                    msg = 'Время ожидания истекло.';
                } else if (exception === 'abort') {
                    msg = 'Запрос был прерван.';
                } else {
                    msg = 'Неизвестная ошибка: ' + jqXHR.responseText;
                }
                console.log(msg)
            }
        })
    })

    // Завершение заявки в карточке Гос. работ
    $body.on('click', '#close_app', function() {
        const $btn = $(this)
        const tzId = $btn.data('tz-id')
        const stage = $btn.data('stage')

        $.ajax({
            method: 'POST',
            url: '/ulab/request/updateApplicationStageAjax',
            data: {
                stage_id: stage,
                tz_id: tzId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    location.reload()
                }
            }
        })
    })

    let $checkMail = $('.check-mail')
    let $btnSend = $('.btnOverall')

    $checkMail.click(function () {
        if ( $('.check-mail:checked').length === 0 ) {
            $btnSend.addClass('disabled')
        } else {
            $btnSend.removeClass('disabled')
        }
    })

    $btnSend.click(function () {

        let $btn = $(this)
        let orderId = $(this).data('order-id'),
            dealId = $(this).data('deal-id'),
            tzId = $(this).data('tz-id'),
            email = $(this).data('email'),
            name = $(this).data('name'),
            attach1 = $(this).data('attach1'),
            attach2 = $(this).data('attach2'),
            attach3 = $(this).data('attach3'),
            title = $(this).data('title')

        let $checked = $('.check-mail:checked')
        let data = {
            "ID": orderId,
            "ID2": dealId,
            "TZ_ID": tzId,
            "TYPE": 7,
            "EMAIL": email,
            "NAME": name,
            "ATTACH1": attach1,
            "ATTACH2": attach2,
            "ATTACH3": attach3,
            "TITLE": title,
            'is_ajax': 1,
        }
        let check = []

        $checked.each(function () {
            let what = $(this).data('text')
            check.push(what)
        })

        if ( check.length > 0 ) {
            data.CHECK = check
        }

        $btn.addClass('disabled')

        $.ajax({
            url: `/mail.php`,
            type: "GET", //метод отправки
            dataType: 'text', // data type
            data: data,
            success: function (result) {
                showSuccessMessage("Документы отправлены")
                $('html, body').animate({scrollTop: $('.alert-success').offset().top - 100}, 500)
            },
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
            },
            complete: function () {
                $btn.removeClass('disabled')
            }
        })

        return false
    })

    $body.on('change', '.pdf-upload-form input[type="file"]', function() {
        $(this).closest('form').submit()
    })

    $body.on('submit', '.pdf-upload-form', function(e) {
        e.preventDefault()
        
        const form = $(this)
        const fileInput = form.find('input[type="file"]')
        const file = fileInput[0].files[0]
        const formData = new FormData()
        const fileTypeInput = form.find('input[name="fileType"]')

        formData.append('file', file)
        
        if (fileTypeInput.length) {
            formData.append('fileType', fileTypeInput.val())
        }
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                const data = JSON.parse(response)
                if (data.success) {
                    const parentRow = form.closest('tr')
                    let fileNameContainer = parentRow.find('td:nth-child(2) .file-name-container')
                    
                    if (fileNameContainer.length === 0) {
                        const secondCell = parentRow.find('td:nth-child(2) div:first')
                        fileNameContainer = $('<div class="file-name-container"></div>')
                        secondCell.append(fileNameContainer)
                    }
                    
                    fileNameContainer.html(`
                        <a href="${data.fileUrl}" target="_blank">${truncateFileName(data.fileName, 25)}</a>
                        <a href="#" class="ms-2 text-danger delete-pdf-file" data-deal-id="${form.attr('action').split('/').pop()}" data-file-type="${fileTypeInput.val()}">
                            <i class="fa fa-times"></i>
                        </a>
                    `)

                    parentRow.addClass('table-green')
                    $('.label-pdf-file-upload').addClass('disabled')
                }
            }
        })
    })

    $body.on('click', '.delete-pdf-file', function(e) {
        e.preventDefault()
        
        const deleteBtn = $(this)
        const dealId = deleteBtn.data('deal-id')
        const fileType = deleteBtn.data('file-type')
        
        $.ajax({
            url: `/ulab/request/deleteFileAjax/${dealId}`,
            type: 'POST',
            data: {
                fileType: fileType
            },
            success: function(response) {
                const data = JSON.parse(response)
                if (data.success) {
                    const fileContainer = deleteBtn.closest('.file-name-container')
                    fileContainer.html('Файл не загружен')
                    fileContainer.closest('tr').removeClass('table-green')
                    $('.label-pdf-file-upload').removeClass('disabled')
                }
            }
        })
    })

    $body.on('change', '.check-all', function() {
        const isChecked = $(this).prop('checked')
        $('.protocol-checkbox').prop('checked', isChecked)
        
        $('.download-selected-protocols').prop('disabled', !isChecked)
    })

    $body.on('change', '.protocol-checkbox', function() {
        if (!$(this).prop('checked')) {
            $('.check-all').prop('checked', false)
        } 
        else if ($('.protocol-checkbox:checked').length === $('.protocol-checkbox').length) {
            $('.check-all').prop('checked', true)
        }
        
        const hasCheckedProtocols = $('.protocol-checkbox:checked').length > 0
        $('.download-selected-protocols').prop('disabled', !hasCheckedProtocols)
    })

    $body.on('click', '.download-selected-protocols', function(e) {
        const selectedProtocols = $('.protocol-checkbox:checked')
        const $protocolRow = $('tr[data-protocol]')
        const dealId = $('input[name="deal_id"]').val()

        if (selectedProtocols.length === 1) {
            const filePath = selectedProtocols.first().data('file-path')
            const fileType = selectedProtocols.first().data('file-type')
            
            if (fileType === 'pdf') {
                window.open(filePath, '_blank');
            } else {
                $('<a>', {
                    href: filePath,
                    download: ''
                }).appendTo('body').get(0).click().remove();
            }
        } else {
            const filePaths = []
            const $form = $('#create-protocols-archive-form')

            selectedProtocols.each(function() {
                const filePath = $(this).data('file-path')
                if (filePath) {
                    filePaths.push(filePath)
                }
            })
           
            $form.find('input[name="files[]"]').remove()
            
            $.each(filePaths, function(i, path) {
                $('<input>', {
                    type: 'hidden',
                    name: 'files[]',
                    value: path
                }).appendTo($form)
            })
            
            $form.submit()
        }

        $.ajax({
            url: '/ulab/request/updateProtocolStatusAjax',
            type: 'POST',
            data: {
                deal_id: dealId
            },
            success: function() {
                $protocolRow.addClass('table-green')
                $protocolRow.find('td:nth-child(2)').text('Сформирован')
            }
        })
    })
})

function truncateFileName(fileName, maxLength) {
    if (fileName.length <= maxLength) {
        return fileName
    }
    
    const lastDotIndex = fileName.lastIndexOf('.')
    const extension = lastDotIndex !== -1 ? fileName.slice(lastDotIndex) : ''
    
    const nameLength = maxLength - 3 - extension.length
    if (nameLength <= 0) {
        return fileName.slice(0, maxLength - 3) + '...'
    }
    
    const name = fileName.slice(0, lastDotIndex !== -1 ? lastDotIndex : fileName.length)
    return name.slice(0, nameLength) + '...' + extension
}

Dropzone.options.dropzoneExample = {
    paramName: "file", // The name that will be used to transfer the file
    maxFilesize: 30, // MB
    init: function() {
        this.on("complete", function (file) {
            if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                $('.dropzone-msg').append(
                    `<div class="alert alert-success d-flex align-items-center alert-dismissible fade show" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg>
                    <div>
                        Файлы сохранены. <a href="#" onclick="document.location.reload();">Перезагрузите страницу</a>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`
                )
            }
        });
        this.on("success", function(file, responseText) {
            // console.log('success: ' + responseText);
        })

        this.on("sending", function(file, xhr, formData) {
            // console.log('sending', file, xhr, formData)
        })

        this.on("addedfile", file => {
            // console.log("A file has been added");
        })
    },
    error: function (file, message) {
        // console.log('error: ' + message)
        $('.dropzone-msg').append(
            `<div class="alert alert-danger d-flex align-items-center alert-dismissible fade show" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
                <div>
                    Ошибка: ${message}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`
        )
    }
}