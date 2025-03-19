$(function ($) {
    /*recipe journal*/
    let reactiveJournal = $('#reactive_journal').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            type: 'POST',
            data: function (d) {
                d.idWhichFilter = $('#inputIdWhichFilter').val()
            },
            url: '/ulab/reactivestorage/getListProcessingAjax/',
            dataSrc: function (json) {
                return json.data
            },
        },
        columns: [
            {
                data: 'number'
            },
            {
                data: 'name'
            },
            {
                data: 'number_batch'
            },
            {
                data: 'date_expired_dateformat',
                render: function (data, type, item) {
                    if (item.is_expired == 0) {
                        return data
                    } else return `<div class="alert alert-danger" title="Срок годности иcтек">` + data + `</div>`
                }
            },
            {
                data: 'receive_quantity'
            },
            {
                data: 'consume_quantity'
            },
            {
                data: 'total_quantity'
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
        fixedHeader: false
    })

    reactiveJournal.columns().every(function () {
        $(this.header()).closest('thead').find('.search:eq(' + this.index() + ')').on('keyup change clear', function () {
            reactiveJournal
                .column($(this).parent().index())
                .search(this.value)
                .draw()
        })
    })

    $('.select-id-reactive').change(function () {
        reactiveJournal.ajax.reload()
    })

    /*journal buttons*/
    let container = $('div.dataTables_scrollBody'),
        scroll = $('#recipe_journal').width()


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

    $('.select-reactive').select2({
        placeholder: 'Выбирете реактив',
        width: '100%',
    })
    $('.select-id-reactive').select2({
        placeholder: 'Выбирете реактив',
        width: '100%',
    })
    $('.popup-first').magnificPopup({
        items: {
            src: '#add-entry-modal-form-first',
            type: 'inline'
        },
        fixedContentPos: false
    })

    $("body").on('change', '.select-reactive', function () {
        let unit = $('option:selected', this).data('unit')
        $('.quantity-reactive').html(unit)
    })

    /** journal filters */
    $('.filter-btn-search').on('click', function () {
        $('#journal_requests_filter').addClass('is-open')
        $('.filter-btn-search').hide()
    })

    $('.filter').on('change', function () {
        reactiveJournal.ajax.reload()
    })

    function reportWindowSize() {
        reactiveJournal
            .columns.adjust()
    }

    window.onresize = reportWindowSize

    $('.filter-btn-reset').on('click', function () {
        location.reload()
    })

})
