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
                data: 'WORK_POSITION',
            },
            {
                data: 'control',
                width: '150px',
                orderable: false,
                render: function (data, type, item) {
                    return '<a href="#" class="unbound_btn">Отвязать</a>'
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

    journalDataTableRooms.on('click', '.edit_btn', function () {
        let $form = $('#popup_form_rooms')
        let data = journalDataTableRooms.row($(this).closest('tr')).data()

        $.magnificPopup.open({
            items: {
                src: '#popup_form_rooms',
            },
            type: 'inline',
            closeBtnInside: true,
            closeOnBgClick: false,
            fixedContentPos: false,
            callbacks: {
                beforeOpen: function() {
                    $form.find('#form_entity_name').val(data.NAME)
                    $form.find('#form_entity_number').val(data.NUMBER)
                    $form.find('#form_entity_id').val(data.ID)
                },
                afterClose: function() {
                    $form.find('#form_entity_name').val('')
                    $form.find('#form_entity_number').val('')
                    $form.find('#form_entity_id').val('')
                }
            }
        })

        return false
    })


    journalDataTableUsers.on('click', '.edit_btn', function () {
        $.magnificPopup.open({
            items: {
                src: '#popup_form_users',
            },
            type: 'inline',
            closeBtnInside: true,
            closeOnBgClick: false,
            fixedContentPos: false,
        })

        return false
    })

    journalDataTableUsers.on('click', '.unbound_btn', function () {
        let data = journalDataTableUsers.row($(this).closest('tr')).data()

        if ( confirm("Отвязать пользователя от данной лаборатории?") ) {
            $.ajax({
                method: 'POST',
                url: '/ulab/import/deleteAffiliationUserAjax/',
                data: {
                    user_id: data.ID
                },
                complete: function () {
                    journalDataTableUsers.ajax.reload()
                    journalDataTableUsers.draw()
                }
            })
        }

        return false
    })
})