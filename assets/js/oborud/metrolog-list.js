$(function () {
    let journalsDataTable = {
        'journal_end' : null,
        'journal_close_end' : null,
        'journal_need_check' : null,
    }

    const columns = [
        {
            data: 'stage',
            orderable: false,
            render: function (data, type, item) {
                return `<div class="stage rounded ${item['bgStage']}" title="${item['titleStage']}"></div>`
            }
        },
        {
            data: 'OBJECT',
            render: function (data, type, item) {
                return `<a href="/ulab/oborud/edit/${item['ID']}">${item['OBJECT']}</a>`
            }
        },
        {
            data: 'TYPE_OBORUD',
            render: $.fn.dataTable.render.ellipsis(50, true)
        },
        {
            data: 'FACTORY_NUMBER',
            render: $.fn.dataTable.render.ellipsis(50, true)
        },
        {
            data: 'REG_NUM',
        },
        {
            data: 'date_end',
        },
    ]
    let dataTableSettings = {
        bAutoWidth: false,
        autoWidth: false,
        fixedColumns: false,
        processing: true,
        serverSide: true,
        bSortCellsTop: true,
        scrollX: true,
        fixedHeader: false,
        colReorder: true,
        ajax: {},
        columns: columns,
        language: dataTablesSettings.language,
        lengthMenu: [[10, 25, 50, 100, -1], [10,25, 50, 100, "Все"]],
        pageLength: 25,
        order: [[ 5, "desc" ]],
        dom: 'fBrt<"bottom"lip>',
        buttons: [],
        initComplete: function (settings) {
            let api = this.api()
            api.columns().every(function () {
                let timeout
                $(this.header()).closest('thead').find('.search:eq('+ this.index() +')').on( 'input', function () {
                    clearTimeout(timeout)
                    const searchValue = this.value
                    timeout = setTimeout(function () {
                        api
                            .column($(this).parent().index())
                            .search(searchValue)
                            .draw()
                    }.bind(this), 1000)
                })
            })
        }
    }

    const journals = {
        'journal_end' : 'poverka_alarm',
        'journal_close_end' : 'poverka',
        'journal_need_check' : 'unchecked',
    }

    $.each(journals, function(i, item) {
        dataTableSettings.ajax = {
            type: 'POST',
            data: function (d) {
                d.lab = $('#selectLab option:selected').val()
                d.stage = item
            },
            url : '/ulab/oborud/getListProcessingAjax/',
            dataSrc: function (json) {
                return json.data
            },
        }

        journalsDataTable[i] = $(`#${i}`).DataTable(dataTableSettings)
    })

    $('.filter').on('change', function () {
        $.each(journalsDataTable, function(i, item) {
            if ( item !== null ) {
                item.ajax.reload()
            }
        })
    })

    function reportWindowSize() {
        $.each(journalsDataTable, function(i, item) {
            if ( item !== null ) {
                item.columns.adjust()
            }
        })
    }

    window.onresize = reportWindowSize

    $('.filter-btn-reset').on('click', function () {
        location.reload()
    })
})
