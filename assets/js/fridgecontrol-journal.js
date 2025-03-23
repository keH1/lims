$(function ($) {
    /*recipe journal*/
    let precursorJournal = $('#fridgecontrol_journal').DataTable({
        processing: true,
        serverSide: true,
        bAutoWidth: false,
        autoWidth: false,
        fixedColumns: false,
        ajax: {
            type: 'POST',
            data: function (d) {
                d.idWhichFilter = $('#inputIdWhichFilter').val()
                d.dateStart = $('#inputDateStart').val()
                d.dateEnd = $('#inputDateEnd').val()
            },
            url: '/ulab/fridgecontrol/getListProcessingAjax/',
            dataSrc: function (json) {
                console.log(json);
                return json.data
            },
        },
        columns: [
            {
                data: 'results',
                orderable: false,
                render: function (data, type, item) {
                    if (item.conclusion == "Не cоответствует") {
                        return `<span class="cursor-pointer not-conformity" title="Не соответствует">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 text-danger" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                        </svg>
                                    </span>`;
                    } else if (item.conclusion == "Соответствует") {
                        return `<span class="cursor-pointer not-conformity" title="Соответствует">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 text-success" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                        </svg>
                                    </span>`;
                    } else {
                        return ``
                    }
                }
            },
            {
                data: 'date_time_dateformat'
            },
            {
                data: 'name',
                render: $.fn.dataTable.render.ellipsis(64, true)},
            {
                data: 'temperature',
                width: 100,
                render: function (data, type, item) {
                    if (item.conclusion == "Не cоответствует") {
                        return `<div class="text-danger">` + data + `</div>`
                    } else return data
                }
            },
            {
                data: 'range_full'
            },
            {
                data: 'conclusion'
            },
            {
                data: 'global_assigned_name'
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
        dom: 'frtB<"bottom"lip>',
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

    $('.auto-fill').on('click', function () {
        const fillModalForm = $('#auto-fill')

        $.magnificPopup.open({
            items: {
                src: fillModalForm,
                type: 'inline',
                fixedContentPos: false
            },
            closeOnBgClick: false,
        })
    })

})
