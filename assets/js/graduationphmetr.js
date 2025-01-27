$(function ($) {
    let graduationJournal = $('#graduation_journal').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            type: 'POST',
            data: function (d) {
                if ($('.select-equip option:selected').val() != "") {
                    d.equip = $('.select-equip option:selected').val()
                }
                if ($('.select-date').val() != "") {
                    d.date = $('.select-date').val()
                }
            },
            url: '/ulab/graduationphmetr/getListProcessingAjax/',
            dataSrc: function (json) {
                return json.data
            },
        },
        columns: [
            {
                data: 'date'
            },
            {
                data: 'object'
            },
            {
                data: 'factory_number'
            },
            {
                data: 'result_1'
            },
            {
                data: 'infelicity_1'
            },
            {
                data: 'conclusion_1'
            },
            {
                data: 'result_2'
            },
            {
                data: 'infelicity_2'
            },
            {
                data: 'conclusion_2'
            },
            {
                data: 'result_3'
            },
            {
                data: 'infelicity_3'
            },
            {
                data: 'conclusion_3'
            },
            {
                data: 'global_assigned_name'
            }
        ],

        columnDefs: [{
            className: 'control',
            /*'targets': */
            'orderable': false,
        }],
        language: {
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
    // $('.select-fridge').change(function () {
    //     precursorJournal.ajax.reload()
    // })
    // $('.select-month').change(function () {
    //     precursorJournal.ajax.reload()
    // })

    /*journal buttons*/
    let container = $('div.dataTables_scrollBody'),
        scroll = $('#graduation_journal').width()


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
        fixedContentPos: false
    })


    /** journal filters */
    $('.filter-btn-search').on('click', function () {
        $('#journal_requests_filter').addClass('is-open')
        $('.filter-btn-search').hide()
    })

    $('.filtered').on('change', function () {
        // graduationJournal.ajax.reload()
        graduationJournal.draw()
    })

    $('.filter-btn-reset').on('click', function () {
        location.reload()
    })

    // function nameReplace (bool) {
    //     if (bool) {
    //         for (let i = 1; i <= 5; i++) {
    //             $(`input[data-id="${i}"]`).attr("name", `graduation[result][${i - 1}][]`)
    //         }
    //     } else {
    //         for (let i = 2; i <= 4; i++) {
    //             $(`input[data-id="${i}"]`).attr("name", `graduation[result][${i - 2}][]`)
    //         }
    //     }
    // }

    $("#changeMeasuring").on("click", function(){
        if ($(this).prop("checked") == true) {
            $("#measure").find(".hidden-measure").css("display", "table-row")
            $(".hidden-measure").find("input").attr("disabled", false)

           // nameReplace(true)
        } else {
            $("#measure").find(".hidden-measure").css("display", "none")
            $(".hidden-measure").find("input").attr("disabled", true)

            //nameReplace(false)
        }
    })
})
