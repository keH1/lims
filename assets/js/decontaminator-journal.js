$(function ($) {
        /*recipe journal*/
        let fridgejournal = $('#fridge_journal').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                type: 'POST',
                data: function (d) {
                },
                url: '/ulab/fridge/getListProcessingAjax/',
                dataSrc: function (json) {
                    return json.data
                },
            },
            columns: [
                {
                    data: 'name'
                },
                {
                    data: 'OBJECT'
                },
                {
                    data: 'TYPE_OBORUD'
                },
                {
                    data: 'FACTORY_NUMBER'
                },
                {
                    data: 'INV_NUM'
                },
                {
                    data: 'range_full'
                }
            ],

            columnDefs: [{
                className: 'control',

                'orderable': false,
            },

            ],
            language: dataTablesSettings.language,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Все"]],
            pageLength: 25,
            order: [],
            colReorder: true,
            dom: 'fBrt<"bottom"lip>',
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
            scrollX: true,
            fixedHeader: false,

        })

        fridgejournal.columns().every(function () {
            $(this.header()).closest('thead').find('.search:eq(' + this.index() + ')').on('keyup change clear', function () {
                fridgejournal
                    .column($(this).parent().index())
                    .search(this.value)
                    .draw()
            })
        })

        $('.is-full').change(function () {

            if ($(this).is(':checked')) {
                $('#Full').prop({'hidden': false})
                $('#Full input').each(function () {
                    $(this).prop('required', true).val(0)
                })
            } else {
                $('#Full').prop({'hidden': true});
                $('#Full input').each(function () {
                    $(this).prop('required', false).val("")
                })
            }
        })
        $('.is-all').change(function () {

            if ($(this).is(':checked')) {
                fridgejournal.columns([3, 4, 5, 6, 7, 8, 9, 10, 11, 12]).visible(false);

            } else {
                fridgejournal.columns([3, 4, 5, 6, 7, 8, 9, 10, 11, 12]).visible(true);
            }
        })

        /*journal buttons*/
        let container = $('div.dataTables_scrollBody'),
            scroll = $('#fridge_journal').width()

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

        let $body = $("body")
        let $containerScroll = $body.find('.dataTables_scroll')
        let $thead = $('.journal thead tr:first-child')

        $(document).scroll(function () {
            let positionScroll = $(window).scrollTop(),
                tableScrollBody = container.height(),
                positionTop = $containerScroll.offset().top

            if (positionScroll >= positionTop) {
                $thead.attr('style', 'position:fixed;top:0;z-index:99')
            } else {
                $thead.attr('style', '')
            }

            if (positionScroll > 265 && positionScroll < tableScrollBody) {
                $('.arrowRight').css('transform', `translateY(${positionScroll - 260}px)`)
                $('.arrowLeft').css('transform', `translateY(${positionScroll - 250}px)`)
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
        $('.popup-second').magnificPopup({
            items: {
                src: '#add-entry-modal-form-second',
                type: 'inline'
            },
            fixedContentPos: false,
            closeOnBgClick: false,
        })

        $('.select-oborud').select2({
            placeholder: 'Выберете оборудование',
            width: '100%',
        })
    $('.select-lamp').select2({
        placeholder: 'Выберете оборудование',
        width: '100%',
    })

    /** journal filters */
    $('.filter-btn-search').on('click', function () {
        $('#journal_requests_filter').addClass('is-open')
        $('.filter-btn-search').hide()
    })

    $('.filter').on('change', function () {
        fridgejournal.ajax.reload()
    })

    function reportWindowSize() {
        fridgejournal
            .columns.adjust()
    }

    window.onresize = reportWindowSize

        $('.filter-btn-reset').on('click', function () {
            location.reload()
        })

    }
)
