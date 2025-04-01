$(function () {
    let $journal = $('#journal_gost')

    /*journal requests*/
    let journalDataTable = $journal.DataTable({
        bAutoWidth: false,
        autoWidth: false,
        fixedColumns: true,
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
            url : '/ulab/statistic/getJournalReportMethodAjax/',
            dataSrc: function (json) {
                console.log(json)
                return json.data
            }
        },
        columns: [
            {
                data: 'ACT_DATE'
            },
            {
                data: 'DATE',
            },
            {
                data: 'REQUEST_TITLE',
                render: function (data, type, item) {
                    return `<a href="/ulab/request/card/${item.ID_Z}">${item.REQUEST_TITLE}</a>`
                }
            },
            {
                data: 'NUMBER_AND_YEAR',
            },
            {
                data: 'TITLE'
            },
            {
                data: 'short_name',
            },
            {
                data: 'gost',
                render: function (data, type, item) {
                    return `<a href="/ulab/gost/edit/${item.id}">${item.reg_doc} ${item.clause}</a>`
                }
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
                data: 'duration_work',
            },
            {
                data: 'count_method'
            },
            {
                data: 'total',
                orderable: false,
            },
        ],
        language: dataTablesSettings.language,
        lengthMenu: [[10, 25, 50, 100, -1], [10,25, 50, 100, "Все"]],
        pageLength: 25,
        order: [[ 0, "desc" ]],
        colReorder: true,
        dom: 'frtB<"bottom"lip>',
        buttons: dataTablesSettings.buttonPrint,
        bSortCellsTop: true,
        scrollX:       false,
        fixedHeader:   false,
    });

    journalDataTable.columns().every( function () {
        $(this.header()).closest('thead').find('.search:eq('+ this.index() +')').on( 'keyup change clear', function () {
            journalDataTable
                .column( $(this).parent().index() )
                .search( this.value )
                .draw();
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
