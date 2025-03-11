$(function ($) {
    let $journal = $('#journal_probe')

    /*journal requests*/
    let journalDataTable = $journal.DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            type : 'POST',
            data: function ( d ) {
                d.dateStart = $('#inputDateStart').val()
                d.dateEnd = $('#inputDateEnd').val()
                d.lab = $('#selectLab option:selected').val()
                d.everywhere = $('#filter_everywhere').val()
            },
            url : '/ulab/probe/getListAjax/',
            dataSrc: function (json) {
                return json.data
            }
        },
        columns: [
            {
                data: 'NUM_ACT_TABLE'
            },
            {
                data: 'CIPHER',
                render: $.fn.dataTable.render.ellipsis(40, true)
            },
            {
                data: 'DOGOVOR_TABLE'
            },
            {
                data: 'DATE_ACT'
            },
            {
                data: 'COMPANY_TITLE'
            },
            {
                data: 'MATERIAL',
                render: $.fn.dataTable.render.ellipsis(40, true)
            },
            {
                data: 'ASSIGNED'
            },
            {
                data: 'REQUEST_TITLE',
                class: 'text-nowrap',
                render: function (data, type, item) {
                    return `<a class="request-link"
                           href="/ulab/request/card/${item.ID_Z}" target="_blank">
                           ${item['REQUEST_TITLE']}
                        </a>`
                }
            },
            {
                data: 'LAB'
            },
            {
                data: 'PROTOCOLS',
                orderable: false,
                render: function (data, type, item) {
                    let dataProtocol = `<div class="d-flex flex-column">`

                    if (item['PROTOCOLS']) {
                        for (const val of item['PROTOCOLS']) {

                            for (const file of val['FILES']) {
                                if (file.indexOf('.pdf') !== -1) {
                                    dataProtocol += `<a class="protocol-link" 
                                                        href="/protocol_generator/archive/${item['b_id']}${val['YEAR']}/${val['ID']}/${file}"
                                                        target="_blank">
                                                        ${val['NUMBER_AND_YEAR']}
                                                    </a>`
                                }
                            }

                            if (val['PROTOCOL_OUTSIDE_LIS'] && val['PDF']) {
                                dataProtocol += `<a class="protocol-link" href="/pdf/${val['ID']}/${val['PDF']}" target='_blank'
                                                    target="_blank">
                                                    ${val['NUMBER_AND_YEAR']}
                                                </a>`
                            }
                        }
                    } else {
                        if(item['NO_BITRIX']) {
                            dataProtocol += `<a class="protocol-link" href="/pdf/${item['PDF']}" target='_blank'>
                                                ${item['PDF'] && item['NUM_P_TABLE'] ? item['NUM_P_TABLE'] : ''}
                                            </a>`
                        } else if (item['b_actual_ver']) {
                            dataProtocol += `<a class="protocol-link" href="/protocol_generator/archive/${item['b_id']}${item['YEAR_ACT']}/${item['b_actual_ver']}.docx?1&1" target='_blank'>
                                                ${item['RESULTS'] && item['NUM_P_TABLE'] ? item['NUM_P_TABLE'] : ''}
                                            </a>`
                        }
                    }
                    dataProtocol += `</div>`

                    return dataProtocol
                },
            },
        ],
        language: dataTablesSettings.language,
        lengthMenu: [[10, 25, 50, 100, -1], [10,25, 50, 100, "Все"]],
        pageLength: 25,
        order: [[ 0, "desc" ]],
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
        journalDataTable.draw()
    })

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

    $('body').on('click', '.accept_probe', function () {
        console.log($(this))
    })


})
