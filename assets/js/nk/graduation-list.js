$(function () {
    let $journal = $('#graduationJournal');

    /**
     * Журнал градуационной зависимости
     */
    let journalDataTable = $journal.DataTable({
        processing: true,
        serverSide: true,
        bAutoWidth: false,
        autoWidth: false,
        fixedColumns: false,
        ajax: {
            type: 'POST',
            data: function (d) {
                d.dateStart = $('#inputDateStart').val();
                d.dateEnd = $('#inputDateEnd').val();
                d.everywhere = $('#filter_everywhere').val();
            },
            url: '/ulab/nk/getGraduationJournalAjax/',
            dataSrc: function (json) {
                return json.data;
            }
        },
        columns: [
            {
                data: 'id',
                render: function (data, type, item) {
                    // if (item.is_can_edit) {
                        return `<a class="request-link"
                               href="/ulab/nk/graduation/${item.id}">
                               ${item.id}
                            </a>`
                    // } else {
                    //     return `<span>${item.id}</span>`;
                    // }
                }
            },
            {
                data: 'object',
                render: function (data, type, item) {
                    return `<span>${item.object}</span>`;
                }
            },
            {
                data: 'date',
                render: function (data, type, item) {
                    return `<span>${item.ru_date}</span>`;
                }
            },
        ],
        language: dataTablesSettings.language,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Все"]],
        pageLength: 25,
        order: [[0, "desc"]],
        colReorder: true,
        dom: 'frt<"bottom"lip>',
        bSortCellsTop: true,
        scrollX: true,
        fixedHeader: true,
    });

    journalDataTable.columns().every(function() {
        let timeout
        $(this.header()).closest('thead').find('.search:eq('+ this.index() +')').on('keyup change clear', function() {
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
        $('#journal_filter').addClass('is-open');
        $('.filter-btn-search').hide();
    });

    $('.filter').on('change', function () {
        journalDataTable.ajax.reload()
    })

    function reportWindowSize() {
        journalDataTable
            .columns.adjust()
    }

    window.onresize = reportWindowSize

    $('.filter-btn-reset').on('click', function () {
        location.reload();
    });
});