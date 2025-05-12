$(function ($) {
    let $body = $('body')

    $body.on('click', '.edit_probe', function () {
        const id = $(this).data('id')
        const $form = $('#edit-modal-form')

        $.ajax({
            url: "/ulab/probe/getAjax/",
            data: {"id": id},
            dataType: "json",
            method: "POST",
            success: function (data) {
                $form.find('.probe_id').val(id)
                $form.find('.name_for_protocol').val(data.name_for_protocol)
                $form.find('.probe_place').val(data.place)
                $form.find('.probe_date').val(data.date_probe)

                $.magnificPopup.open({
                    items: {
                        src: '#edit-modal-form',
                        type: 'inline',
                    },
                    fixedContentPos: false,
                    closeOnBgClick: false,
                })
            }
        })

        return false
    })

    $body.on('change', '.selection-type', function () {
        const id = $(this).data('id')
        let val = $(this).prop('checked')

        $.ajax({
            url: "/ulab/probe/changeSelectionTypeAjax/",
            data: {"id": id,
                   "checked": val},
            dataType: "json",
            method: "POST",
            success: function (data) { }
        })

        return false
    })


    $body.on('click', '.history_probe', function () {
        const id = $(this).data('id')
        const $form = $('#history-modal-form')

        $form.find('.cipher').empty()
        $form.find('.history-info').empty()

        $.ajax({
            url: "/ulab/probe/getHistoryAjax/",
            data: {"id": id},
            dataType: "json",
            method: "POST",
            success: function (data) {
                $form.find('.cipher').text(data.info.cipher)

                let html = ``

                $.each(data.history, function (i, item) {
                    html +=
                        `<div class="row">
                            <div class="col">${item.date}</div>
                            <div class="col">${item.action}</div>
                            <div class="col">${item.full_name_escaped}</div>
                        </div>`
                })

                if ( html === '' ) {
                    html = `У пробы еще нет истории`
                }

                $form.find('.history-info').html(html)

                $.magnificPopup.open({
                    items: {
                        src: '#history-modal-form',
                        type: 'inline',
                    },
                    fixedContentPos: false,
                    closeOnBgClick: false,
                })
            }
        })

        return false
    })
})
