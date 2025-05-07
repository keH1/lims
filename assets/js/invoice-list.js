$(function ($) {
    let $journal = $('#journal_invoice')

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
                d.dateStart = $('#inputDateStart').val() || "0001-01-01";   
                d.dateEnd = $('#inputDateEnd').val() || "9999-12-31";
                d.lab = $('#selectLab option:selected').val()
                d.stage = $('#selectStage option:selected').val()
            },
            url : '/ulab/invoice/getListAjax/',
            dataSrc: function (json) {
                return json.data
            }
        },
        columns: [
            {
                data: 'STAGE_NUMBER',
                orderable: false,
                render: function (data, type, item) {
                    return `<div class="stage rounded ${item['color']}" title="${item['title']}"></div>`
                }
            },
            {
                data: 'ACCOUNT'
            },
            {
                data: 'DATE'
            },
            {
                data: 'price_discount'
            },
            {
                data: 'COMPANY_TITLE'
            },
            {
                data: 'MATERIAL',
                render: $.fn.dataTable.render.ellipsis(70, true)
            },
            {
                data: 'ASSIGNED'
            },
            {
                data: 'DOGOVOR_TABLE'
            },
            {
                data: 'REQUEST_TITLE',
                class: 'text-nowrap',
                render: function (data, type, item) {
                    return `<a class="request-link"
                           href="/ulab/request/card/${item.ID_Z}" >
                           ${item['REQUEST_TITLE']}
                        </a>`
                }
            },
            {
                data: 'ACT_VR'
            },
            {
                data: 'DATE_ACT_VR'
            },
            {
                data: 'SEND_DATE_ACT_VR',
            }
        ],
        language: dataTablesSettings.language,
        lengthMenu: [[10, 25, 50, 100, -1], [10,25, 50, 100, "Все"]],
        pageLength: 25,
        order: [[ 1, "desc" ]],
        dom: 'frtB<"bottom"lip>',
        buttons: dataTablesSettings.buttonPrint,
        columnDefs: [
            { 
                targets: 1,
                width: '80px'
            }
        ],
    });

    journalDataTable
        .on('init.dt draw.dt', () => initTableScrollNavigation())

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

    /*journal filters*/
    $('.filter-btn-search').on('click', function () {
        $('#journal_filter').addClass('is-open')
        $('.filter-btn-search').hide()
    })

    $('.filter').on('change', function () {
        journalDataTable.ajax.reload()
    })

    $('.filter-btn-reset').on('click', function () {
        location.reload()
    })
})