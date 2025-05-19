$(function ($) {
    let body = $('body'),
        $journal = $('#scales_journal')

    let precursorJournal = $journal.DataTable({
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
            type: 'POST',
            data: function (d) {
                d.idScale = $('.select-scale option:selected').val()
                d.month = $('.select-month').val()
                d.dateStart = $('#inputDateStart').val() || "0001-01-01";
                d.dateEnd = $('#inputDateEnd').val() || "9999-12-31";
            },
            url: '/ulab/scale/getListProcessingAjax/',
            dataSrc: function (json) {
                console.log(json)
                return json.data
            },
        },
        columns: [
            {
                data: 'results',
                orderable: false,
                render: function (data, type, item) {
                    if (item.results === false) {
                        return `<span class="cursor-pointer not-conformity" title="Не соответствует">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 text-danger" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                    </svg>
                                </span>`;
                    } else {
                        return `<span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 text-success" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                    </svg>
                                </span>`;
                    }
                }
            },
            // {
            //     data: 'number',
            //     orderable: false
            // },
            {
                data: 'date_calibration'
            },
            {
                data: 'scale_name',
            },
            {
                data: 'weight_name',
            },
            {
                data: 'mass_weight'
            },
            {
                data: 'weight_result'
            },
            {
                data: 'scale_error',
                render: function (data, type, item) {
                    return `±${item.scale_error}`
                }
            },
            {
                data: 'results',
                orderable: false,
                render: function (data, type, item) {
                    if (item.results === false) {
                        return `Не соответсвует`;
                    } else {
                        return `Соответствует`;
                    }
                }
            },
            {
                data: 'global_assigned_name'
            }

        ],
        // columnDefs: [{
        //     className: 'control',
        //     'orderable': false,
        // }],
        language: dataTablesSettings.language,
        lengthMenu: [[10, 25, 50, 100, -1], [10,25, 50, 100, "Все"]],
        pageLength: 25,
        order: [[ 1, "desc" ]],
        dom: 'frtB<"bottom"lip>',
        buttons: dataTablesSettings.buttonPrint,
    })

    precursorJournal
        .on('init.dt draw.dt', () => initTableScrollNavigation())

    precursorJournal.columns().every(function() {
        let timeout
        $(this.header()).closest('thead').find('.search:eq('+ this.index() +')').on('input', function() {
            clearTimeout(timeout)
            const searchValue = this.value
            timeout = setTimeout(function() {
                precursorJournal
                    .column($(this).parent().index())
                    .search(searchValue)
                    .draw()
            }.bind(this), 1000)
        })
    })

    /** modal */
    $('.popup-first').magnificPopup({
        items: {
            src: '#add-entry-modal-form-first',
            type: 'inline'
        },
        fixedContentPos: false,
        closeOnBgClick: false,
    })

    $('.filter').on('change', function () {
        precursorJournal.ajax.reload()
    })

    function reportWindowSize() {
        precursorJournal
            .columns.adjust()
    }

    window.onresize = reportWindowSize

    $('.filter-btn-reset').on('click', function () {
        location.reload()
    })

    body.on('change', '.scale', function () {
        let scaleId = $(this).val()
        let scaleError = $(this).parents('#add-entry-modal-form-first').find('.scale_error')

        if (scaleId == 448) {
            scaleError.val('0.01')
        } else if (scaleId == 825) {
            scaleError.val('0.06')
        } else if (scaleId == 833) {
            scaleError.val('0.01')
        } else if (scaleId == 399) {
            scaleError.val('0.0002')
        } else if (scaleId == 398) {
            scaleError.val('0.01')
        } else if (scaleId == 397) {
            scaleError.val('0.01')
        } else if (scaleId == 383) {
            scaleError.val('0.01')
        }
    })

    body.on('change', '.weight', function () {
        let weightId = $(this).val()
        let massWeight = $(this).parents('#add-entry-modal-form-first').find('.mass_weight')

        if (weightId == 401) {
            massWeight.val('100')
        } else if (weightId == 402) {
            massWeight.val('200')
        } else if (weightId == 403) {
            massWeight.val('1000')
        } else if (weightId == 400) {
            massWeight.val('100')
        }
    })

    // $('.auto-fill').on('click', function () {
    //     const fillModalForm = $('#auto-fill')

    //     $.magnificPopup.open({
    //         items: {
    //             src: fillModalForm,
    //             type: 'inline',
    //             fixedContentPos: false
    //         },
    //         closeOnBgClick: false,
    //     })
    // })

})
