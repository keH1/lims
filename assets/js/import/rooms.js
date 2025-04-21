/**
 * Карточка несение сведений об отделах и помещениях
 */
$(function ($) {
    const body = $('body')

    let $journal = $('#rooms-table')
    let journalDataTable = $journal.DataTable({
        processing: true,
        serverSide: true,
        colReorder: true,
        bSortCellsTop: true,
        scrollX: true,
        ajax: {
            type : 'POST',
            data: function ( d ) {
                d.laboratory = $('#labs option:selected').val()
            },
            url : '/ulab/lab/getRoomsListForLabAjax/',
            dataSrc: function (json) {
                return json.data
            }
        },
        columns: [
            {
                data: 'NUMBER'
            },
            {
                data: 'NAME'
            },
            {
                data: 'SPEC',
                render: function(data, type, row) {
                    return data == 0 ? 'Специальное' : 'Приспособленное'
                }
            },
            {
                data: 'PURPOSE'
            },
            {
                data: 'AREA'
            },
            {
                data: 'PARAMS'
            },
            {
                data: 'SPEC_EQUIP'
            },
            {
                data: 'DOCS'
            },
            {
                data: 'PLACEMENT'
            },
            {
                data: 'COMMENT'
            },
            {
                data: null,
                orderable: false,
                render: function (data, type, item) {
                    return `
                        <button type="button" class="btn btn-fill btn-square room-edit" title="${item['NUMBER']}"
                                data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top"
                                data-room-id="${item['ID']}">
                                    <i class="fa-solid fa-pencil icon-fix"></i>
                        </button>`
                }
            },
        ],
        language: {
            ...dataTablesSettings.language,
            zeroRecords: "Здесь отобразятся помещения, если они есть"
        },
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Все"]],
        pageLength: 25,
        dom: 'frt<"bottom"lip>',
        columnDefs: [
            {
                targets: '_all',
                className: 'text-center'
            }
        ]
    })

    journalDataTable.columns().every(function() {
        let timeout
        $(this.header()).closest('thead').find('.search:eq('+ this.index() +')').on('keyup change clear', function() {
            clearTimeout(timeout)
            const searchValue = this.value
            timeout = setTimeout(function() {
                journalDataTable
                    .column($(this).parent().index())
                    .search(searchValue)
                    .draw()
            }.bind(this), 1000)
        })
    })

    $('.filter-btn-search').on('click', function () {
        $('#journal_filter').addClass('is-open')
        $('.filter-btn-search').hide()
    })

    $('.filter').on('change', function () {
        journalDataTable.ajax.reload()
    })

    $('#labs').on('change', function() {
        let $panelDefault = $('.rooms-block'),
            $panelBody = $panelDefault.find('.panel-body'),
            $panelIcon = $panelDefault.find('.panel-heading a')

        if ($panelBody.css('display') === 'none') {
            $panelBody.css('display', '')
            
            if ($panelIcon.hasClass('fa-chevron-down')) {
                $panelIcon.removeClass('fa-chevron-down').addClass('fa-chevron-up')
            }
        }
        
        let selectedLabId = $(this).val()
        $('#labId').val(selectedLabId)
    })

    function reportWindowSize() {
        journalDataTable
            .columns.adjust()
    }

    window.onresize = reportWindowSize

    $('.filter-btn-reset').on('click', function () {
        location.reload()
    })

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

        if (positionScroll > 265 && positionScroll < tableScrollBody) {
            $('.arrowRight').css('transform',`translateY(${positionScroll-260}px)`);
            $('.arrowLeft').css('transform',`translateY(${positionScroll-250}px)`);
        }
    })

    body.on('click', '.popup-with-form', function () {
        $.magnificPopup.open({
            items: {
                src: '#room-modal-form',
                type: 'inline'
            },
            fixedContentPos: false,
            fixedBgPos: true,
            closeOnBgClick: false,
            callbacks: {
                open: function() {
                    const roomModalForm = $('#room-modal-form');
                    roomModalForm.find('.form-button').text('Добавить помещение');
                    roomModalForm.find('#title-type').text('Добавить помещение');

                    if (roomModalForm.find('#roomId').val() != '')
                    {
                        roomModalForm.find('#roomId').val('');
                        roomModalForm.find('#number').val('');
                        roomModalForm.find('#name').val('');
                        roomModalForm.find('#spec').val('');
                        roomModalForm.find('#purpose').val('');
                        roomModalForm.find('#area').val('');
                        roomModalForm.find('#params').val('');
                        // roomModalForm.find('#specEquip').val('');
                        roomModalForm.find('#docs').val('');
                        roomModalForm.find('#placement').val('');
                        roomModalForm.find('#comment').val('');

                        roomModalForm.find('.room-delete').remove();
                    }

                    $.ajax({
                        method: 'POST',
                        url: '/ulab/import/getUnboundOborudAjax',
                        dataType: "json",
                        success: function (data) {
                            updateSelects(data['equipment_storaged'], [], 0);
                        },
                        error: function (jqXHR, exception) {
                            let msg = '';
                            if (jqXHR.status === 0) {
                                msg = 'Not connect.\n Verify Network.';
                            } else if (jqXHR.status === 404) {
                                msg = 'Requested page not found. [404]';
                            } else if (jqXHR.status === 500) {
                                msg = 'Internal Server Error [500].';
                            } else if (exception === 'parsererror') {
                                msg = '1 Requested JSON parse failed.';
                            } else if (exception === 'timeout') {
                                msg = 'Time out error.';
                            } else if (exception === 'abort') {
                                msg = 'Ajax request aborted.';
                            } else {
                                msg = 'Uncaught Error.\n' + jqXHR.responseText;
                            }
                            console.log(msg)
                        }
                    });
                }
            }
        })
    })

    /**
     * обновить данные помещения
     */
    body.on('click', '.room-edit', function () {
        let roomId = $(this).data('roomId');

        let editButton = $(this);
        editButton.find('i').addClass('fa-spinner');
        editButton.find('i').removeClass('fa-pencil');
        editButton.find('i').removeClass('fa-xmark');

        $.ajax({
            method: 'POST',
            url: '/ulab/import/getRoomAjax',
            data: {
                id: roomId
            },
            dataType: "json",
            success: function (data) {
                editButton.find('i').removeClass('fa-spinner');
                editButton.find('i').addClass('fa-pencil');

                if (data['ID']) {
                    const roomModalForm = $('#room-modal-form');

                    roomModalForm.find('.form-button').text('Сохранить помещение');
                    roomModalForm.find('#title-type').text('Редактировать помещение');

                    roomModalForm.find('#roomId').val(roomId);
                    //roomModalForm.find('#labId').val(data['LAB_ID']);
                    roomModalForm.find('#number').val(data['NUMBER']);
                    roomModalForm.find('#name').val(data['NAME']);
                    roomModalForm.find('#spec').val(data['SPEC']);
                    roomModalForm.find('#purpose').val(data['PURPOSE']);
                    roomModalForm.find('#area').val(data['AREA']);
                    roomModalForm.find('#params').val(data['PARAMS']);
                    //roomModalForm.find('#specEquip').val(data['SPEC_EQUIP']);
                    roomModalForm.find('#docs').val(data['DOCS']);
                    roomModalForm.find('#placement').val(data['PLACEMENT']);
                    roomModalForm.find('#comment').val(data['COMMENT']);

                    updateSelects(data['equipment_storaged'], [], roomId);

                    roomModalForm.find('.room-delete').remove();
                    roomModalForm.append(`<button type="button" class="btn btn-danger ms-2 room-delete" 
                        data-room-id="${roomId}">Удалить</button>`);

                    $.magnificPopup.open({
                        items: {
                            src: roomModalForm,
                            type: 'inline',
                            fixedContentPos: false
                        },
                        closeOnBgClick: false,
                    });
                }
            },
            error: function (jqXHR, exception) {
                editButton.find('i').removeClass('fa-spinner');
                editButton.find('i').addClass('fa-xmark');

                let msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status === 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status === 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = '1 Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }
                console.log(msg)
            }
        });
    });

    /**
     * удалить данные помещения
     */
    body.on('click', '.room-delete', function () {
        let roomId = $(this).data('roomId');

        if (!confirm('Вы действительно хотите удалить помещение? После удаления, данные нельзя будет востановить')) {
            return false;
        }

        const roomModalForm = $('#room-modal-form');
        let oldText = roomModalForm.find('.room-delete').text();
        roomModalForm.find('.room-delete').text('Подождите, происходит удаление');
        roomModalForm.find('.form-button').addClass('disabled');

        $.ajax({
            method: 'POST',
            url: '/ulab/import/deleteRoomAjax',
            data: {
                id: roomId
            },
            dataType: "json",
            success: function (data) {
                $.magnificPopup.close()

                if (data['success']) {
                    roomModalForm.find('.room-delete').text(oldText);
                    roomModalForm.find('.form-button').removeClass('disabled');
                    journalDataTable.ajax.reload()
                } else {
                    $('.alert-title').text('Внимание!')
                    $('.alert-content').text(data['error']['message'])

                    roomModalForm.find('.form-button').text('Ошибка');
                    roomModalForm.find('.form-button').removeClass('disabled');

                    $.magnificPopup.open({
                        items: {
                            src: $('#alert_modal'),
                            type: 'inline',
                            fixedContentPos: false
                        },
                        closeOnBgClick: false,
                    })

                    return false
                }
            },
            error: function (jqXHR, exception) {
                let msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status === 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status === 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = '1 Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }
                console.log(msg)
            }
        });
    });

    $('#room-modal-form').on('submit', function(e) {
        e.preventDefault()
        
        $(this).find('.form-button').text('Пожалуйста подождите');
        $(this).find('.form-button').addClass('disabled');

        let roomDeleteButton = $(this).find('.room-delete');
        if (roomDeleteButton.length) {
            roomDeleteButton.remove();
        }
        
        let formData = $(this).serialize()
        let roomId = $('#roomId').val()
        
        $.ajax({
            method: 'POST',
            url: '/ulab/import/insertUpdateRoom/' + roomId,
            data: formData,
            dataType: "json",
            success: function(data) {
                $.magnificPopup.close()
                
                if (data.success) {
                    journalDataTable.ajax.reload()
                } else if (data.error) {
                    console.error(data.error)
                }
            },
            error: function(xhr, status, error) {
                console.error('Ошибка при сохранении: ' + error)
            }
        })
    })

    function updateSelects (equipment_storaged, equipment_operating, room_id) {
        const roomModalForm = $('#room-modal-form'),
              selectStoraged = roomModalForm.find('#equipment_storaged')

        selectStoraged.empty();

        let storagedOptions = '<option value="" disabled>Не выбрано</option>';

        if(room_id === 0) {
            $.each(equipment_storaged, function (key, value) {
                storagedOptions += `
                <option value="${value['id']}">
                    ${value['name']}
                </option>
            `;
            })
        }else {
            $.each(equipment_storaged, function (key, value) {
                storagedOptions += `
                <option value="${value['id']}" ${Number(value['id_storage_room']) === Number(room_id) ? 'selected="selected"' : ''}>
                    ${value['name']}
                </option>
            `;
            })
        }

        selectStoraged.append(storagedOptions);
        selectStoraged.select2({ theme: "bootstrap-5" });
    }

    journalDataTable.on('draw.dt', function() {
        journalDataTable.columns.adjust()
    })
});
