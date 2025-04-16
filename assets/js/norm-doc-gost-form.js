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
            url : '/ulab/normDocGost/getListMethodByGostAjax/',
            dataSrc: function (json) {
                return json.data
            }
        },
        columns: [
            {
                data: 'is_confirm',
                className: 'text-center',
                orderable: true,
                render: function (data, type, item) {
                    if (item.is_confirm == 1) {
                        return `<span class="text-green" title="Методика подтверждена"><i class="fa-regular fa-circle-check"></i></span>`
                    } else {
                        return `<span class="text-red" title="Методика не подтверждена"><i class="fa-regular fa-circle-xmark"></i></span>`
                    }
                }
            },
            {
                data: 'name',
                // render: $.fn.dataTable.render.ellipsis(50, true)
            },
            {
                data: 'clause',
            },
            {
                data: 'unit_rus',
                render: $.fn.dataTable.render.ellipsis(50, true)
            },
            {
                data: 'buttons',
                className: 'text-end',
                render: function (data, type, item) {
                    return `<div style="display: inline-flex;">
                                <a
                                        href="/ulab/normDocGost/method/${item['id']}"
                                        class="btn btn-success btn-square me-1"
                                        title="Редактировать методику">
                                    <i class="fa-solid fa-pencil icon-fix"></i>
                                </a>
                                <form action="/ulab/normDocGost/copyMethod/" method="post">
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
        buttons: dataTablesSettings.buttons,
        bSortCellsTop: true,
        scrollX:       true,
        fixedHeader:   false,
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
})
