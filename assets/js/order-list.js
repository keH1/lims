$(function ($) {
    let $journal = $('#journal_order')

    /*journal requests*/
    let journalDataTable = $journal.DataTable({
        bAutoWidth: false,
        autoWidth: false,
        fixedColumns: false,
        processing: true,
        serverSide: true,
        ajax: {
            type : 'POST',
            data: function ( d ) {
                d.dateStart = $('#inputDateStart').val()
                d.dateEnd = $('#inputDateEnd').val()
                d.everywhere = $('#filter_everywhere').val()
            },
            url : '/ulab/order/getListProcessingAjax/',
            dataSrc: function (json) {
                return json.data
            },
        },
        columns: [
            {
                data: 'stage',
                width: '52px',
                orderable: false,
                render: function (data, type, item) {
                    return `<div class="stage rounded ${item['bgStage']}" title="${item['titleStage']}"></div>`
                }
            },
            {
                data: 'NUMBER',
                width: '100px',
                render: function (data, type, item) {
                    return `<a class="results-link"
                               href="/ulab/order/card/${item['d_id']}" target="_blank">
                               ${item['NUMBER']}
                            </a>`
                }
            },
            {
                data: 'DATE',
                width: '100px'
            },
            {
                data: 'CONTRACT_TYPE',
            },
            {
                data: 'COMPANY_TITLE',
                width: '100%',
                render: $.fn.dataTable.render.ellipsis(45, true)
            },
            {
                data: 'linkName',
                orderable: false,
                render: function (data, type, item) {
                    if ( item['order_pdf'] === '' ) {
                        return 'Не сформирована';
                    } else {
                        return `<a class="results-link"
                               href="/protocol_generator/archive_dog/${item['order_pdf']}" target="_blank">
                               Скачать
                            </a>`
                    }
                }
            },
            {
                data: 'linkName2',
                orderable: false,
                render: function (data, type, item) {
                    if ( item['PDF'] === '' ) {
                        return 'Не загружена';
                    } else {
                        return `<a class="results-link"
                               href="/pdf/${item['PDF']}" target="_blank">
                               Скачать
                            </a>`
                    }
                }
            },
        ],
        language:{
            processing: 'Подождите...',
            search: '',
            searchPlaceholder: "Поиск...",
            lengthMenu: 'Отображать _MENU_  ',
            info: 'Записи с _START_ до _END_ из _TOTAL_ записей',
            infoEmpty: 'Записи с 0 до 0 из 0 записей',
            infoFiltered: '(отфильтровано из _MAX_ записей)',
            infoPostFix: '',
            loadingRecords: 'Загрузка записей...',
            zeroRecords: 'Записи отсутствуют.',
            emptyTable: 'В таблице отсутствуют данные',
            paginate: {
                first: 'Первая',
                previous: 'Предыдущая',
                next: 'Следующая',
                last: 'Последняя'
            },
            buttons: {
                colvis: '',
                copy: '',
                excel: '',
                print: ''
            },
            aria: {
                sortAscending: ': активировать для сортировки столбца по возрастанию',
                sortDescending: ': активировать для сортировки столбца по убыванию'
            }
        },
        lengthMenu: [[10, 25, 50, 100, -1], [10,25, 50, 100, "Все"]],
        pageLength: 25,
        order: [[ 2, "desc" ]],
        colReorder: true,
        dom: 'frtB<"bottom"lip>',
        buttons: [
            {
                extend: 'colvis',
                titleAttr: 'Выбрать'
            },
            {
                extend: 'copy',
                titleAttr: 'Копировать',
                exportOptions: {
                    modifier: {
                        page: 'current'
                    }
                }
            },
            {
                extend: 'excel',
                titleAttr: 'excel',
                exportOptions: {
                    modifier: {
                        page: 'current'
                    }
                }
            },
            {
                extend: 'print',
                titleAttr: 'Печать',
                exportOptions: {
                    modifier: {
                        page: 'current'
                    }
                }
            }
        ],
        bSortCellsTop: true,
        scrollX:       true,
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
        journalDataTable.draw()
    })

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

    let $body = $("body")
    let $containerScroll = $body.find('.dataTables_scroll')
    let $thead = $('.journal thead tr:first-child')

    $(document).scroll(function() {
        let positionScroll = $(window).scrollTop(),
            tableScrollBody = container.height(),
            positionTop = $containerScroll.offset().top

        if ( positionScroll >= positionTop ) {
            $thead.attr('style', 'position:fixed;top:0;z-index:99')
        } else {
            $thead.attr('style', '')
        }

        if (positionScroll > 265 && positionScroll < tableScrollBody) {
            $('.arrowRight').css('transform',`translateY(${positionScroll-260}px)`);
            $('.arrowLeft').css('transform',`translateY(${positionScroll-250}px)`);
        }
    })
})