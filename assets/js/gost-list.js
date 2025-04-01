$(function () {
    let $journal = $('#journal_gost')

    let journalDataTable = window.initDataTable('#journal_gost', {
        ajax: {
            type : 'POST',
            data: function ( d ) {
                d.stage = $('#selectStage option:selected').val()
                d.lab = $('#selectLab option:selected').val()
                d.everywhere = $('#filter_everywhere').val()
            },
            url : '/ulab/gost/getJournalAjax/',
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
                data: 'num_oa'
            },
            {
                data: 'reg_doc',
                render: function (data, type, item) {
                    return `<a href="/ulab/gost/edit/${item.gost_id}">${item.reg_doc}</a>`
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
                        return `Методик не добавлено`
                    }
                    if ( item.mp_name === null ) {
                        return `<a href="/ulab/gost/method/${item.method_id}">${item.name}</a>`
                    }
                    return `<a href="/ulab/gost/method/${item.method_id}">${item.mp_name}</a>`
                }
            },
            {
                data: 'clause'
            },
            {
                data: 'test_method',
                render: $.fn.dataTable.render.ellipsis(32, true)
            },
            {
                data: 'unit_rus'
            },
            {
                data: 'in_field',
                render: function (data, type, item) {
                    if (item['in_field'] == 1) {
                        return 'Да'
                    }
                    return 'Нет'
                }
            },
            {
                data: 'is_extended_field',
                render: function (data, type, item) {
                    if (item['is_extended_field'] == 1) {
                        return 'Да'
                    }
                    return 'Нет'
                }
            }
        ],
        language: dataTablesSettings.language,
        lengthMenu: [[10, 25, 50, 100, -1], [10,25, 50, 100, "Все"]],
        pageLength: 25,
        order: [[ 1, "desc" ]],
        colReorder: true,
        dom: 'frtB<"bottom"lip>',
        buttons: dataTablesSettings.buttonPrint
    });

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