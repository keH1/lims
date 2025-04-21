$(function ($) {
    let journalDataTable = window.initDataTable('#journal_history', {
        ajax: {
            type : 'POST',
            data: function (d) {
                d.dateStart = $('#inputDateStart:visible').val()
                d.dateEnd = $('#inputDateEnd:visible').val()
            },
            url : '/ulab/history/getListProcessingAjax/',
            dataSrc: function (json) {
                return json.data
            },
        },
        columns: [
            {
                data: 'REQUEST'
            },
            {
                data: 'PROT_NUM',
            },
            {
                data: 'TZ_ID',
            },
            {
                data: 'DATE',
            },
            {
                data: 'TYPE',
                render: $.fn.dataTable.render.ellipsis(65)
            },
            {
                data: 'ASSIGNED',
            }
        ],
        language: dataTablesSettings.language,
        buttons: dataTablesSettings.buttonPrint,
        lengthMenu: [[10, 25, 50, 100, -1], [10,25, 50, 100, "Все"]],
        pageLength: 25,
        order: [[3, "desc"]],
        dom: 'frt<"bottom"lip>',
    })
    
    // window.adjustmentColumnsTable(journalDataTable)
    window.setupDataTableColumnSearch(journalDataTable);
    window.setupJournalFilters(journalDataTable);
})