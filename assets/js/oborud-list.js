$(function ($) {
    let $journal = $('#journal_oborud')

    let journalDataTable = window.initDataTable('#journal_oborud', {
        ajax: {
            type : 'POST',
            data: function ( d ) {
                d.stage = $('#selectStage option:selected').val()
                d.lab = $('#selectLab option:selected').val()
                d.everywhere = $('#filter_everywhere').val()
            },
            url : '/ulab/oborud/getListProcessingAjax/',
            dataSrc: function (json) {
                return json.data
            }
        },
        columns: [
            {
                data: 'stage',
                orderable: false,
                render: function (data, type, item) {
                    return `<div class="stage rounded ${item['bgStage']}" title="${item['titleStage']}"></div>`
                }
            },
            {
                data: 'NAME',
                render: $.fn.dataTable.render.ellipsis(50, true)
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
                data: 'IDENT',
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
                data: 'god_vvoda_expluatation',
            },
            {
                data: 'measuring_range',
                render: $.fn.dataTable.render.ellipsis(50, true)
            },
            {
                data: 'сlass_precision_and_accuracy',
                render: $.fn.dataTable.render.ellipsis(50, true)
            },
            {
                data: 'certificate_name',
                render: $.fn.dataTable.render.ellipsis(50, true)
            },
            {
                data: 'date_start',
            },
            {
                data: 'date_end',
            },
            {
                data: 'POVERKA_PLACE',
            },
            {
                data: 'property_rights',
            },
            {
                data: 'laba_name',
            },
            {
                data: 'room',
            },
            {
                data: 'IN_AREA',
                render: function (data, type, item) {
                    if (item['IN_AREA'] == 1) {
                        return 'В области'
                    }
                    return 'Не в области'
                }
            },
            {
                data: 'CHECKED',
                render: function (data, type, item) {
                    if (item['CHECKED'] == 1) {
                        return 'Да'
                    }
                    return 'Нет'
                }
            },
            {
                data: 'manufacturer',
                render: $.fn.dataTable.render.ellipsis(50, true)
            },
            {
                data: 'note',
                render: $.fn.dataTable.render.ellipsis(40, true)
            }
        ],
        language: dataTablesSettings.language,
        lengthMenu: [[10, 25, 50, 100, -1], [10,25, 50, 100, "Все"]],
        pageLength: 25,
        order: [[ 2, "asc" ]],
        dom: 'frtB<"bottom"lip>',
        buttons: dataTablesSettings.buttonPrint,
    });

    // window.adjustmentColumnsTable(journalDataTable)
    window.setupDataTableColumnSearch(journalDataTable)
    window.setupJournalFilters(journalDataTable)

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

        if (positionScroll > 265 && positionScroll < tableScrollBody) {
            $('.arrowRight').css('transform',`translateY(${positionScroll-260}px)`);
            $('.arrowLeft').css('transform',`translateY(${positionScroll-250}px)`);
        }
    })
})
