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
        dom: 'frtB<"bottom"lip>',
        buttons: dataTablesSettings.buttons,
        bSortCellsTop: true,
        scrollX: true,
        fixedHeader: true,
    });

    journalDataTable.columns().every( function () {
        $(this.header()).closest('thead').find('.search:eq('+ this.index() +')').on( 'keyup change clear', function () {
            journalDataTable
                .column( $(this).parent().index() )
                .search( this.value )
                .draw();
        });
    });

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

    /*journal buttons*/
    let container = $('div.dataTables_scrollBody'),
        scroll = $journal.width();

    $('.btnRightTable, .arrowRight').hover(function() {
            container.animate(
                {
                    scrollLeft: scroll
                },
                {
                    duration: 4000, queue: false
                }
            )
        },
        function() {
            container.stop();
        });

    $('.btnLeftTable, .arrowLeft').hover(function() {
            container.animate(
                {
                    scrollLeft: -scroll
                },
                {
                    duration: 4000, queue: false
                }
            )
        },
        function() {
            container.stop();
        });

    $(document).scroll(function() {
        let positionScroll = $(window).scrollTop(),
            tableScrollBody = container.height()

        $(".dtfh-floatingparenthead tr:first-child th")
            .css("padding-inline", "0px")

        if (positionScroll > 265 && positionScroll < tableScrollBody) {
            $('.arrowRight').css('transform',`translateY(${positionScroll-260}px)`);
            $('.arrowLeft').css('transform',`translateY(${positionScroll-250}px)`);
        }
    });

});