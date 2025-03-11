const conditionerRoom = {
    '12' : "Samsung (модель AQ07XLN)",
    '13' : "Samsung (модель AQ07XLN)",
    '11' : "Midea (модель MSE-07HR)",
    '10' : "Midea (модель MSE-07HR)"
}

$(function ($) {
    /*recipe journal*/
    let fridgejournal = $('#fridge_journal').DataTable({
        processing: true,
        serverSide: true,
        bAutoWidth: false,
        autoWidth: false,
        fixedColumns: false,
        ajax: {
            type: 'POST',
            data: function (d) {
            },
            url: '/ulab/disinfectionConditioners/getListProcessingAjax/',
            dataSrc: function (json) {
                return json.data
            },
        },
        columns: [
            {
                data: 'date_dateformat'
            },
            {
                data: 'NUMBER'
            },
            {
                data: 'conditioner'
            },
            {
                data: 'disinfectant'
            },
            {
                data: 'date_sol_dateformat'
            },
            {
                data: 'global_assigned_name'
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

    })

    fridgejournal.columns().every(function () {
        $(this.header()).closest('thead').find('.search:eq(' + this.index() + ')').on('keyup change clear', function () {
            fridgejournal
                .column($(this).parent().index())
                .search(this.value)
                .draw()
        })
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

    $('.filter').on('change', function () {
        fridgejournal.ajax.reload()
        fridgejournal.draw()
    })

    $('.filter-btn-reset').on('click', function () {
        location.reload()
    })


    $('.select-room').change(function () {
        let val = $(this).val()

        $('.conditioner').val(conditionerRoom[val])
    })
})
