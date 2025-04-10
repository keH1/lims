$(function ($) {
    $('.popup-with-form').magnificPopup({
        type: 'inline',
        closeBtnInside:true,
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
        fixedContentPos: false
    })

    $('.act-manual-edit').click(function () {
        $('.act-manual-block').toggleClass('visually-hidden')
    })

    $('#act-work-modal-form').submit(function () {
        let actNumber     = $(this).find('input[name=actNumber]').val(),
            actDate       = $(this).find('input[name=actDate]').val(),
            lead          = $(this).find('select[name=lead] option:selected').val(),
            dealId        = $(this).find('input[name=deal_id]').val(),
            tzId          = $(this).find('input[name=tz_id]').val(),
            accountEmail  = $(this).find('input[name=Email]').val()

        window.open(`/protocol_generator/akt_vr.php?ID=${dealId}&TZ_ID=${tzId}&NUM=${actNumber}&DATE=${actDate}&LEAD=${lead}&ACCMAIL=${accountEmail}`, '_blank');
    })

    $('.akt-finish').click(function() {
        let tzId = $('#finish-modal-form').find('input[name=tz_id]').val()
        let stage = $(this).data('stage')

        $.ajax({
            method: 'POST',
            url: '/update_stage_id.php',
            data: {
                satge_id: stage,
                tz_id: tzId,
            },
            success: function(textContent) {
                if (textContent) {
                    location.reload();
                }
            },
            error: function (jqXHR, exception) {
                let msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status === 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status === 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }
                console.log(msg)
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
})

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
