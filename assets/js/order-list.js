$(function ($) {
    let $journal = $('#journal_order')

    /*journal requests*/
    let journalDataTable = $journal.DataTable({
        bAutoWidth: false,
        autoWidth: false,
        fixedColumns: false,
        processing: true,
        serverSide: true,
        ajax: {
            type : 'POST',
            data: function ( d ) {
                d.dateStart = $('#inputDateStart').val() || "0001-01-01"
                d.dateEnd = $('#inputDateEnd').val() || "9999-12-31"
                d.everywhere = $('#filter_everywhere').val()
            },
            url : '/ulab/order/getListProcessingAjax/',
            dataSrc: function (json) {
                return json.data
            },
        },
        columns: [
            {
                data: 'stage',
                width: '52px',
                orderable: false,
                render: function (data, type, item) {
                    return `<div class="stage rounded ${item['bgStage']}" title="${item['titleStage']}"></div>`
                }
            },
            {
                data: 'NUMBER',
                width: '100px',
                render: function (data, type, item) {
                    return `<a class="results-link"
                               href="/ulab/order/card/${item['d_id']}" >
                               ${item['NUMBER']}
                            </a>`
                }
            },
            {
                data: 'DATE',
                width: '100px'
            },
            {
                data: 'CONTRACT_TYPE',
            },
            {
                data: 'COMPANY_TITLE',
                render: $.fn.dataTable.render.ellipsis(45, true)
            },
            {
                data: 'linkName',
                className: 'text-center text-wrap',
                width: '150px',
                orderable: false,
                render: function (data, type, item) {
                    if ( item['order_pdf'] === '' ) {
                        return 'Не сформирован';
                    } else {
                        return `<a class="results-link"
                               href="/protocol_generator/archive_dog/${item['order_pdf']}" >
                               Скачать
                            </a>`
                    }
                }
            },
            {
                data: 'linkName2',
                className: 'text-center',
                width: '150px',
                orderable: false,
                render: function (data, type, item) {
                    if ( item['PDF'] === '' ) {
                        return 'Не загружена';
                    } else {
                        return `<a class="results-link"
                               href="/pdf/${item['PDF']}" >
                               Скачать
                            </a>`
                    }
                }
            },
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
        fixedHeader:   false,
    });

    journalDataTable
        .on('init.dt draw.dt', () => initTableScrollNavigation())

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
})