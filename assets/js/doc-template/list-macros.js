$(function () {
    let $journal = $('#journal')

    /*journal requests*/
    let journalDataTable = $journal.DataTable({
        processing: true,
        serverSide: true,
        bAutoWidth: false,
        autoWidth: false,
        fixedColumns: false,
        ajax: {
            type : 'POST',
            data: function ( d ) {

            },
            url : '/ulab/docTemplate/getJournalMacrosAjax/',
            dataSrc: function (json) {
                return json.data
            }
        },
        columns: [
            {
                data: 'macros',
            },
            {
                data: 'description'
            },
            {
                data: 'type_name'
            },
            {
                data: 'link',
                orderable: false,
                render: function (data, type, item) {
                    return `<a class="results-link"
                               href="#"
                               data-text-clipboard="\${${item.macros}}" 
                               >
                                Скопировать
                            </a>`
                }
            }
        ],
        language: dataTablesSettings.language,
        lengthMenu: [[10, 25, 50, 100, -1], [10,25, 50, 100, "Все"]],
        pageLength: 25,
        order: [[ 0, "desc" ]],
        colReorder: true,
        dom: 'frtB<"bottom"lip>',
        buttons: dataTablesSettings.buttons,
        bSortCellsTop: true,
        scrollX:       true,
        fixedHeader:   true,
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

    /*journal buttons*/
    let container = $('div.dataTables_scrollBody'),
        scroll = $journal.width()

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
        })

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
        })

    $(document).scroll(function() {
        let positionScroll = $(window).scrollTop(),
            tableScrollBody = container.height()

        $(".dtfh-floatingparenthead tr:first-child th")
            .css("padding-inline", "0px")

        if (positionScroll > 265 && positionScroll < tableScrollBody) {
            $('.arrowRight').css('transform',`translateY(${positionScroll-260}px)`);
            $('.arrowLeft').css('transform',`translateY(${positionScroll-250}px)`);
        }
    })
})