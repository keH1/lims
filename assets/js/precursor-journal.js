$(function ($) {
    $journal = $('#precursor_journal');

    /*recipe journal*/
    let precursorJournal = $journal.DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            type: 'POST',
            data: function (d) {
                d.idWhichFilter = $('#inputIdWhichFilter').val()
                d.dateStart = $('#inputDateStart').val()
                d.dateEnd = $('#inputDateEnd').val()
            },
            url: '/ulab/precursor/getListProcessingAjax/',
            dataSrc: function (json) {
                return json.data
            },
        },
        columns: [
            {
                data: 'reactive_name',
                render: $.fn.dataTable.render.ellipsis(40, true)
            },
            {
                data: 'month_year_dateformat'
            },
            {
                data: 'quantity_begin_full'
            },
            {
                data: 'date_receive_dateformat'
            },
            {
                data: 'doc_name'
            },
            {
                data: 'quantity_receive_full'
            },
            {
                data: 'global_assigned_name_receive'
            },
            {
                data: 'quantity_remain_plus_receive_full'
            },
            {
                data: 'type'
            },
            {
                data: 'date_consume_dateformat'
            },
            {
                data: 'quantity_consume_full'
            },
            {
                data: 'quantity_consume_full'
            },
            {
                data: 'quantity_remain_month_full'
            },
            {
                data: 'quantity_actual_remain_end_full'
            },
            {
                data: 'global_assigned_name_remain'
            }

        ],

        columnDefs: [{
            className: 'control',
            /*'targets': */
            'orderable': false,
        }],
        language: dataTablesSettings.language,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Все"]],
        pageLength: 25,
        order: [],
        colReorder: true,
        dom: 'frt<"bottom"lip>',
        bSortCellsTop: true,
        scrollX: true,
        fixedHeader: false,

    })

    precursorJournal
        .on('init.dt draw.dt', () => initTableScrollNavigation())

    precursorJournal.columns().every(function() {
        let timeout
        $(this.header()).closest('thead').find('.search:eq(' + this.index() + ')').on('keyup change clear', function() {
            clearTimeout(timeout)
            const searchValue = this.value
            timeout = setTimeout(function() {
                precursorJournal
                    .column($(this).parent().index())
                    .search(searchValue)
                    .draw()
            }.bind(this), 1000)
        })
    })

    /** modal */
    $('.popup-first').magnificPopup({
        items: {
            src: '#add-entry-modal-form-first',
            type: 'inline'
        },
        fixedContentPos: false,
        closeOnBgClick: false,
    })
    $('.popup-second').magnificPopup({
        items: {
            src: '#add-entry-modal-form-second',
            type: 'inline'
        },
        fixedContentPos: false,
        closeOnBgClick: false,
    })

    /** journal filters */
    $('.filter-btn-search').on('click', function () {
        $('#journal_requests_filter').addClass('is-open')
        $('.filter-btn-search').hide()
    })

    $('.filter').on('change', function () {
        precursorJournal.ajax.reload()
    })

    function reportWindowSize() {
        precursorJournal
            .columns.adjust()
    }

    window.onresize = reportWindowSize

    $('.filter-btn-reset').on('click', function () {
        location.reload()
    })

})
