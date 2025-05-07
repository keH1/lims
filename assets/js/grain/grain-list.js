$(function ($) {
    let journalDataTable = window.initDataTable('#journal_grain', {
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
        dom: 'frt<"bottom"lip>',
    });

    // window.adjustmentColumnsTable(journalDataTable)
    window.setupDataTableColumnSearch(journalDataTable)
    window.setupJournalFilters(journalDataTable)
})