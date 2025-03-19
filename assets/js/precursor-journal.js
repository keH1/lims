$(function ($) {
    /*recipe journal*/
    let precursorJournal = $('#precursor_journal').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            type: 'POST',
            data: function (d) {
                d.idWhichFilter = $('#inputIdWhichFilter').val()
                d.dateStart = $('#inputDateStart').val()
                d.dateEnd = $('#inputDateEnd').val()
            },
            url: '/ulab/precursor/getListProcessingAjax/',
            dataSrc: function (json) {
                return json.data
            },
        },
        columns: [
            {
                data: 'reactive_name',
                render: $.fn.dataTable.render.ellipsis(40, true)
            },
            {
                data: 'month_year_dateformat'
            },
            {
                data: 'quantity_begin_full'
            },
            {
                data: 'date_receive_dateformat'
            },
            {
                data: 'doc_name'
            },
            {
                data: 'quantity_receive_full'
            },
            {
                data: 'global_assigned_name_receive'
            },
            {
                data: 'quantity_remain_plus_receive_full'
            },
            {
                data: 'type'
            },
            {
                data: 'date_consume_dateformat'
            },
            {
                data: 'quantity_consume_full'
            },
            {
                data: 'quantity_consume_full'
            },
            {
                data: 'quantity_remain_month_full'
            },
            {
                data: 'quantity_actual_remain_end_full'
            },
            {
                data: 'global_assigned_name_remain'
            }

        ],

        columnDefs: [{
            className: 'control',
            /*'targets': */
            'orderable': false,
        }],
        language: dataTablesSettings.language,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Все"]],
        pageLength: 25,
        order: [],
        colReorder: true,
        dom: 'fBrt<"bottom"lip>',
        buttons: [
            {
                extend: 'colvis',
                titleAttr: 'Выбрать'
            },
            {
                extend: 'copy',
                titleAttr: 'Копировать',
                exportOptions: {
                    modifier: {
                        page: 'current'
                    }
                }
            },
            {
                extend: 'excel',
                titleAttr: 'excel',
                exportOptions: {
                    modifier: {
                        page: 'current'
                    }
                }
            },
            {
                extend: 'print',
                titleAttr: 'Печать',
                exportOptions: {
                    modifier: {
                        page: 'current'
                    }
                }
            }
        ],
        bSortCellsTop: true,
        scrollX: true,
        fixedHeader: false,

    })
    precursorJournal.columns().every(function () {
        $(this.header()).closest('thead').find('.search:eq(' + this.index() + ')').on('keyup change clear', function () {
            precursorJournal
                .column($(this).parent().index())
                .search(this.value)
                .draw()
        })
    })
    /*journal buttons*/
    let container = $('div.dataTables_scrollBody'),
        scroll = $('#precursor_journal').width()


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
    $("body").on('change', '.select-reactive', function () {
        let unit = $('.select-reactive option:selected').data('unit')
        let lastdate = $('.select-reactive option:selected').data('lastdate').slice(0, 7)
        $('.quantity-reactive').html(unit);
        $('.select-month').val(lastdate);
    })

    /** modal */
    $('.popup-first').magnificPopup({
        items: {
            src: '#add-entry-modal-form-first',
            type: 'inline'
        },
        fixedContentPos: false
    })
    $('.popup-second').magnificPopup({
        items: {
            src: '#add-entry-modal-form-second',
            type: 'inline'
        },
        fixedContentPos: false
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
        precursorJournal
            .columns.adjust()
    }

    window.onresize = reportWindowSize

    $('.filter-btn-reset').on('click', function () {
        location.reload()
    })

})
