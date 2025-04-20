$(function () {
    let $journal = $('#journal_gost')

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
        colReorder: false,
        ajax: {
            type : 'POST',
            data: function ( d ) {
            },
            url : '/ulab/normDocGost/getJournalAjax/',
            dataSrc: function (json) {
                return json.data
            }
        },
        columns: [
            {
                data: 'stage',
                orderable: false,
                render: function (data, type, item) {
                    if ( item.is_confirm == 1 ) {
                        return `<span class="text-green" title="Методика подтверждена"><i class="fa-regular fa-circle-check"></i></span>`
                    } else {
                        return `<span class="text-red" title="Методика не подтверждена"><i class="fa-regular fa-circle-xmark"></i></span>`
                    }
                }
            },
            {
                data: 'reg_doc',
                render: function (data, type, item) {
                    return `<a href="/ulab/normDocGost/edit/${item.gost_id}">${item.reg_doc}</a>`
                }
            },
            {
                data: 'description',
                render: $.fn.dataTable.render.ellipsis(32, true)
            },
            {
                data: 'year',
            },
            {
                data: 'materials',
                render: $.fn.dataTable.render.ellipsis(32, true)
            },
            {
                data: 'name',
                render: function (data, type, item) {
                    if ( item.method_id === null ) {
                        return ''
                    }

                    return `<a href="/ulab/normDocGost/method/${item.method_id}">${item.name}</a>`
                }
            },
            {
                data: 'clause'
            },
        ],
        language: dataTablesSettings.language,
        lengthMenu: [[10, 25, 50, 100, -1], [10,25, 50, 100, "Все"]],
        pageLength: 25,
        order: [[ 1, "desc" ]],
        dom: 'frtB<"bottom"lip>',
        buttons: dataTablesSettings.buttonPrint,
    });

    journalDataTable
        .on('init.dt draw.dt', () => initTableScrollNavigation())

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
    })

    function reportWindowSize() {
        journalDataTable
            .columns.adjust()
    }

    window.onresize = reportWindowSize
})