$(function () {
    let $journal = $('#journal_gost')

    /*journal requests*/
    let journalDataTable = $journal.DataTable({
        processing: true,
        serverSide: true,
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
                data: 'num_oa'
            },
            {
                data: 'reg_doc',
                render: function (data, type, item) {
                    return `<a href="/ulab/gost/edit/${item.gost_id}">${item.reg_doc}</a>`
                }
            },
            {
                data: 'clause'
            },
            {
                data: 'year',
            },
            {
                data: 'name',
                render: function (data, type, item) {
                    if ( item.method_id === null ) {
                        return `Методик не добавлено`
                    }
                    return `<a href="/ulab/gost/method/${item.method_id}">${item.name}</a>`
                }
            },
            {
                data: 'test_method',
                render: $.fn.dataTable.render.ellipsis(32, true)
            },
            {
                data: 'price'
            },
            {
                data: 'input',
                orderable: false,
                render: function (data, type, item) {
                    return `<input type="number" min="0" step="0.01" class="form-control bg-white new_price" value="">`
                }
            },
            {
                data: 'button',
                orderable: false,
                render: function (data, type, item) {
                    return `<button class="btn btn-primary save_price" type="button" data-method_id="${item.method_id}">Сохранить</button>`
                }
            }
        ],
        language: dataTablesSettings.language,
        lengthMenu: [[10, 25, 50, 100, -1], [10,25, 50, 100, "Все"]],
        pageLength: 25,
        order: [[ 0, "desc" ]],
        colReorder: true,
        dom: 'frt<"bottom"lip>',
        // buttons: [
        //     {
        //         extend: 'colvis',
        //         titleAttr: 'Выбрать'
        //     },
        //     {
        //         extend: 'copy',
        //         titleAttr: 'Копировать',
        //         exportOptions: {
        //             modifier: {
        //                 page: 'current'
        //             }
        //         }
        //     },
        //     {
        //         extend: 'excel',
        //         titleAttr: 'excel',
        //         exportOptions: {
        //             modifier: {
        //                 page: 'current'
        //             }
        //         }
        //     },
        //     {
        //         extend: 'print',
        //         titleAttr: 'Печать',
        //         exportOptions: {
        //             modifier: {
        //                 page: 'current'
        //             }
        //         }
        //     }
        // ],
        bSortCellsTop: true,
        scrollX:       true,
        fixedHeader:   true,
    });

    initNumberInputRestriction('input.new_price')

    journalDataTable.columns().every(function () {
        let timeout
        $(this.header()).closest('thead').find('.search:eq(' + this.index() + ')').on('input', function () {
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


    $('body').on('click', '.save_price', function () {
        let $container = $(this).closest('tr')
        let methodId = $(this).data('method_id')
        let newPrice = $container.find('.new_price').val()

        if ( newPrice != '' && newPrice !== undefined ) {
            $.ajax({
                method: 'POST',
                url: '/ulab/gost/setNewPriceAjax/',
                data: {
                    method_id: methodId,
                    new_price: newPrice
                },
                dataType: "json",
                success: function (data) {
                    if ( data.success ) {
                        showSuccessMessage('Цена успешно обновилась')
                        journalDataTable.ajax.reload()
                        journalDataTable.draw()
                    } else {
                        showErrorMessage(data.error)
                    }
                }
            })
        } else {
            showErrorMessage("Новая цена не может быть пустой")
        }
    })
})