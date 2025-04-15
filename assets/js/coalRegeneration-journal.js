$(function ($) {

    let body = $('body')
    /*recipe journal*/
    let precursorJournal = $('#coal_journal').DataTable({
        processing: true,
        serverSide: true,
        bAutoWidth: false,
        autoWidth: false,
        fixedColumns: false,
        ajax: {
            type: 'POST',
            data: function (d) {
                d.idCoal = $('.select-coal option:selected').val()
                d.month = $('.select-month').val()
                d.dateStart = $('#inputDateStart').val()
                d.dateEnd = $('#inputDateEnd').val()
            },
            url: '/ulab/coal/getListProcessingAjax/',
            dataSrc: function (json) {
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
                    } else if (item.results === true) {
                        return `<span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 text-success" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                    </svg>
                                </span>`;
                    } else {
                        return '';
                    }
                }
            },
            {
                data: 'date_regeneration_end',
            },
            {
                data: 'type_bdb',
            },
            {
                data: 'eb_date',
            },
            {
                data: 'eb_i1',
                orderable: false
            },
            {
                data: 'eb_i2',
                orderable: false
            },
            {
                data: 'eb_i3',
                orderable: false
            },
            {
                data: 'eb_t1',
                orderable: false
            },
            {
                data: 'eb_t2',
                orderable: false
            },
            {
                data: 'eb_t3',
                orderable: false
            },
            {
                data: 'eb_s1',
                orderable: false
            },
            {
                data: 'eb_s2',
                orderable: false
            },
            {
                data: 'eb_s3',
                orderable: false
            },
            {
                data: 'eb_average',
                orderable: false
            },
            {
                data: 'fb_date',
            },
            {
                data: 'fb_i1',
                orderable: false
            },
            {
                data: 'fb_i2',
                orderable: false
            },
            {
                data: 'fb_i3',
                orderable: false
            },
            {
                data: 'fb_t1',
                orderable: false
            },
            {
                data: 'fb_t2',
                orderable: false
            },
            {
                data: 'fb_t3',
                orderable: false
            },
            {
                data: 'fb_s1',
                orderable: false
            },
            {
                data: 'fb_s2',
                orderable: false
            },
            {
                data: 'fb_s3',
                orderable: false
            },
            {
                data: 'fb_average',
                orderable: false
            },
            {
                data: 'A_b',
                orderable: false
            },
            {
                data: 'range_A_b',
                orderable: false,
                render: function (data, type, item) {
                    return 'меньше или равно 1.9'
                }
            },
            {
                data: 'results',
                render: function (data, type, item) {
                    if (item.results === false) {
                        return `Не соответсвует`;
                    } else if (item.results === true) {
                        return `Соответствует`;
                    } else {
                        return '';
                    }
                }
            },
            {
                data: 'global_assigned_name',
            }

        ],

        // columnDefs: [{
        //     className: 'control',
        //     /*'targets': */
        //     'orderable': false,
        // }],
        language: dataTablesSettings.language,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Все"]],
        pageLength: 25,
        order: [],
        colReorder: false,
        dom: 'fBrt<"bottom"lip>',
        buttons: [],
        bSortCellsTop: true,
        scrollX: true,
        fixedHeader: false,

    })

    precursorJournal.columns().every( function () {
        $(this.header()).closest('thead').find('.search:eq('+ this.index() +')').on( 'keyup change clear', function () {
            precursorJournal
                .column( $(this).parent().index() )
                .search( this.value )
                .draw();
        })
    })


    /*journal buttons*/
    let container = $('div.dataTables_scrollBody'),
        scroll = $('#fridgecontrol_journal').width()


    let $body = $("body")
    let $containerScroll = $body.find('.dataTables_scroll')
    let $thead = $('.journal thead tr:first-child')

    $(document).scroll(function () {
        let positionScroll = $(window).scrollTop(),
            tableScrollBody = container.height(),
            positionTop = $containerScroll.offset().top

        if (positionScroll >= positionTop) {
            $thead.attr('style', 'position:fixed;top:0;z-index:99')
        } else {
            $thead.attr('style', '')
        }

        if (positionScroll > 265 && positionScroll < tableScrollBody) {
            $('.arrowRight').css('transform', `translateY(${positionScroll - 260}px)`)
            $('.arrowLeft').css('transform', `translateY(${positionScroll - 250}px)`)
        }
    })




    /** modal */
    $('.popup-first').magnificPopup({
        items: {
            src: '#add-entry-modal-form-first',
            type: 'inline'
        },
        fixedContentPos: false,
        closeOnBgClick: false
    })

    $('.popup-two').magnificPopup({
        items: {
            src: '#add-entry-modal-form-two',
            type: 'inline'
        },
        fixedContentPos: false,
        closeOnBgClick: false
    })

    $('.popup-three').magnificPopup({
        items: {
            src: '#add-entry-modal-form-three',
            type: 'inline'
        },
        fixedContentPos: false,
        closeOnBgClick: false
    })


    /** journal filters */
    $('.filter-btn-search').on('click', function () {
        $('#journal_requests_filter').addClass('is-open')
        $('.filter-btn-search').hide()
    })

    $('.filter').on('change', function () {
        precursorJournal.ajax.reload()
    })

    function reportWindowSize() {
        journalDataTable
            .columns.adjust()
    }

    window.onresize = reportWindowSize

    $('.filter-btn-reset').on('click', function () {
        location.reload()
    })

    body.on('change', '.coal', function () {
        let coalId = $(this).val()
        let coalError = $(this).parents('#add-entry-modal-form-first').find('.coal_error')

        if (coalId == 448) {
            coalError.val('0.01')
        } else if (coalId == 825) {
            coalError.val('0.06')
        } else if (coalId == 833) {
            coalError.val('0.01')
        } else if (coalId == 399) {
            coalError.val('0.0002')
        } else if (coalId == 398) {
            coalError.val('0.01')
        } else if (coalId == 397) {
            coalError.val('0.01')
        } else if (coalId == 383) {
            coalError.val('0.01')
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

})
