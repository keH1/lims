$(function ($) {
    let $journal = $('#journal_revise')

    /*journal requests*/
    let journalDataTable = $journal.DataTable({
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
            },
            url : '/ulab/order/getReviseDataJournalAjax/',
            dataSrc: function (json) {
                console.log(json)
                return json.data
            }
        },
        columns: [
            {
                data: 'NUMBER',
                width: '100px',
                render: function (data, type, item) {
                    return `<a class="results-link"
                               href="/ulab/order/card/${item['ID']}" target="_blank">
                               ${item['NUMBER']}
                            </a>`
                }
            },
            {
                data: 'COMPANY_TITLE',
            },
            {
                data: 'SUM_ALL_PRICE',
                orderable: false,
            },
            {
                data: 'SUM_NO_PAYMENT',
                orderable: false,
            },
            {
                data: 'COUNT_REQUEST',
                orderable: false,
            },
            {
                data: 'COUNT_REQUEST_NO_PAYMENT',
                orderable: false,
            },
            {
                data: 'btn',
                width: '100px',
                orderable: false,
                render: function (data, type, item) {
                    return `<a href="#" class="btn btn-primary">Акт сверки</a>`
                }
            },
        ],
        language: dataTablesSettings.language,
        lengthMenu: [[10, 25, 50, 100, -1], [10,25, 50, 100, "Все"]],
        pageLength: 25,
        order: [[ 0, "asc" ]],
        dom: 'frt<"bottom"lip>',
        buttons: dataTablesSettings.buttons,
    })

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

    $('.filter').on('change', function () {
        journalDataTable.ajax.reload()
        journalDataTable.draw()
    })
})