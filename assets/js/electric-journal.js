$(function ($) {
    let columnsMainTable = ["date", "name"]
    columnsMainTable.map(function (d) {
        return {data: d}
    });
    /*recipe journal*/
    let mainTable = $('#main_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            type: 'POST',
            data: function (d) {
                d.idWhichFilter = $('#inputIdWhichFilter').val()
                d.dateStart = $('#inputDateStart').val() || "0001-01-01"
                d.dateEnd = $('#inputDateEnd').val() || "9999-12-31"
            },
            url: '/ulab/electric/getListProcessingAjax/',
            dataSrc: function (json) {
                return json.data
            },
        },
        columns: [
            {
                data: 'results',
                orderable: false,
                render: function (data, type, item) {
                    if (item.conclusion == "Не cоответствует") {
                        return `<span class="not-conformity" title="Не соответствует">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 text-danger" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                        </svg>
                                    </span>`;
                    } else if (item.conclusion == "Соответствует") {
                        return `<span class="not-conformity" title="Соответствует">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 text-success" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                        </svg>
                                    </span>`;
                    } else {
                        return ``
                    }
                }
            },
            {
                data: 'date_dateformat',
                sort: 'desc'
            },
            {
                data: 'name',
                render: $.fn.dataTable.render.ellipsis(64, true)
            },
            {
                data: 'voltage_UA',
            },
            {
                data: 'range_UA',
                orderable: false,
                render: function (data, type, item) {
                    item.voltage_UA_min = parseFloat(item.voltage_UA_min).toFixed(1).replace(".",",")
                    item.voltage_UA_max = parseFloat(item.voltage_UA_max).toFixed(1).replace(".",",")
                    return `${item.voltage_UA_min} — ${item.voltage_UA_max}`
                }
            },
            {
                data: 'voltage_UB',
            },
            {
                data: 'range_UB',
                orderable: false,
                render: function (data, type, item) {
                    item.voltage_UB_min = parseFloat(item.voltage_UB_min).toFixed(1).replace(".",",")
                    item.voltage_UB_max = parseFloat(item.voltage_UB_max).toFixed(1).replace(".",",")
                    return `${item.voltage_UB_min} — ${item.voltage_UB_max}`
                }
            },
            {
                data: 'voltage_UC',
            },
            {
                data: 'range_UC',
                orderable: false,
                render: function (data, type, item) {
                    item.voltage_UC_min = parseFloat(item.voltage_UC_min).toFixed(1).replace(".",",")
                    item.voltage_UC_max = parseFloat(item.voltage_UC_max).toFixed(1).replace(".",",")
                    return `${item.voltage_UC_min} — ${item.voltage_UC_max}`
                }
            },
            {
                data: 'frequency',
            },
            {
                data: 'range_frequency',
                orderable: false,
                render: function (data, type, item) {
                    item.frequency_min = parseFloat(item.frequency_min).toFixed(1).replace(".",",")
                    item.frequency_max = parseFloat(item.frequency_max).toFixed(1).replace(".",",")
                    return `${item.frequency_min} — ${item.frequency_max}`
                }
            },
            {
                data: 'conclusion'
            },
            {
                data: 'global_assigned_name'
            },
        ],
        'columnDefs': [{
            'targets': [],
            'orderable': false,
        }],
        language: dataTablesSettings.language,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Все"]],
        pageLength: 25,
        order: [[1, "desc"]],
        colReorder: true,
        dom: 'frtB<"bottom"lip>',
        buttons: dataTablesSettings.buttonPrint,
        bSortCellsTop: true,
        scrollX: true,
        fixedHeader: false,
    })

    mainTable.columns().every(function() {
        let timeout
        $(this.header()).closest('thead').find('.search:eq(' + this.index() + ')').on('input', function() {
            clearTimeout(timeout)
            const searchValue = this.value
            timeout = setTimeout(function() {
                mainTable
                    .column($(this).parent().index())
                    .search(searchValue)
                    .draw()
            }.bind(this), 1000)
        })
    })

    /*journal buttons*/
    let container = $('div.dataTables_scrollBody'),
        scroll = $('#main_table').width()

    $('.btnRightTable, .arrowRight').hover(function () {
            container.animate(
                {
                    scrollLeft: scroll
                },
                {
                    duration: 4000, queue: false
                }
            )
        },
        function () {
            container.stop()
        })

    $('.btnLeftTable, .arrowLeft').hover(function () {
            container.animate(
                {
                    scrollLeft: -scroll
                },
                {
                    duration: 4000, queue: false
                }
            )
        },
        function () {
            container.stop()
        })

    $(document).scroll(function() {
        let positionScroll = $(window).scrollTop(),
            tableScrollBody = container.height()

        if (positionScroll > 265 && positionScroll < tableScrollBody) {
            $('.arrowRight').css('transform',`translateY(${positionScroll-260}px)`);
            $('.arrowLeft').css('transform',`translateY(${positionScroll-250}px)`);
        }
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


    /** journal filters */
    $('.filter-btn-search').on('click', function () {
        $('#journal_requests_filter').addClass('is-open')
        $('.filter-btn-search').hide()
    })

    $('.filter').on('change', function () {
        mainTable.ajax.reload()
    })

    function reportWindowSize() {
        mainTable
            .columns.adjust()
    }

    window.onresize = reportWindowSize

    $('.filter-btn-reset').on('click', function () {
        location.reload()
    })

    $('.auto-fill').on('click', function () {
        const fillModalForm = $('#auto-fill')

        $.magnificPopup.open({
            items: {
                src: fillModalForm,
                type: 'inline',
                fixedContentPos: false
            },
            closeOnBgClick: false,
        })
    })

})
