$(function ($) {
    let journalGrain = $('#journal_grain').DataTable({
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
                    return `<a class="results-link" href="/ulab/grain/card/${item.ID}" target="_blank">${item.NAME}</a>`
                }
            }
        ],
        language: dataTablesSettings.language,
        lengthMenu: [[10, 25, 50, 100, -1], [10,25, 50, 100, "Все"]],
        pageLength: 25,
        dom: 'fBrt<"bottom"lip>',
        order: [[0, "asc"]],
        colReorder: true,
        dom: 'frtB<"bottom"lip>',
        buttons: dataTablesSettings.buttons,
        bSortCellsTop: true,
        // scrollX:       true,
        fixedHeader:   true
    })

    journalGrain.columns().every( function() {
        let timeout
        $(this.header()).closest('thead').find('.search:eq('+ this.index() +')').on('keyup change clear', function () {
            clearTimeout(timeout)
            const searchValue = this.value
            timeout = setTimeout(function () {
                journalGrain
                    .column( $(this).parent().index())
                    .search(searchValue)
                    .draw()
            }.bind(this), 1000)
        })
    })
    
    /*journal filters*/
    $('.filter-btn-search').on('click', function () {
        $('#journal_requests_filter').addClass('is-open')
        $('.filter-btn-search').hide()
    })

    $('.filter').on('change', function () {
        journalGrain.ajax.reload()
    })

    function reportWindowSize() {
        journalGrain
            .columns.adjust()
    }

    window.onresize = reportWindowSize
})