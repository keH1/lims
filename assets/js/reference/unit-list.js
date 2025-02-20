$(function ($) {
    let $journal = $('#journal')

    if ( $journal.length > 0 ) {
        /*journal requests*/
        let journalDataTable = $journal.DataTable({
            bAutoWidth: false,
            autoWidth: false,
            fixedColumns: false,
            processing: true,
            serverSide: true,
            ajax: {
                type: 'POST',
                url: '/ulab/reference/getDataUnitListAjax/',
                dataSrc: function (json) {
                    return json.data
                },
            },
            columns: [
                {
                    data: 'stage',
                    orderable: false,
                    render: function (data, type, item) {
                        if ( item.is_actual == 1 ) {
                            return `<span class="text-green" title="Актуально в ФСА"><i class="fa-regular fa-circle-check"></i></span>`
                        } else {
                            return `<span class="text-red" title="Неактуально в ФСА"><i class="fa-regular fa-circle-xmark"></i></span>`
                        }
                    }
                },
                {
                    data: 'fsa_id',
                    width: '150px'
                },
                {
                    data: 'unit_rus',
                },
                {
                    data: 'name',
                    render: $.fn.dataTable.render.ellipsis(60, true)
                },
                {
                    data: 'is_used',
                    width: '100px',
                    render: function (data, type, item) {
                        let checked = ''
                        if ( item.is_used == 1 ) {
                            checked = 'checked'
                        }
                        return `<label class="switch">
                                    <input type="checkbox" class="form-check-input mt-0 change-is-used" ${checked} data-id="${item.id}">
                                    <span class="slider"></span>
                                </label>`
                    }
                }
            ],
            language: dataTablesSettings.language,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Все"]],
            pageLength: 25,
            order: [[0, "asc"]],
            colReorder: true,
            dom: 'fBrt<"bottom"lip>',
            buttons: [],
            bSortCellsTop: true,
            scrollX: true,
            fixedHeader: false,
        });

        journalDataTable.columns().every(function () {
            let timeout
            $(this.header()).closest('thead').find('.search:eq(' + this.index() + ')').on('keyup change clear', function () {
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
            journalDataTable.draw()
        })

        $('.filter-btn-reset').on('click', function () {
            location.reload()
        })


        journalDataTable.on('change', '.change-is-used', function () {
            let id = $(this).data('id')

            $.ajax({
                method: 'POST',
                url: '/ulab/reference/changeUsedUnitsAjax',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function (data) {

                },
                complete: function(data) {
                    // journalDataTable.ajax.reload()
                    // journalDataTable.draw()
                }
            })
        })


        $('.sync-data').click(function () {
            if ( confirm("Процесс синхронизации с ФСА может занять несколько минут. Не закрывайте вкладку пока не завершится. Продолжить?") ) {
                let $button = $(this)
                $button.find('i').addClass('spinner-animation')
                $button.addClass('disabled')

                $.ajax({
                    method: 'POST',
                    url: '/ulab/reference/syncUnitsAjax',
                    dataType: 'json',
                    success: function (data) {
                        if (data['success']) {
                            showSuccessMessage(data['msg'])

                            journalDataTable.ajax.reload()
                            journalDataTable.draw()
                        } else {
                            showErrorMessage(data['error'])
                        }
                    },
                    error: function(data) {
                        showErrorMessage('Ошибка во время синхронизации')
                    },
                    complete: function () {
                        $button.find('i').removeClass('spinner-animation')
                        $button.removeClass('disabled')
                    }
                })
            }
        })
    }
})