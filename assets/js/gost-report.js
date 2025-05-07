$(function () {
    let $journal = $('#journal_gost')

    let journalDataTable = $journal.DataTable({
        retrieve: true,
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
                d.dateStart = $('#inputDateStart').val() || "0001-01-01"
                d.dateEnd = $('#inputDateEnd').val() || "9999-12-31"
                d.stage = $('#selectStage option:selected').val()
                d.lab = $('#selectLab option:selected').val()
            },
            url : '/ulab/gost/getJournalReportAjax/',
            dataSrc: function (json) {
                return json.data
            }
        },
        columns: [
            {
                data: 'reg_doc',
                render: function (data, type, item) {
                    return `<a href="/ulab/gost/edit/${item.gost_id}">${item.reg_doc}</a>`
                }
            },
            {
                data: 'clause'
            },
            {
                data: 'materials',
                render: $.fn.dataTable.render.ellipsis(32, true)
            },
            {
                data: 'name',
                render: function (data, type, item) {
                    if ( item.method_id === null ) {
                        return `Методик не добавлено`
                    }
                    if ( item.mp_name === null ) {
                        return `<a href="/ulab/gost/method/${item.method_id}">${item.name}</a>`
                    }
                    return `<a href="/ulab/gost/method/${item.method_id}">${item.mp_name}</a>`
                }
            },
            {
                data: 'count_method'
            },
            {
                data: 'count_vlk',
                orderable: false,
            },
            {
                data: 'in_field',
                orderable: false,
                render: function (data, type, item) {
                    if (item['in_field'] == 1) {
                        return 'Да'
                    }
                    return 'Нет'
                }
            },
        ],
        language: dataTablesSettings.language,
        lengthMenu: [[10, 25, 50, 100, -1], [10,25, 50, 100, "Все"]],
        pageLength: 25,
        order: [[ 4, "desc" ]],
        dom: 'frtB<"bottom"lip>',
        buttons: dataTablesSettings.buttonPrint,
    });


    journalDataTable.columns().every(function() {
        let timeout
        $(this.header()).closest('thead').find('.search:eq('+ this.index() +')').on('input', function() {
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

    $('.filter-btn-search').on('click', function () {
        $('#journal_filter').addClass('is-open')
        $('.filter-btn-search').hide()
    })

    $('.filter').on('click', function () {
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