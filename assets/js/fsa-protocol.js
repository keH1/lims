$(function ($) {
    let $body = $("body")


    $('.select2').select2({
        theme: 'bootstrap-5',
        width: 'resolve',
    })

    let $journal = $('#journal_xml')

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
                url: '/ulab/fsa/getListXmlAjax/',
                dataSrc: function (json) {
                    return json.data
                },
            },
            columns: [
                {
                    data: 'date',
                    width: '100px'
                },
                {
                    data: 'xml_file',
                    orderable: false,
                    render: function (data, type, item) {
                        return `<a class="results-link"
                           href="/ulab/upload/fsa/protocols/${item['file_xml']}" download>
                           ${item['file_xml']}
                        </a>`
                    }
                },
                {
                    data: 'sig_file',
                    orderable: false,
                    render: function (data, type, item) {
                        if ( item['file_sig'] !== '' ) {
                            return `<a class="results-link"
                               href="/ulab/upload/fsa/protocols/${item['file_sig']}" download>
                               ${item['file_sig']}
                            </a>`
                        } else {
                            return ``
                        }
                    }
                },
                {
                    data: 'button',
                    width: '120px',
                    orderable: false,
                    render: function (data, type, item) {
                        if ( item['file_sig'] === '' ) {
                            return `<a class="btn btn-primary">Подписать</a>`
                        } else {
                            return `<a class="btn btn-success">Отправить</a>`
                        }
                    }
                },
            ],
            language: dataTablesSettings.language,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Все"]],
            pageLength: 25,
            order: [[0, "desc"]],
            colReorder: true,
            dom: 'fBrt<"bottom"lip>',
            buttons: [],
            bSortCellsTop: true,
            scrollX: true,
            fixedHeader: false,
        });

        journalDataTable.columns().every(function () {
            $(this.header()).closest('thead').find('.search:eq(' + this.index() + ')').on('keyup change clear', function () {
                journalDataTable
                    .column($(this).parent().index())
                    .search(this.value)
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
            journalDataTable.draw()
        })

        $('.filter-btn-reset').on('click', function () {
            location.reload()
        })
    }
})