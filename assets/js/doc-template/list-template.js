$(function () {
    let $journal = $('#journal_template')
    let $body = $('body')

    $body.on('click', '.edit-template-btn', function () {
        const id = $(this).data('id')
        const $form = $('#edit-modal-form')

        $.ajax({
            url: "/ulab/docTemplate/getAjax/",
            data: {"id": id},
            dataType: "json",
            method: "POST",
            success: function (data) {
                $form.find('.template_id').val(data.id)
                $form.find('.template_name').val(data.name)
                $form.find('.template_type').val(data.id_template_type)
                $form.find('.template_description').val(data.description)

                $.magnificPopup.open({
                    items: {
                        src: '#edit-modal-form',
                    }
                })
            }
        })
    })

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
            url : '/ulab/docTemplate/getJournalTemplateAjax/',
            dataSrc: function (json) {
                return json.data
            }
        },
        columns: [
            {
                data: 'id',
                width:'10px',
                render: function (data, type, item) {
                    return ``
                },
            },
            {
                data: 'name',
            },
            {
                data: 'file_name',
                render: function (data, type, item) {
                    return `<a class="results-link"
                               href="${item.file_url}">
                               ${item.file_name}
                            </a>`
                }
            },
            {
                data: 'description',
            },
            {
                data: 'type_text',
            },
            {
                data: 'qqq',
                orderable: false,
                render: function (data, type, item) {
                    return `<a
                                    href="#"
                                    class="btn btn-success btn-square me-1 edit-template-btn"
                                    title="Редактировать шаблон"
                                    data-id="${item.id}"
                                >
                                <i class="fa-solid fa-pencil icon-fix"></i>
                            </a>
                            <a 
                                    onclick="return confirm('Удаление шаблона. Продолжить?')" 
                                    class="btn btn-danger btn-square"
                                    title="Удалить шаблон"
                                    href="/ulab/docTemplate/deleteTemplate/${item.id}" 
                                >
                                <i class="fa-solid fa-minus icon-fix"></i>
                            </a>`
                }
            }
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
        order: [[ 0, "desc" ]],
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