$(function ($) {
    $('.select2').select2({
        theme: 'bootstrap-5'
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

    const $journal = $('#journal_moving')

    let journalDataTable = $journal.DataTable({
        rowReorder: true,
        bAutoWidth: false,
        autoWidth: false,
        fixedColumns: false,
        processing: true,
        serverSide: true,
        bSortCellsTop: true,
        scrollX: true,
        fixedHeader: false,
        colReorder: true,
        ajax: {
            type : 'POST',
            data: function ( d ) {
                d.oborud_id = $('#selectOborud').val()
            },
            url : '/ulab/oborud/getOborudMovingJournal/',
            dataSrc: function (json) {
                return json.data
            },
        },
        rowReorder: {
            dataSrc: 'name'
        },
        columns: [
            {
                data: 'name',
                orderable: true,
                render: function (data, type, item) {
                    return `<a href="/ulab/oborud/edit/${item.ID}">${item.name}</a>`
                }
            },
            {
                data: 'place',
                orderable: false,
            },
            {
                data: 'date',
                orderable: true,
            },
            {
                data: 'receiver_user',
                orderable: true,
            },
            {
                data: 'completeness',
                orderable: false,
            },
            {
                data: 'no_defects',
                orderable: false,
            },
            {
                data: 'passport',
                orderable: false,
            },
            {
                data: 'manual',
                orderable: false,
            },
            {
                data: 'documents',
                orderable: false,
            },
            {
                data: 'performance',
                orderable: false,
            },
            {
                data: 'comment',
                orderable: false,
            },
            {
                data: 'responsible_user',
                orderable: true,
            },
            {
                data: 'is_confirm',
                orderable: false,
                render: function (data, type, item) {
                    if ( item.is_confirm == 1 ) {
                        return 'Да'
                    } else if ( item.is_confirm == 0 ) {
                        return 'Нет'
                    } else {
                        return '--'
                    }
                }
            },
        ],
        language: dataTablesSettings.language,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Все"]],
        pageLength: 25,
        order: [[ 2, 'desc' ]],
        dom: 'frt<"bottom"lip>'
    })

    journalDataTable.columns().every(function() {
        let timeout
        $(this.header()).closest('thead').find('.search:eq('+ this.index() +')').on('keyup change clear', function() {
            clearTimeout(timeout)
            const searchValue = this.value
            timeout = setTimeout(function() {  
                journalDataTable
                    .column($(this).parent().index())
                    .search(searchValue)
                    .draw()
            }.bind(this), 1000)
        })
    })

    $('.filter').on('change', function () {
        journalDataTable.ajax.reload()
    })

    function reportWindowSize() {
        journalDataTable
            .columns.adjust()
    }

    window.onresize = reportWindowSize

    $('.filter-btn-reset').on('click', function () {
        const cleanPath = window.location.pathname.replace(/\/\d+$/, '')
        window.location.href = cleanPath
    })

    $('#add-moving-modal-form').on('submit', function (e) {
        const $form = $(this)
        const $button = $form.find(`button[type="submit"]`)
        const btnHtml = $button.html()

        $button.html(`<i class="fa-solid fa-arrows-rotate spinner-animation"></i>`)
        $button.addClass('disabled')

        $.ajax({
            url: "/ulab/oborud/addOborudMovingAjax/",
            data: $form.serialize(),
            dataType: "json",
            async: true,
            method: "POST",
            complete: function () {
                journalDataTable.ajax.reload()

                $button.html(btnHtml)
                $button.removeClass('disabled')

                // возвращаем значения по умолчанию
                $form.find('input[type="text"]').val('')
                $form.find('input[type="checkbox"]').prop('checked', false)
                $form.find('textarea').val('')
                $form.find('select').val(null).trigger('change')
                $form.find('#place-moving-block').show()

                $.magnificPopup.close()
            }
        })

        return false
    })
})