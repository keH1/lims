$(function ($) {
    const $body = $('body')

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
                data: 'status',
            },
            {
                data: 'replace_user',
            },
            {
                data: 'control',
                width: '150px',
                orderable: false,
                render: function (data, type, item) {
                    return `
                        <a href="#" class="unbound_btn">Отвязать</a>
                        <a href="#popup_form_users_edit" class="popup-with-form" 
                           data-user-id="${item.ID}" 
                           data-position-id="${item.WORK_POSITION}"
                           data-status-id="${item.status_id}"
                           data-replace-id="${item.replacement_user_id}">Редактировать</a>`
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

    $('.popup-with-form[href="#popup_form_users"]').on('click', function() {
        $.magnificPopup.open({
            items: {
                src: '#popup_form_users',
            },
            type: 'inline',
            closeBtnInside: true,
            closeOnBgClick: false,
            fixedContentPos: false,
            callbacks: {
                open: function() {
                    initUserPositionInteraction()
                },
                afterClose: function() {
                    $('#popup_form_users').find('select').val('').trigger('change')
                }
            }
        })

        return false
    })

    $body.on('click', '.popup-with-form[href="#popup_form_users_edit"]', function() {
        const userId = $(this).data('user-id'),
              positionId = $(this).data('position-id'),
              statusId = $(this).data('status-id'),
              replaceId = $(this).data('replace-id') ?? ''

        $.magnificPopup.open({
            items: {
                src: '#popup_form_users_edit',
            },
            type: 'inline',
            closeBtnInside: true,
            closeOnBgClick: false,
            fixedContentPos: false,
            callbacks: {
                open: function() {
                    initUserPositionInteraction(true)
                },
                beforeOpen: function() {
                    $('#popup_form_users_edit select[name="user_id"]').val(userId).trigger('change')
                    $('#popup_form_users_edit select[name="position"]').val(positionId).trigger('change')
                    $('#popup_form_users_edit select[name="status"]').val(statusId).trigger('change')
                    $('#popup_form_users_edit select[name="replace"]').val(replaceId).trigger('change')
                }
            }
        })

        return false
    })

    /**
     * @desc Инициализирует обработчики выбора пользователя, должности, статуса и заменяемого пользователя
     */
    function initUserPositionInteraction(isEdit = false) {
        const $userSelect = $('#form_entity_user_id'),
              $positionSelect = $('#form_entity_position'),
              $statusSelect = $('#form_entity_status'),
              $replaceSelect = $('#form_entity_replace'),
              originalPositionsHTML = $positionSelect.html(),
              originalUsersHTML = $userSelect.html()
        
        if (!isEdit) {
            $userSelect.val('')
            $positionSelect.val('').prop('disabled', false)
            $statusSelect.val('')
            $replaceSelect.val('').prop('disabled', true)
        }
        
        $userSelect.off('change')
        $positionSelect.off('change')
        $statusSelect.off('change')
        
        $statusSelect.on('change', function() {
            const statusValue = $(this).val()
            
            if (statusValue === '1') {
                $replaceSelect.val('').prop('disabled', true)
            } else if (statusValue === '2' || statusValue === '3') {
                $replaceSelect.prop('disabled', false)
            } else {
                $replaceSelect.val('').prop('disabled', true)
            }
            
            $replaceSelect.select2('destroy').select2({
                theme: 'bootstrap-5'
            })
        })
        
        if (!isEdit) {
            $userSelect.on('change', function() {
                const userId = $(this).val()
                
                if (userId) {
                    const position = $(this).find('option:selected').data('position')
                    
                    $positionSelect.html(`<option value="${position}">${position}</option>`)
                    $positionSelect.val(position).prop('disabled', true)
                } else {
                    $positionSelect.html(originalPositionsHTML)
                    $positionSelect.val('').prop('disabled', false)
                }
                
                $positionSelect.select2('destroy').select2({
                    theme: 'bootstrap-5'
                })
            })
            
            $positionSelect.on('change', function() {
                if ($(this).prop('disabled')) return
                
                const position = $(this).val(),
                      currentUserId = $userSelect.val(),
                      $temp = $('<div>').html(originalUsersHTML)

                let hasCurrentUser = false
                
                $userSelect.empty().append('<option value="">Не выбран</option>')
                
                $temp.find('option').each(function() {
                    const $option = $(this),
                          value = $option.val(),
                          userPosition = $option.data('position')
                    
                    if (!value) return true
                    
                    if (!position || userPosition === position) {
                        $userSelect.append($option.clone())
                        
                        if (value === currentUserId)
                            hasCurrentUser = true
                    }
                })
                
                $userSelect.val(hasCurrentUser ? currentUserId : '')
                
                $userSelect.select2('destroy').select2({
                    theme: 'bootstrap-5'
                })
            })
        }
        
        $userSelect.add($positionSelect).add($statusSelect).add($replaceSelect).select2({
            theme: 'bootstrap-5'
        })
        
        if (isEdit) {
            $statusSelect.trigger('change')
        }
    }

    journalDataTableUsers.on('click', '.unbound_btn', function () {
        let data = journalDataTableUsers.row($(this).closest('tr')).data()

        if ( confirm("Отвязать пользователя от данной лаборатории?") ) {
            $.ajax({
                method: 'POST',
                url: '/ulab/import/deleteAffiliationUserAjax/',
                data: {
                    user_id: data.ID
                },
                success: function() {
                    const $userSelect = $('#popup_form_users #form_entity_user_id')
                    
                    if ($userSelect.find(`option[value="${data.ID}"]`).length === 0) {
                        const $options = $userSelect.find('option').filter(function() {
                            return $(this).val() !== ''
                        })
                        
                        const $newOption = $(`<option value="${data.ID}" data-position="${data.WORK_POSITION}">${data.NAME} ${data.LAST_NAME}</option>`)
                        
                        if ($options.length === 0) {
                            $userSelect.append($newOption)
                        } else {
                            let inserted = false
                            
                            // Вставка по алфавиту
                            $options.each(function() {
                                const optionText = $(this).text(),
                                      newOptionText = $newOption.text()
                                
                                if (newOptionText.localeCompare(optionText) < 0) {
                                    $(this).before($newOption)
                                    inserted = true
                                    return false
                                }
                            })
                            
                            if (!inserted) {
                                $userSelect.append($newOption)
                            }
                        }
                    }
                },
                complete: function () {
                    journalDataTableUsers.ajax.reload()
                }
            })
        }

        return false
    })
})