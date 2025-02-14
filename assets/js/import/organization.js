$(function ($) {
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: 'resolve',
    })

    let $journal = $('#journal_branch')

    /*journal requests*/
    let journalDataTable = $journal.DataTable({
        bAutoWidth: false,
        autoWidth: false,
        fixedColumns: false,
        processing: true,
        serverSide: true,
        bSortCellsTop: true,
        scrollX: true,
        fixedHeader: false,
        colReorder: true,
        ajax: {
            type : 'POST',
            data: function ( d ) {
                d.id = $('#org_id').val()
            },
            url : '/ulab/import/getBranchJournalAjax/',
            dataSrc: function (json) {
                return json.data
            }
        },
        columns: [
            {
                data: 'name',
                render: function (data, type, item) {
                    return `<a href="/ulab/import/branch/${item.id}">${item.name}</a>`
                }
            },
            {
                data: 'control',
                width: '150px',
                orderable: false,
                render: function (data, type, item) {
                    return '<a href="#" class="edit_btn">Редактировать</a>'
                }
            },
        ],
        language: dataTablesSettings.language,
        lengthMenu: [[10, 25, 50, 100, -1], [10,25, 50, 100, "Все"]],
        pageLength: 25,
        order: [[ 0, "asc" ]],
        dom: 'frtB<"bottom"lip>',
        buttons: dataTablesSettings.buttons,
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
        journalDataTable.draw()
    })

    journalDataTable.on('click', '.edit_btn', function () {
        let $form = $('#popup_form')
        let data = journalDataTable.row($(this).closest('tr')).data()

        $.magnificPopup.open({
            items: {
                src: '#popup_form',
            },
            type: 'inline',
            closeBtnInside: true,
            fixedContentPos: false,
            callbacks: {
                beforeOpen: function() {
                    $form.find('#form_entity_name').val(data.name)
                    $form.find('#form_entity_id').val(data.id)
                    $form.find('#form_entity_head').val(data.head_user_id).trigger('change')
                },
                afterClose: function() {
                    $form.find('#form_entity_name').val('')
                    $form.find('#form_entity_id').val('')
                    $form.find('#form_entity_head').val('')
                }
            }
        })
    })
})