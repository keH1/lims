$(function () {
    /** modal */
    $('.popup-with-form1').magnificPopup({
        items: {
            src: '#method-modal-form',

            type: 'inline'
        },
        closeOnBgClick: true,
        fixedContentPos: false
    })

    $('.select2').select2({
        theme: 'bootstrap-5'
    })

    let $journal = $('#table-method')

    /*journal requests*/
    let journalDataTable = $journal.DataTable({
        bAutoWidth: false,
        autoWidth: false,
        processing: true,
        serverSide: true,
        ajax: {
            type : 'POST',
            data: function ( d ) {
                d.id = $('#gost-id').val()
            },
            url : '/ulab/gost/getListMethodByGostAjax/',
            dataSrc: function (json) {
                return json.data
            }
        },
        columns: [
            {
                data: 'is_confirm',
                orderable: false,
                render: function (data, type, item) {
                    if (item.is_confirm == 1) {
                        return `<span class="text-green" title="Методика потверждена"><i class="fa-regular fa-circle-check"></i></span>`
                    } else {
                        return `<span class="text-red" title="Методика не потверждена"><i class="fa-regular fa-circle-xmark"></i></span>`
                    }
                }
            },
            {
                data: 'name',
                render: $.fn.dataTable.render.ellipsis(50, true)
            },
            {
                data: 'clause',
            },
            {
                data: 'test_method_name',
                render: $.fn.dataTable.render.ellipsis(50, true)
            },
            {
                data: 'unit_rus',
                render: $.fn.dataTable.render.ellipsis(50, true)
            },
            {
                data: 'in_field',
                render: function (data, type, item) {
                    if (item.in_field == 1) {
                        return 'Да'
                    } else {
                        return 'Нет'
                    }
                }
            },
            {
                data: 'is_extended_field',
                render: function (data, type, item) {
                    if (item.is_extended_field == 1) {
                        return 'Да'
                    } else {
                        return 'Нет'
                    }
                }
            },
            {
                data: 'buttons',
                orderable: false,
                render: function (data, type, item) {
                    return `<div class="text-end d-flex justify-content-around">
                                <a
                                        href="/ulab/gost/method/${item['id']}"
                                        class="btn btn-success btn-square me-1"
                                        title="Редактировать методику">
                                    <i class="fa-solid fa-pencil icon-fix"></i>
                                </a>
                                <form action="/ulab/gost/copyMethod/" method="post">
                                    <input type="hidden" name="method_id" value="${item['id']}">
                                    <input type="hidden" name="gost_id" value="${item['gost_id']}">
                                    <button
                                            type="submit"
                                            class="btn btn-primary btn-square"
                                            title="Скопировать методику">
                                        <i class="fa-regular fa-copy icon-fix"></i>
                                    </button>
                                </form>
                            </div>`
                }
            },
        ],
        language: dataTablesSettings.language,
        lengthMenu: [[10, 25, 50, 100, -1], [10,25, 50, 100, "Все"]],
        pageLength: 25,
        order: [[ 2, "asc" ]],
        colReorder: true,
        dom: 'frt<"bottom"lip>',
        bSortCellsTop: true,
        scrollX:       true,
        fixedHeader:   false,
    });

    journalDataTable.columns().every( function() {
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

    const $wrapper = $('#table-method_wrapper');
    if ($wrapper.find('.arrowRight').length === 0) {
        $wrapper.append(`
        <div class="arrowLeft position-fixed" style="right: 65px;">
            <svg class="bi" width="40" height="40">
                <use xlink:href="/ulab/assets/images/icons.svg#arrow-left"/>
            </svg>
        </div>
        <div class="arrowRight position-fixed" style="right: 65px;">
            <svg class="bi" width="40" height="40">
                <use xlink:href="/ulab/assets/images/icons.svg#arrow-right"/>
            </svg>
        </div>
    `);
    }

    const $container = $('.dataTables_scrollBody');

    $container.css('position', 'relative');

    function repositionArrows() {
        const container = $container[0];
        if (!container) return;

        const containerRect = container.getBoundingClientRect();
        const viewportHeight = window.innerHeight;
        const hideOffset = 100;

        const isVisible = containerRect.bottom > hideOffset && containerRect.top < viewportHeight;

        if (!isVisible) {
            $('.arrowLeft, .arrowRight').css('opacity', '0');
            return;
        }

        const opacity = Math.min(
            1,
            (containerRect.bottom - hideOffset) / 100,
            (viewportHeight - containerRect.top - hideOffset) / 100
        );

        $('.arrowLeft, .arrowRight').css({
            'opacity': opacity,
            'pointer-events': opacity > 0.5 ? 'auto' : 'none'
        });

        const newY = Math.max(
            hideOffset,
            Math.min(containerRect.top + 20, viewportHeight - 90)
        );

        $('.arrowLeft').css('top', `${newY + 50}px`);
        $('.arrowRight').css('top', `${newY}px`);
    }

    $(window).on('scroll resize', () => {
        requestAnimationFrame(repositionArrows);
    });
    repositionArrows();

    let scroll = $journal.width()

    $('.arrowRight').hover(function() {
            $container.animate(
                {
                    scrollLeft: scroll
                },
                {
                    duration: 4000, queue: false
                }
            )
        },
        function() {
            $container.stop();
        })

    $('.arrowLeft').hover(function() {
            $container.animate(
                {
                    scrollLeft: -scroll
                },
                {
                    duration: 4000, queue: false
                }
            )
        },
        function() {
            $container.stop();
        })
})
