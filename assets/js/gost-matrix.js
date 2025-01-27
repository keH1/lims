$(function () {
    const $journal = $('#journal_matrix')
    const $trHeaderTitle = $journal.find('.header-title')
    const $trHeaderSearch = $journal.find('.header-search')

    let columns = [
        {
            data: 'num_oa',
            width: '95px'
        },
        {
            data: 'reg_doc',
            render: function (data, type, item) {
                return `<a href="/ulab/gost/edit/${item.gost_id}">${item.reg_doc} ${item.clause}</a>`
            }
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
    ]

    let countCell = $trHeaderTitle.find('th').length
    let centerCell = [0]

    $.ajax({
        url: "/ulab/gost/getLabAndUserAjax/",
        success: function (data) {
            data = JSON.parse(data);
            // console.log(data)
            $.each(data, function (i, val) {
                let countUser = Object.keys(val.users).length

                $trHeaderTitle.find('th:last-child').after(`<th scope="col" colspan="${countUser}">${val.short_name}</th>`)

                $.each(val.users, function (k, user) {
                    $trHeaderSearch.find('th:last-child').after(`<th scope="col" class="text-nowrap">${user.short_name}</th>`)

                    centerCell.push(countCell++)

                    columns.push(
                        {
                            data: 'qwe',
                            orderable: false,
                            render: function (data, type, item) {
                                let check = item.assigned.includes(user.id) ? 'checked' : ''

                                return `<input class="form-check-input user_in_method" data-user_id="${user.id}" data-method_id="${item.method_id}" type="checkbox" value="1" ${check}>`
                            }
                        }
                    )
                })


            })

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
                        d.stage = $('#selectStage option:selected').val()
                        d.lab = $('#selectLab option:selected').val()
                    },
                    url : '/ulab/gost/getJournalMatrixAjax/',
                    dataSrc: function (json) {
                        return json.data
                    }
                },
                columnDefs: [
                    { className: 'text-center', targets: centerCell },
                ],
                columns: columns,
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
                scrollX: true,
                fixedHeader: false,
            });

            journalDataTable.columns().every( function () {
                $(this.header()).closest('thead').find('.search:eq('+ this.index() +')').on( 'keyup change clear', function () {
                    journalDataTable
                        .column( $(this).parent().index() )
                        .search( this.value )
                        .draw();
                })
            })


            $('.filter').on('change', function () {
                journalDataTable.ajax.reload()
                journalDataTable.draw()
            })

            $('#workarea-content').on('change', '.user_in_method', function () {
                const userId = $(this).data('user_id')
                const methodId = $(this).data('method_id')

                $.ajax({
                    url: "/ulab/gost/setAssignedAjax/",
                    method: "POST",
                    data: {
                        user_id: userId,
                        method_id: methodId
                    },
                    success: function (data) {
                        journalDataTable.ajax.reload()
                        journalDataTable.draw()
                    }
                })

                return true
            })
        }
    })


    $('.filter-btn-reset').on('click', function () {
        location.reload()
    })
})