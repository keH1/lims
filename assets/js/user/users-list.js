$(function ($) {
    let body = $('body');
    let $journal = $('#journal_users');

    let userForUpdateRole = [];
    let userForUpdateDepartment = [];

    let usersUpdateRoleButton = $('.users-update-role-trigger');

    let permissionList = [];
    let departmentsList = [];

    let activeAjaxRequests = 0;

    $.ajax({
        type: 'GET',
        url: '/ulab/permission/getPermissionListAjax/',
        dataType: 'json',
        beforeSend: () => {
            activeAjaxRequests++;
            if (activeAjaxRequests > 0)
                $('#ajax-loading-message').show()

            $('#ajax-loading-message').trigger('toast-check');
        },
        complete: () => {
            activeAjaxRequests--;
            if (activeAjaxRequests <= 0)
                $('#ajax-loading-message').hide();

            $('#ajax-loading-message').trigger('toast-check');
        }
    })
    .then((dataPerm) => {
        permissionList = dataPerm;
        return $.ajax({
            type: 'GET',
            url: '/ulab/permission/getDepartmentsListAjax/',
            dataType: 'json',
            beforeSend: () => {
                activeAjaxRequests++;
                if (activeAjaxRequests > 0)
                    $('#ajax-loading-message').show()

                $('#ajax-loading-message').trigger('toast-check');
            },
            complete: () => {
                activeAjaxRequests--;
                if (activeAjaxRequests <= 0)
                    $('#ajax-loading-message').hide();

                $('#ajax-loading-message').trigger('toast-check');
            }
        });
    })
    .then((dataDept) => {
        departmentsList = dataDept;

        return  $.ajax({
            type: 'GET',
            url: '/ulab/permission/getCurrentUserPermissionAjax/',
            dataType: 'json',
            beforeSend: () => {
                activeAjaxRequests++;
                if (activeAjaxRequests > 0)
                    $('#ajax-loading-message').show()

                $('#ajax-loading-message').trigger('toast-check');
            },
            complete: () => {
                activeAjaxRequests--;
                if (activeAjaxRequests <= 0)
                    $('#ajax-loading-message').hide();

                $('#ajax-loading-message').trigger('toast-check');
            }
        })
    })
    .then((userData) => {
        const userId = userData['userId'];
        const uesrRole = userData['view_name'];
        
        let journalDataTable = $journal.DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            fixedHeader: true,
            scrollX: false,
            scrollCollapse: false,
            ajax: {
                type : 'POST',
                url : '/ulab/permission/getUsersAjax/',
                dataSrc: function (json) {
                    return json.data
                },
                beforeSend: () => {
                    activeAjaxRequests++;
                    if (activeAjaxRequests > 0)
                        $('#ajax-loading-message').show()

                    $('#ajax-loading-message').trigger('toast-check');
                },
                complete: () => {
                    activeAjaxRequests--;
                    if (activeAjaxRequests <= 0)
                        $('#ajax-loading-message').hide();

                    $('#ajax-loading-message').trigger('toast-check');
                }
            },
            columns: [
                {
                    data: 'LOGIN',
                    render: function (data, type, item) {
                        let currentUserId = BX.message("USER_ID");
                        if (item.ID == 1 && currentUserId != 1) {
                            return `<p style="margin-bottom: 0">${item.LOGIN}</a>`;
                        }
                        else {
                             return `<a href="#" class="user-edit" data-user-id="${item.ID}" 
                                title="Отредактировать пользователя" data-bs-toggle="tooltip" data-bs-trigger="hover">${item.LOGIN}</a>`;
                        }

                    }
                },
                {
                    data: 'FULL_NAME'
                },
                {
                    data: 'EMAIL',
                    render: $.fn.dataTable.render.ellipsis(30, true)
                },
               {
                    data: 'WORK_POSITION',
                    render: $.fn.dataTable.render.ellipsis(36, true)
                },
                 {
                    data: 'WORK_DEPARTMENT',
                    render: function (data, type, item) {
                        let formLayout = '';
                        formLayout += `<form id="updateDepartment-${item.ID}" class="card" action="/ulab/permission/updateDepartment/" method="post">`
                        formLayout += `<input type="hidden" name="user_id" value="${item.ID}">`;
                        formLayout += `<select name="department_id" class="form-control section department_id">`
                        formLayout += `<option value="" ${item.DEPARTMENT_ID == null ? 'selected' : ''}>Отдел не указан</option>`

                        let selectedDepartmentId = null;
                        const existingUser = userForUpdateDepartment.find(user => user.user_id === item.ID);
                        if (existingUser) {
                            selectedDepartmentId = existingUser.department_id;
                        } else {
                            selectedDepartmentId = item.DEPARTMENT_ID || null;
                        }

                        departmentsList.forEach(department => {
                           formLayout += `<option value="${department.ID}" ${department.ID === selectedDepartmentId ? 'selected' : ''}>${department.NAME}</option>`
                        })
                        formLayout += ` </select>`;
                        formLayout += `</form>`;

                        return formLayout;
                    }
                },
               {
                    data: 'permission_name',
                   render: function (data, type, item)
                   {
                        const isYourRole = userId == item.ID;
                        const isYouAdmin = uesrRole == 'admin';

                        const is_head_dept = item.IS_HEAD_DEPT ?? null;
                        let tooltip = '';
                        if (is_head_dept) {
                            tooltip = `title="Данный сотрудник является начальником отдела." data-bs-toggle="tooltip"`;
                        }
                        let formLayout = '';
                        formLayout += `<form ${tooltip} id="updateUser-${item.ID}" class="card" action="/ulab/permission/updateUser/" method="post">`
                        formLayout += `<input type="hidden" name="user_id" value="${item.ID}">`;
                        formLayout += `<select name="role_id" class="form-control section role_id" ${is_head_dept ? 'disabled' : ''} ${isYourRole || (!isYouAdmin && item['permission_view_name'] == 'admin') ? 'disabled' : ''}>`

                        let selectedRoleId = null;
                        const existingUser = userForUpdateRole.find(user => user.user_id == item.ID);
                        if (existingUser) {
                            selectedRoleId = existingUser.role_id;
                        } else {
                            selectedRoleId = item.permission_id || null;
                        }

                        permissionList.forEach(permission => {
                            formLayout += `<option value="${permission.id}" ${permission.id == selectedRoleId ? 'selected' : ''}>${permission.name}</option>`;
                        });

                        formLayout += ` </select>`;
                        formLayout += `</form>`;

                        return formLayout;
                    }
                }
            ],
            language:{
                processing: '<div class="processing-wrapper">Подождите...</div>',
                search: '',
                searchPlaceholder: "Поиск...",
                lengthMenu: 'Отображать _MENU_  ',
                info: 'Записи с _START_ до _END_ из _TOTAL_ записей',
                infoEmpty: 'Записи с 0 до 0 из 0 записей',
                infoFiltered: '(отфильтровано из _MAX_ записей)',
                infoPostFix: '',
                loadingRecords: 'Загрузка записей...',
                zeroRecords: 'Записи отсутствуют.',
                emptyTable: 'В таблице отсутствуют данные',
                paginate: {
                    first: 'Первая',
                    previous: 'Предыдущая',
                    next: 'Следующая',
                    last: 'Последняя'
                },
                aria: {
                    sortAscending: ': активировать для сортировки столбца по возрастанию',
                    sortDescending: ': активировать для сортировки столбца по убыванию'
                }
            },
            lengthMenu: [[10, 25, 50, 100, -1], [10,25, 50, 100, "Все"]],
            pageLength: 25,
            dom: 'frt<"bottom"lip>',
            colReorder: false,
            bSortCellsTop: true,
            order: [[ 0, 'asc' ]],
            drawCallback: function() {
                const tooltipUserTriggerList = document.querySelectorAll('.user-edit[data-bs-toggle="tooltip"]')
                const tooltipUserList = [...tooltipUserTriggerList].map(tooltipUserEl => new bootstrap.Tooltip(tooltipUserEl))

                $('.form-control.section.role_id').on('change', function (e) {
                    const formUser = e.currentTarget.parentElement;

                    const userId = formUser.querySelector('input[name="user_id"]').value;
                    const roleId = formUser.querySelector('select[name="role_id"]').value;

                    const existingIndex = userForUpdateRole.findIndex(user => user.user_id === userId);

                    if (existingIndex !== -1) {
                    // Если объект с таким user_id уже есть, обновляем его role_id
                        userForUpdateRole[existingIndex].role_id = roleId;
                    } else {
                    // Если объекта с таким user_id нет, добавляем новый объект
                        userForUpdateRole.push({ "user_id": userId, "role_id": roleId });
                    }

                    if (userForUpdateRole.length > 0 || userForUpdateDepartment.length > 0)
                        usersUpdateRoleButton.removeClass('disabled');
                    else
                        usersUpdateRoleButton.addClass('disabled');

                    const toast = new bootstrap.Toast($('.toast-save-permission'));
                    toast.show();
                });

                $('.form-control.section.department_id').on('change', function (e) {
                    const formUser = e.currentTarget.parentElement;

                    const userId = formUser.querySelector('input[name="user_id"]').value;
                    const departmentId = formUser.querySelector('select[name="department_id"]').value;

                    const existingIndex = userForUpdateDepartment.findIndex(user => user.user_id === userId);

                    if (existingIndex !== -1) {
                    // Если объект с таким user_id уже есть, обновляем его department_id
                        userForUpdateDepartment[existingIndex].department_id = departmentId;
                    } else {
                    // Если объекта с таким user_id нет, добавляем новый объект
                        userForUpdateDepartment.push({ "user_id": userId, "department_id": departmentId });
                    }

                    if (userForUpdateRole.length > 0 || userForUpdateDepartment.length > 0)
                        usersUpdateRoleButton.removeClass('disabled');
                    else
                        usersUpdateRoleButton.addClass('disabled');

                    const toast = new bootstrap.Toast($('.toast-save-permission'));
                    toast.show();
                });

                $('.card[data-bs-toggle="tooltip"]').each(function() {
                    $(this).tooltip();
                });
            },
        });

        let searchTimeouts = {};
        journalDataTable.columns().every(function () {
            let columnIndex = this.index();

            $(this.header()).closest('thead').find('.search:eq(' + columnIndex + ')').on('keyup change clear', function () {
                clearTimeout(searchTimeouts[columnIndex]);

                let inputElement = this;
                searchTimeouts[columnIndex] = setTimeout(function () {
                    journalDataTable
                        .column($(inputElement).parent().index())
                        .search(inputElement.value)
                        .draw();
                }, 500);
            });
        });

        /*journal filters*/
        $('.filter-btn-search').on('click', function () {
            $('#journal_filter').addClass('is-open')
            $('.filter-btn-search').hide()
        })

        $(document).on('click', function(event) {
            if (!$(event.target).closest('#filter_search').length && $('#filter_everywhere').val() === '') {
                $('#journal_filter').removeClass('is-open');
                $('.filter-btn-search').show();
            }
        });

        let debounceTimeout;
        $('.filter').on('change', function () {
           clearTimeout(debounceTimeout);

            debounceTimeout = setTimeout(function () {
                journalDataTable.ajax.reload()
            }, 350);
        })

        function reportWindowSize() {
            journalDataTable
                .columns.adjust()
        }

        window.onresize = reportWindowSize

        $('.filter-btn-reset').on('click', function () {
            location.reload()
        })
    })
    .catch(error => {
        // Обработка ошибок
    });

    usersUpdateRoleButton.click(function() {
        let oldText = usersUpdateRoleButton.text();
        usersUpdateRoleButton.text('Подождите');

         $.ajax({
            type: "POST",
            url: "/ulab/permission/updateUsersRole/",
            data: {
                array_update_users: userForUpdateRole
            },
            success: function(response) {
                $.ajax({
                    type: "POST",
                    url: "/ulab/permission/updateUsersDepartment/",
                    data: {
                        array_update_users: userForUpdateDepartment
                    },
                    success: function(response) {
                        usersUpdateRoleButton.text(oldText);
                        location.reload();
                    },
                    error: function(error) {
                        usersUpdateRoleButton.text('Произошла ошибка');
                        console.error("Ошибка запроса:", error);
                    }
                });
            },
            error: function(error) {
                usersUpdateRoleButton.text('Произошла ошибка');
                console.error("Ошибка запроса:", error);
            }
        });
    });


    function observeAjaxLoadingMessage() {
        const toastSaveMessage = $('.toast-save-permission');
        if (toastSaveMessage.is(':visible')) {
            $('#ajax-loading-message').css('margin-bottom', '94px');
        } else {
            $('#ajax-loading-message').css('margin-bottom', '0');
        }
    }

    $('.toast-save-permission').on('shown.bs.toast', () => observeAjaxLoadingMessage());
    $('.toast-save-permission').on('hidden.bs.toast', () => observeAjaxLoadingMessage());
    $('#ajax-loading-message').on('toast-check', () => observeAjaxLoadingMessage());

    $('.select2-users').select2({
        placeholder: 'Выберите должность',
        language: {
            noResults: () => "Ничего не найдено",
            searching: () => "Идет поиск...",
        },
        theme: 'bootstrap-5'
    });

    let isFormSubmitting = false

    function validateFullName(field) {
        let value = field.val(),
            hasDigits = /[0-9]/.test(value)
        
        if (hasDigits) {
            let parent = field.parent(),
                errorMessage = $('<div class="pop-up-error-message text-danger">В поле не должно быть цифр</div>')
            
            parent.find('.pop-up-error-message').remove()
            
            field.before(errorMessage)
            
            let newValue = value.replace(/[0-9]/g, '')
            field.val(newValue)
            
            setTimeout(function() {
                errorMessage.fadeOut(300, function() {
                    $(this).remove()
                })
            }, 3000)
            
            return true
        }
        
        return false
    }

    function validatePassword(password) {
        let value = password.val(),
            hasErrors = false,
            hasSpecialChars = /[,.<>/?;:'"[\]{}\\|`~!@#$%^&*()_+=\-]/.test(value),
            hasUpperCase = /[A-Z]/.test(value),
            hasDigits = /[0-9]/.test(value),
            passwordLabel = $('label[for="newPassword"]')
        
        // Проверка спецсимволов
        if (!hasSpecialChars) {
            let errorMessage = $('<div class="pop-up-error-message text-danger">Пароль должен содержать знаки пунктуации (,.<>/?;:\'"[]{}\|`~!@#$%^&*()_+=-)</div>')
            passwordLabel.before(errorMessage)
            hasErrors = true
        }
        
        // Проверка заглавных букв
        if (!hasUpperCase) {
            let errorMessage = $('<div class="pop-up-error-message text-danger">Пароль должен содержать латинские символы верхнего регистра (A-Z)</div>')
            passwordLabel.before(errorMessage)
            hasErrors = true
        }
        
        // Проверка цифр
        if (!hasDigits) {
            let errorMessage = $('<div class="pop-up-error-message text-danger">Пароль должен содержать цифры (0-9)</div>')
            passwordLabel.before(errorMessage)
            hasErrors = true
        }

        return hasErrors
    }

    function checkPasswordMatch() {
        let password = $('#newPassword'),
            confirmPassword = $('#newPasswordConfirm'),
            parent = confirmPassword.parent().parent()
        
        parent.parent().find('.pop-up-error-message').remove()
        
        if (confirmPassword.val().length > 0 && password.val() !== confirmPassword.val()) {
            let errorMessage = $('<div class="pop-up-error-message text-danger">Пароли не совпадают</div>')
            parent.before(errorMessage)
            return false
        }
        
        return true
    }

    function validateEmail(field) {
        let email = field.val(),
            emailPattern = /.+@.+\..+/i,
            isValid = emailPattern.test(email),
            parent = field.parent()
            
        parent.find('.pop-up-error-message').remove()
        
        if (!isValid && email.length > 0) {
            let errorMessage = $('<div class="pop-up-error-message text-danger">Введите корректный email</div>')
            field.before(errorMessage)
            return false
        }
        
        return true
    }

    $('#name, #lastName, #secondName').on('input', function() {
        validateFullName($(this))
    })
    
    $('#email').on('input', function() {
        validateEmail($(this))
    })
  
    $('#newPassword').on('input', function() {
        let field = $(this),
            value = field.val(),
            hasSpecialChars = /[,.<>/?;:'"[\]{}\\|`~!@#$%^&*()_+=\-]/.test(value),
            hasUpperCase = /[A-Z]/.test(value),
            hasDigits = /[0-9]/.test(value)
            
        if (hasSpecialChars && hasUpperCase && hasDigits) {
            let parent = field.parent().parent()
            parent.parent().find('.pop-up-error-message').remove()
        }
        
        if ($('#newPasswordConfirm').val().length > 0) {
            checkPasswordMatch()
        }
    })
    
    $('#newPasswordConfirm').on('input', function() {
        checkPasswordMatch()
    })
    
    $('#user-modal-form').on('submit', function(e) {
        if (isFormSubmitting) {
            e.preventDefault()
            return false
        }
        
        e.preventDefault()
        
        let password = $('#newPassword'),
            passwordConfirm = $('#newPasswordConfirm'),
            hasErrors = false,
            $form = $(this)
        
        $('.pop-up-error-message').remove()
        
        $('#name, #lastName, #secondName').each(function() {
            if (validateFullName($(this))) {
                hasErrors = true
            }
        })
        
        if (!validateEmail($('#email'))) {
            hasErrors = true
        }
        
        if (password.val().length > 0) {
            hasErrors = validatePassword(password)
        }
        
        // Проверка совпадения паролей
        if (passwordConfirm.val().length > 0 && password.val() !== passwordConfirm.val()) {
            let confirmLabel = $('label[for="newPasswordConfirm"]'),
                errorMessage = $('<div class="pop-up-error-message text-danger">Пароли не совпадают</div>')

            confirmLabel.before(errorMessage)
            hasErrors = true
        }
        
        if (hasErrors) {
            return false
        }
        
        let email = $('#email').val(),
            login = $('#login').val(),
            userId = $('#userId').val()
            
        isFormSubmitting = true
        
        $.ajax({
            type: 'POST',
            url: '/ulab/user/checkUsersDataAjax/',
            data: {
                email: email,
                login: login,
                user_id: userId
            },
            dataType: 'json',
            success: function(response) {
                let hasValidationErrors = response.length > 0

                if (hasValidationErrors) {
                    response.forEach(function(item) {
                        let fieldName = Object.keys(item).find(key => key !== 'error_messages'),
                            field = $('#' + fieldName),
                            parent = field.parent(),
                            errorMessage = $('<div class="pop-up-error-message text-danger">' + item.error_messages + '</div>')

                        parent.find('.pop-up-error-message').remove()
                        field.before(errorMessage)
                    })
                    
                    isFormSubmitting = false
                } else {
                    $('#user-modal-form').off('submit')
                    $form.submit()
                }
            },
            error: function() {
                $('.pop-up-error-message').remove()
                
                let formButton = $('.form-button'),
                    errorMessage = $('<div class="pop-up-error-message text-danger">Ошибка проверки данных</div>')
                
                formButton.before(errorMessage)
                
                isFormSubmitting = false
            }
        })
    })
})