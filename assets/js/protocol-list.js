$(function ($) {
    let $journal = $('#journal_protocol')

    /*journal requests*/
    let journalDataTable = $journal.DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            type : 'POST',
            data: function ( d ) {
                d.dateStart = $('#inputDateStart').val()
                d.dateEnd = $('#inputDateEnd').val()
                d.stage = $('#selectStage option:selected').val()
                d.lab = $('#selectLab option:selected').val()
            },
            url : '/ulab/protocol/getListProcessingAjax/',
            dataSrc: function (json) {
                return json.data
            }
        },
        columns: [
            {
                data: 'stage',
                orderable: false,
                render: function (data, type, item) {
                    return `<div class="stage rounded ${item['bgStage']}" title="${item['titleStage']}"></div>`
                }
            },
            {
                data: 'ATTESTAT',
                orderable: false
            },
            {
                data: 'NUMBER_AND_YEAR',
                render: function (data, type, item) {
                    if ( item['is_non_actual'] == 1 ) {
                        return `<span class="text-red" title="Протокол неактуален">${item['NUMBER_AND_YEAR']}</span>`
                    }
                    return `${item['NUMBER_AND_YEAR']}`
                }
            },
            {
                data: 'DATE'
            },
            {
                data: 'COMPANY_TITLE',
                render: $.fn.dataTable.render.ellipsis(45, true)
            },
            {
                data: 'MATERIAL',
                render: $.fn.dataTable.render.ellipsis(40, true)
            },
            {
                data: 'ASSIGNED',
                render: $.fn.dataTable.render.ellipsis(40, true)
            },
            {
                data: 'requestTitle',
                class: 'text-nowrap',
                render: function (data, type, item) {
                    return `<a class="request-link"
                           href="/ulab/request/card/${item.ID_Z}" >
                           ${item['REQUEST_TITLE']}
                        </a>`
                }
            },
            {
                data: 'tz',
                render: function (data, type, item) {
                    return `<a class="number-tz" href="/ulab/requirement/card_new/${item['b_tz_id']}" >
                                ${item['b_tz_id']}
                            </a>`
                }
            },
            {
                data: 'linkName',
                orderable: false,
                render: function (data, type, item) {
                    if ( item['PROTOCOL_OUTSIDE_LIS'] == 1 ) {
                        return 'Вне ЛИС'
                    }
                    return `<a class="results-link"
                               href="/ulab/result/card_oati/${item['ID_Z']}?protocol_id=${item['protocol_id']}" >
                               Открыть
                            </a>`
                }
            },
            {
                data: 'DOC',
                render: function (data, type, item) {
                    if ( item['DOC'] !== '' ) {
                        return `<a class="results-link"
                               href="${item['DOC']}" >
                               PDF
                            </a>`
                    }

                    return ''
                },
                orderable: false
            },
            {
                data: 'USER_HISTORY',
                class: 'text-nowrap',
                render: $.fn.dataTable.render.ellipsis(25, true)
            }
        ],
        language: dataTablesSettings.language,
        lengthMenu: [[10, 25, 50, 100, -1], [10,25, 50, 100, "Все"]],
        pageLength: 25,
        order: [[ 2, "desc" ]],
        colReorder: true,
        dom: 'frtB<"bottom"lip>',
        buttons: dataTablesSettings.buttonPrint,
        bSortCellsTop: true,
        scrollX:       true,
        fixedHeader:   true,
    });

    journalDataTable.columns().every(function () {
        let timeout
        $(this.header()).closest('thead').find('.search:eq('+ this.index() +')').on('keyup change clear', function () {
            clearTimeout(timeout)
            const searchValue = this.value
            timeout = setTimeout(function () {
                journalDataTable
                    .column($(this).parent().index())
                    .search(searchValue)
                    .draw()
            }.bind(this), 1000)
        })
    })

    /*journal filters*/
    $('.filter-btn-search').on('click', function () {
        $('#journal_filter').addClass('is-open')
        $('.filter-btn-search').hide()
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
        location.reload()
    })

    /*journal buttons*/
    let container = $('div.dataTables_scrollBody'),
        scroll = $journal.width()

    $('.btnRightTable, .arrowRight').hover(function() {
        container.animate(
            {
                scrollLeft: scroll
            },
            {
                duration: 4000, queue: false
            }
        )
    },
    function() {
        container.stop();
    })

    $('.btnLeftTable, .arrowLeft').hover(function() {
        container.animate(
            {
                scrollLeft: -scroll
            },
            {
                duration: 4000, queue: false
            }
        )
    },
    function() {
        container.stop();
    })

    $(document).scroll(function() {
        let positionScroll = $(window).scrollTop(),
            tableScrollBody = container.height()

        if (positionScroll > 265 && positionScroll < tableScrollBody) {
            $('.arrowRight').css('transform',`translateY(${positionScroll-260}px)`);
            $('.arrowLeft').css('transform',`translateY(${positionScroll-250}px)`);
        }
    })
})
