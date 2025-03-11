$(function ($) {
    /*recipe journal*/
    let recipeJournal = $('#recipe_journal').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            type: 'POST',
            data: function (d) {
                d.param = $('#param').val()
            },
            url: '/ulab/recipe/getListProcessingAjax/',
            dataSrc: function (json) {
                return json.data
            },
        },
        columns: [
            {
                data: 'name'
            },
            {
                data: 'concentration_full'
            },
            {
                data: 'type_name'
            },
            {
                data: 'GOST'
            },
            {
                data: 'reactives_full'
            },
            {
                data: 'solvent_full'
            },
            {
                data: 'quantity_solution_full'
            },
            {
                data: 'storage_life'
            },
            {
                data: 'check_in_day'
            },
            {
                data: 'global_assigned_name'
            }
        ],
        'columnDefs': [{
            'targets': [],
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
        fixedHeader: false,
    })

    recipeJournal.columns().every(function () {
        $(this.header()).closest('thead').find('.search:eq(' + this.index() + ')').on('keyup change clear', function () {
            recipeJournal
                .column($(this).parent().index())
                .search(this.value)
                .draw()
        })
    })

    /*journal buttons*/
    let container = $('div.dataTables_scrollBody'),
        scroll = $('#recipe_journal').width()

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

    $('.add-reactive').click(function () {
        $.each($('.select-reactive'), function () {
            $(this).select2('destroy');
        });
        let cloneReactive = $('.reactives[data-id = "1"]').clone()
        let countReactive = $('.reactives').length
        let countReactivePlusOne = countReactive + 1
        cloneReactive.attr('data-id', countReactivePlusOne)
        cloneReactive.find('select').attr('name', `reactives[unit_reactive_id` + countReactivePlusOne + `][id_library_reactive]`)
        cloneReactive.find('input').attr('name', `reactives[unit_reactive_id` + countReactivePlusOne + `][quantity]`)
        $(`.reactives[data-id="${countReactive}"]`).after(cloneReactive)
        $.each($('.select-reactive'), function () {
            $(this).select2();
        });
    })

    $('.del-reactive').click(function () {
        let countReactive = $('.reactives').length
        if (countReactive > 1) $(`.reactives[data-id="${countReactive}"]`).remove()
        else alert("Нельзя удалить единственный реактив")
    })

    $('.recipe-type').change(function () {
        if ($(this).val() == '3') {
            $('.recipe-is-accurate').prop('disabled', false);
        } else {
            $('.recipe-is-accurate').prop('disabled', true).prop('checked', true);
        }
        if ($(this).val() == '2') {
            $('.recipe-check').prop('hidden', true);
        } else {
            $('.recipe-check').prop('hidden', false);
        }
    })
    $('.select-solution').change(function () {
        let unit = $('.select-solution option:selected').data('unit')
        $('.quantity-solvent').html(unit);
    })

    $("body").on('change', '.select-reactive', function () {
        const unit = $('option:selected', this).data('unit')
        $(this).parents('div[class^=reactive-unit]').find('.quantity-reactive').html(unit)
    })


    /** modal */
    $('.popup-first').magnificPopup({
        items: {
            src: '#add-entry-modal-form-first',
            type: 'inline'
        },
        fixedContentPos: false
    })
    $('.popup-second').magnificPopup({
        items: {
            src: '#add-entry-modal-form-second',
            type: 'inline'
        },
        fixedContentPos: false
    })

    $('.select-doc').select2({
        placeholder: 'Выберете документ',
        width: '100%',
    })
    $('.select-reactive').select2({
        placeholder: 'Выберете реактив',
        width: '100%',
    })
    $('.select-solution').select2({
        placeholder: 'Выберете растворитель',
        width: '100%',
    })
    $('.select-recipe').select2({
        placeholder: 'Выберете рецепт',
        width: '100%',
    })

    /** journal filters */
    $('.filter-btn-search').on('click', function () {
        $('#journal_requests_filter').addClass('is-open')
        $('.filter-btn-search').hide()
    })

    $('.filter').on('change', function () {
        recipeJournal.ajax.reload()
        recipeJournal.draw()
    })

    $('.filter-btn-reset').on('click', function () {
        location.reload()
    })

})
