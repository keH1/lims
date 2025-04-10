$(function ($) {
    let $journal = $('#journal_grain')

    let journalDataTable = $journal.DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            type : 'POST',
            url : '/ulab/grain/getListProcessingAjax/',
            dataSrc: function (json) {
                return json.data;
            },
        },
        columns: [
            {
                data: 'material_name',
                render: function (data, type, item) {
                    return `<a class="results-link" href="/ulab/grain/card/${item.ID}" >${item.NAME}</a>`
                }
            }
        ],
        language: dataTablesSettings.language,
        lengthMenu: [[10, 25, 50, 100, -1], [10,25, 50, 100, "Все"]],
        pageLength: 25,
        order: [[0, "asc"]],
        colReorder: true,
        dom: 'frt<"bottom"lip>',
        bSortCellsTop: true,
        fixedHeader: true
    });

    journalDataTable.columns().every(function() {
        let timeout
        $(this.header()).closest('thead').find('.search:eq('+ this.index() +')').on('keyup change clear', function() {
            clearTimeout(timeout)
            const searchValue = this.value
            timeout = setTimeout(function() {
                journalDataTable
                    .column( $(this).parent().index())
                    .search(searchValue)
                    .draw()
            }.bind(this), 1000)
        })
    })
    
    $('.filter-btn-search').on('click', function () {
        $('#journal_requests_filter').addClass('is-open')
        $('.filter-btn-search').hide()
    });

    $('.filter').on('change', function () {
        journalDataTable.ajax.reload()
    });
})