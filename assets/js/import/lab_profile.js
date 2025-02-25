$(function ($) {
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: 'resolve',
    })

    let $journalRooms = $('#journal_rooms')
    let $journalUsers = $('#journal_users')

    let journalDataTableRooms = $journalRooms.DataTable({
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
                d.id = $('#lab_id').val()
            },
            url : '/ulab/import/getLabRoomsJournalAjax/',
            dataSrc: function (json) {
                return json.data
            }
        },
        columns: [
            {
                data: 'NAME',
                render: function (data, type, item) {
                    return `${item.NAME} ${item.NUMBER}`
                }
            },
            {
                data: 'control',
                width: '150px',
                render: function (data, type, item) {
                    return '<a href="#" class="edit_btn">Редактировать</a>'
                }
            },
        ],
        language: dataTablesSettings.language,
        lengthMenu: [[10, 25, 50, 100, -1], [10,25, 50, 100, "Все"]],
        pageLength: 25,
        order: [[ 0, "asc" ]],
        dom: 'frt<"bottom"lip>',
        buttons: dataTablesSettings.buttons,
    })

    let journalDataTableUsers = $journalUsers.DataTable({
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
                d.id = $('#lab_id').val()
            },
            url : '/ulab/import/getLabUsersJournalAjax/',
            dataSrc: function (json) {
                return json.data
            }
        },
        columns: [
            {
                data: 'NAME',
                render: function (data, type, item) {
                    return `${item.LAST_NAME} ${item.NAME}`
                }
            },
            {
                data: 'control',
                width: '150px',
                render: function (data, type, item) {
                    return '<a href="#" class="edit_btn">Редактировать</a>'
                }
            },
        ],
        language: dataTablesSettings.language,
        lengthMenu: [[10, 25, 50, 100, -1], [10,25, 50, 100, "Все"]],
        pageLength: 25,
        order: [[ 0, "asc" ]],
        dom: 'frt<"bottom"lip>',
        buttons: dataTablesSettings.buttons,
    })
})