function initDataTable(selector, options) {
    const defaultOptions = {
        processing: true,
        serverSide: true,
        scrollX: true,
        bSortCellsTop: true,
        fixedHeader: false,
        colReorder: true,
        fixedColumns: false,
        // bAutoWidth: false,
        // autoWidth: false,

        // processing: true,
        // serverSide: true,
        // scrollX: true,
        // bSortCellsTop: true,
        // fixedHeader: false
    }
    
    const tableOptions = $.extend(true, {}, defaultOptions, options)
    const dataTable = $(selector).DataTable(tableOptions)

    setupTableResizeHandlers()
    
    // dataTable.on('column-visibility.dt', function(e, settings, column, state) {
    //     dataTable.columns().every(function() {
    //         if (this.visible()) {
    //             $(this.header()).css('width', $(this.header()).width() + 'px')
    //             $(this.footer()).css('width', $(this.header()).width() + 'px')
    //         }
    //     })
        
    //     dataTable.columns.adjust().draw()
    // })
   
    return dataTable
}

// window.initDataTable = initDataTable

/**
 * Настройка поиска по колонкам в DataTable
 * @param {Object} dataTable - DataTable
 */
window.setupDataTableColumnSearch = function(dataTable) {
    dataTable.columns().every(function() {
        let timeout
        $(this.header()).closest('thead').find('.search:eq('+ this.index() +')').on('keyup change clear', function() {
            clearTimeout(timeout)
            const searchValue = this.value
            timeout = setTimeout(function() {
                dataTable
                    .column($(this).parent().index())
                    .search(searchValue)
                    .draw()
            }.bind(this), 1000)
        })
    })
}

/**
 * Настройка обработчиков для фильтров журнала
 * @param {Object} dataTable - DataTable
 */
window.setupJournalFilters = function(dataTable) {
    $('.filter-btn-search').on('click', function() {
        $('#journal_filter').addClass('is-open')
        $('.filter-btn-search').hide()
    })

    $('.filter').on('change', function() {
        dataTable.ajax.reload()
    })

    $('.filter-btn-reset').on('click', function() {
        location.reload()
    })
}

/**
 * Настройка обработчика изменения размера окна для таблицы
 * @param {Object} dataTable - DataTable
 */
window.adjustmentColumnsTable = function(dataTable) {
    alert('adjustmentColumnsTable')
    if (!dataTable) {
        return
    }
    
    window.onresize = function() {
        dataTable.columns.adjust()
    }
}

function setupTableResizeHandlers() {
    /**
     * @desc Обновляет все таблицы на странице при изменении размера окна
     */
    function refreshAllTables() {
        $('table.dataTable').each(function() {
            const tableId = $(this).attr('id')
            
            try {
                const dataTable = $(this).DataTable()
                
                if (typeof dataTable.draw === 'function') {
                    $(this).css('width', '100%')
                    $(this).closest('.dataTables_wrapper').find('.dataTables_scrollHeadInner, .dataTables_scrollBody table').css({
                        'width': '100%',
                        'max-width': '100%'
                    })
                    
                    dataTable.columns.adjust()
                }
            } catch (e) {
                console.log("Ошибка c таблицей ", tableId, e)
            }
        })
    }
    
    // Первоначальная загрузка таблиц
    setTimeout(function() {
        refreshAllTables()
    }, 100)
    
    $(window).on('resize', function() {
        clearTimeout(window.resizeTimer)
        window.resizeTimer = setTimeout(refreshAllTables, 200)
    })
}

const dataTablesSettings = {
    buttons:[
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
    buttonPrint:[
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
        buttons: {
            colvis: '',
            copy: '',
            excel: '',
            print: '',
            copyTitle: 'Копирование в буфер обмена',
            copySuccess: {
                _: 'Скопировано %d строк в буфер обмена',
                1: 'Скопирована 1 строка в буфер обмена'
            }
        },
        aria: {
            sortAscending: ': активировать для сортировки столбца по возрастанию',
            sortDescending: ': активировать для сортировки столбца по убыванию'
        }
    },
}

const TOKEN = "94cc68d21383486a9bd647ab5fe50c3a781339ff0a0203617fa99366660f0015";

const URI = '/ulab'

function isNumeric(num) {
    return !isNaN(parseFloat(num)) && isFinite(num);
}

escapeHtml = function (text) {
    let map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };

    if (text) {
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    return false;
}

// Скролл к первой ошибке
function scrollToFirstError() {
    const $firstError = $('.is-invalid').first()
    if ($firstError.length) {
        $('html, body').animate({
                scrollTop: $firstError.offset().top - 100
            },
            500,
            function () {
                $firstError.focus()
            })
    }
}

/**
 * Инициализирует стрелочки для горизонтальной прокрутке таблицы
 *
 * @param {string} [wrapperSelector=''] CSS‑селектор обёртки DataTables (обычно '.dataTables_scroll').
 * Внутрь неё будут добавлены кнопки-стрелки.
 * @param {string} [scrollContainerSelector='']  CSS‑селектор прокручиваемой области таблицы (обычно '.dataTables_scrollBody').
 * @param {string} [buttonPrefix=''] Префикс для классов стрелок. Если нужно инициализировать
 * сразу несколько таблиц на странице, можно установить уникальный префикс, чтобы не было пересечений.
 *
 * Как использовать:
 *   По завершении инициализации DataTables (события 'init.dt' и 'draw.dt') просто вызвать:
 *     journalDataTable.on('init.dt draw.dt', () => initTableScrollNavigation());
 */
function initTableScrollNavigation(wrapperSelector = '', scrollContainerSelector = '', buttonPrefix = '') {
    const arrowRightClass = `${buttonPrefix}arrowRight`;
    const arrowLeftClass = `${buttonPrefix}arrowLeft`;

    const $wrapper = $(wrapperSelector || '.dataTables_scroll');
    const $scrollContainer = $(scrollContainerSelector || '.dataTables_scrollBody');
    const BUTTONS_GAP = 60;
    const scrollStep = $scrollContainer.width();

    let animationFrame = null;

    if (!$wrapper.find(`.${arrowRightClass}`).length) {
        $wrapper.append(`
            <div class="${arrowLeftClass} arrow position-absolute right--0-95">
                <svg class="bi" width="40" height="40">
                    <use xlink:href="/ulab/assets/images/icons.svg#arrow-left"/>
                </svg>
            </div>
            <div class="${arrowRightClass} arrow position-absolute right--0-95">
                <svg class="bi" width="40" height="40">
                    <use xlink:href="/ulab/assets/images/icons.svg#arrow-right"/>
                </svg>
            </div>
        `);
    }

    const $arrowRight = $wrapper.find(`.${arrowRightClass}`);
    const $arrowLeft  = $wrapper.find(`.${arrowLeftClass}`);

    const updatePositions = () => {
        const wrapperTop      = $wrapper.offset().top;
        const containerTop    = $scrollContainer.offset().top;
        const containerHeight = $scrollContainer.outerHeight();

        const minTopOffset = (containerTop - wrapperTop) + 10;

        const buttonHeight  = $arrowRight.outerHeight();
        const maxTranslate  = containerHeight - buttonHeight * 2 - BUTTONS_GAP;

        const scrollTop = $(window).scrollTop();

        let translateY = scrollTop - containerTop + minTopOffset;
        translateY = Math.max(minTopOffset, Math.min(translateY, minTopOffset + maxTranslate));

        const visibility = maxTranslate > 0 ? 'visible' : 'hidden';

        $arrowRight.css({ top: `${translateY}px`, visibility });
        $arrowLeft.css({
            top: `${translateY + BUTTONS_GAP}px`,
            visibility
        });
    };

    updatePositions();

    const requestUpdate = () => {
        cancelAnimationFrame(animationFrame);
        animationFrame = requestAnimationFrame(updatePositions);
    };

    $(window).on('scroll resize', requestUpdate);
    new ResizeObserver(requestUpdate).observe($scrollContainer[0]);

    $arrowRight.hover(
        () => $scrollContainer.stop().animate({ scrollLeft: `+=${scrollStep}` }, 4000, 'linear'),
        () => $scrollContainer.stop()
    );

    $arrowLeft.hover(
        () => $scrollContainer.stop().animate({ scrollLeft: `-=${scrollStep}` }, 4000, 'linear'),
        () => $scrollContainer.stop()
    );
}



$(function ($) {
    $('.popup-help').magnificPopup({
        type: 'iframe',
        mainClass: 'white-popup',
        closeOnBgClick: false,
    })

    $('body').find('.popup-with-form').magnificPopup({
        type: 'inline',
        closeBtnInside: true,
        fixedContentPos: false,
        closeOnBgClick: false,
        focus: '#focus-blur-loop-select',
        midClick: true,
        callbacks: {
            open: function() {
                this.popupId = $(this.st.el).attr('href')

                $('.mfp-content').css({
                    'max-height': '90vh',
                    'overflow-y': 'auto'
                })

                $('body').css('overflow', 'hidden')

                $('.mfp-bg').on('click', function() {
                    $.magnificPopup.close()
                })
            },
            close: function() {
                $('body').css('overflow', 'auto')

                $('.mfp-content').css({
                    'max-height': 'none',
                    'overflow-y': 'visible'
                })
            },
            afterClose: function() {
                $('#add-certificate-modal-form')[0]?.reset()
                $('#add-work-modal-form')[0]?.reset()
                // $(this.popupId)[0]?.reset()
                
                if (this.popupId) {
                    $(this.popupId).find('select.select2').each(function() {
                        $(this).val('').trigger('change')
                    })
                }
            }
        }
    })

    // panel collapsible
    $('.panel .panel-heading').click(function () {
        let el = $(this).parents(".panel").children(".panel-body")
        if ($(this).find('a').hasClass("fa-chevron-up")) {
            $(this).find('a').removeClass("fa-chevron-up").addClass("fa-chevron-down")
            el.slideUp(500)
        } else {
            $(this).find('a').removeClass("fa-chevron-down").addClass("fa-chevron-up")
            el.slideDown(500)
        }

        return false
    })

    let $body = $("body")
    $body.on("change keyup input click", "input.number-only", function() {
        const cleanedValue = this.value.replace(REGEX.NUMBERS_ONLY, '')
        this.value = cleanedValue
    })

    //счет оферта
    $('input[name="order_type"]').change(function () {
        let check = $(this).val(),
            order = $body.find('.order-type'),
            select = order.find('select[name="NUM_DOGOVOR"]')

        if (check == 2) {
            order.addClass('visually-hidden')
            select.find('option').prop('selected', false)
        } else {
            order.removeClass('visually-hidden')
        }
    });

    // копирует в буффер из дата элемента
    $body.on('click', 'a[data-text-clipboard]', function () {
        const $parent = $(this).parent()
        const text = $(this).data('text-clipboard')
        const $temp = $("<div>")

        $parent.append($temp)

        $temp.attr("contenteditable", true)
            .html(text).select()
            .on("focus", function() { document.execCommand('selectAll',false,null); })
            .focus();
        document.execCommand("copy");
        $temp.remove();

        $(this).addClass('clipboard-tooltip')
        $(this).append(`<span class="tooltiptext rounded">Скопировано</span>`)

        return false
    })

    $body.on('mouseover', 'a[data-text-clipboard]', function () {
        $(this).removeClass('clipboard-tooltip')
        $(this).find('.tooltiptext').remove()
    })

    $body.on('click', '.disable-after-click', function () {
        $(this).addClass('disabled')
    })
    
    $body.on("click", ".form-group button.remove_this", function () {
        let $formGroupContainer = $(this).parents('.form-group')
        $formGroupContainer.remove()
    })

    $body.on('input', 'input[list]', function(e) {
        let $input = $(e.target),
            $options = $('#' + $input.attr('list') + ' option'),
            $hiddenInput = $('#' + $input.attr('id') + '-hidden'),
            label = $input.val().trim()

        for (let i = 0; i < $options.length; i++) {
            let $option = $options.eq(i)

            if ($option.text().trim() === label) {
                $hiddenInput.val( $option.attr('data-value') )
                break
            } else {
                $hiddenInput.val('')
            }
        }
    })

    $body.on('change', '.assigned-select', function(e) {
        let $select = $(e.target),
            $hiddenInput = $('#' + $select.attr('id') + '-hidden')

        $hiddenInput.val($($select).val())
        $(this).parents('.form-group').find('.add_assigned').removeAttr('disabled')
    })

    let companyId = $('input[name="company_id"]').val()
    if ( companyId !== '' ) {
        $('#company').val($(`#company_list option[data-value="${companyId}"]`).text())
    }

    let $inputMaterial = $('input.material_id')
    $inputMaterial.each(function (i, item) {
        let $item = $(item)
        let materialId = $item.val()

        if (materialId !== '') {
            let reg = /\d+/
            let inputId = $item.attr('id').match(reg)

            if (inputId !== null) {
                $(`#material${inputId[0]}`).val($(`#materials option[data-value="${materialId}"]`).text())
            }
        }
    })

    // let $inputAssigned = $('input[name^="id_assign"]')
    // $inputAssigned.each(function (i, item) {
    //     let $item = $(item)
    //     let assignedId = $item.val()

    //     if (assignedId !== '') {
    //         let reg = /\d+/
    //         let inputId = $item.attr('id').match(reg)

    //         if (inputId !== null) {
    //             $(`#assigned${inputId[0]}`).val($(`#assigneds option[data-value="${assignedId}"]`).text())
    //         }
    //     }
    // })

    $body.on('change', '#company', function() {
        // clear form
        let $selectContract = $('select[name="NUM_DOGOVOR"]')
        $selectContract.empty().append(`<option value="">Новый договор</option>`)
        $('input.clearable').val('')

        let text = $(this).val()
        if ( text !== '' ) {
            let companyId = $(`#company_list option:contains(${text})`).data('value')

            if ( companyId !== undefined ) {
                $.ajax({
                    url: "/ulab/request/getRequisiteAjax/",
                    data: {"company_id": companyId},
                    dataType: "json",
                    method: "POST",
                    success: function (data) {
                        $('input[name="CompanyFullName"]').val(data.RQ_COMPANY_FULL_NAME)
                        $('input[name="INN"]').val(data.RQ_INN)
                        $('input[name="OGRNIP"]').val(data.RQ_OGRNIP)
                        $('input[name="OGRN"]').val(data.RQ_OGRN)
                        $('input[name="ADDR"]').val(data.RQ_ACCOUNTANT)
                        $('input[name="ACTUAL_ADDRESS"]').val(data.address[1].ADDRESS_1)
                        $('input[name="mailingAddress"]').val(data.RQ_COMPANY_NAME)
                        $('input[name="EMAIL"]').val(data.RQ_FIRST_NAME)
                        $('input[name="POST_MAIL"]').val(data.RQ_EMAIL)
                        $('input[name="PHONE"]').val(data.RQ_PHONE)
                        $('input[name="CONTACT"]').val(data.RQ_NAME)
                        $('input[name="KPP"]').val(data.RQ_KPP)
                        $('input[name="Position2"]').val(data.RQ_COMPANY_REG_DATE)
                        $('input[name="PositionGenitive"]').val('')
                        $('input[name="DirectorFIO"]').val(data.RQ_DIRECTOR)
                        $('input[name="RaschSchet"]').val(data.RQ_ACC_NUM)
                        $('input[name="KSchet"]').val(data.RQ_COR_ACC_NUM)
                        $('input[name="l_schet"]').val(data.COMMENTS)
                        $('input[name="BIK"]').val(data.RQ_BIK)
                        $('input[name="BankName"]').val(data.RQ_BANK_NAME)
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
                            msg = 'Requested JSON parse failed.';
                        } else if (exception === 'timeout') {
                            msg = 'Time out error.';
                        } else if (exception === 'abort') {
                            msg = 'Ajax request aborted.';
                        } else {
                            msg = 'Uncaught Error.\n' + jqXHR.responseText;
                        }
                        console.log(msg)
                    }
                })

                $.ajax({
                    url: "/ulab/request/getContractsAjax/",
                    data: {"company_id": companyId},
                    dataType: "json",
                    method: "POST",
                    success: function (data) {
                        for (const i in data) {
                            if (data.hasOwnProperty(i)) {
                                $selectContract.append(
                                    `<option value="${data[i].ID}">${data[i].CONTRACT_TYPE ?? 'Договор'} №${data[i].NUMBER} от ${data[i].DATE}</option>`
                                )
                            }
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
                            msg = 'Requested JSON parse failed.';
                        } else if (exception === 'timeout') {
                            msg = 'Time out error.';
                        } else if (exception === 'abort') {
                            msg = 'Ajax request aborted.';
                        } else {
                            msg = 'Uncaught Error.\n' + jqXHR.responseText;
                        }
                        console.log(msg)
                    }
                })
            }
        }
    })

    $('input[name="INN"]').on('input', function () {
        let inn = $(this).val()
        let $innHelp = $('#innHelp')

        let length = inn.length

        if ( length === 10 || length === 12 ) {
            $innHelp.html(
                `Идет поиск по ИНН. Подождите...
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>`
            ).removeClass('text-green').removeClass('text-red')

            $.ajax({
                url: "/ulab/request/checkCompanyByInnAjax/",
                data: {"INN": inn},
                dataType: "json",
                method: "POST",
                success: function (data) {
                    if ( data !== false ) {
                        $innHelp.text('Найдено в системе.').addClass('text-green')
                        let text = $(`#company_list option[data-value=${data}]`).text()
                        if ( confirm(`Компания с таким ИНН уже существует. Название: ${text}. Применить данные этой компании?`) ) {
                            $('#company-hidden').val(data)
                            $('#company').val(text).trigger('change')
                        }
                    } else {
                        $.ajax({
                            url: "/ulab/request/getCompanyByInnAjax/",
                            data: {"INN": inn},
                            dataType: "json",
                            method: "POST",
                            success: function (data) {
                                if (data && data.name_short !== undefined && data.name_short !== null) {
                                    $innHelp.text('Найдено в сети Интернет.').addClass('text-green')
                                    if ( confirm(`Найдена компания с таким ИНН. Название: ${data.name_short}. Применить данные этой компании?`) ) {
                                        $('#company').val(data.name_short)
                                        $('input[name="CompanyFullName"]').val(data.name)
                                        $('input[name="KPP"]').val(data.kpp)
                                        $('input[name="ADDR"]').val(data.adress)
                                        $('input[name="Position2"]').val(data.position_name)
                                        $('input[name="DirectorFIO"]').val(data.official_name)
                                        $('input[name="OGRN"]').val(data.ogrn)
                                    }
                                } else {
                                    $innHelp.text('Компаний с таким ИНН не найдено').addClass('text-red')
                                }
                            }
                        })
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
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                    console.log(msg)
                }
            })
        } else {
            $innHelp.html(
                `Необходимо 10 или 12 цифр. Введено: ${length}`
            ).removeClass('text-green').removeClass('text-red')
        }
    })

    $('.check-ip').change(function () {
        let disabled = $( ".check-ip:checked" ).length === 0
        $('#ogrnip').prop( "disabled", disabled )
    })

    $('.reload').on('click', function () {
        let url = $(this).data('href')
        window.open (url, '_blank')
        setTimeout( function() {location.reload()});
    })

    // setupTableResizeHandlers()
})

/**
 * Округление
 * @param num
 * @param decimalPlaces
 * @returns {number}
 */
function round(num, decimalPlaces = 0) {
    if (num < 0) {
        return -round(-num, decimalPlaces);
    }
    let p = Math.pow(10, decimalPlaces);
    let n = num * p;
    let f = n - Math.floor(n);
    let e = Number.EPSILON * n;

    return (f >= 0.5 - e) ? Math.ceil(n) / p : Math.floor(n) / p;
}

/**
 * Нахождение среднего арифметического
 * @param nums
 * @returns {number|boolean}
 */
function average(nums) {
    if ( nums.length === 0 || nums.length === undefined ) {
        return false
    }
    return nums.reduce((a, b) => (a + b)) / nums.length
}

/**
 * Нахождение количество дней между 2-мя датами
 * @param start
 * @param end
 * @returns {number}
 */
function getNumberOfDays(start, end) {
    const date1 = new Date(start);
    const date2 = new Date(end);

    // Один день в миллисекундах
    const oneDay = 1000 * 60 * 60 * 24;

    // Расчет разницы во времени между двумя датами
    const diffInTime = date2.getTime() - date1.getTime();

    const diffInDays = Math.round(diffInTime / oneDay);

    return diffInDays;
}

function showErrorMessage(msg, anchor = '') {
    // Создаем уникальный ID для сообщения
    const messageId = 'error-message-' + new Date().getTime();
    
    // Добавляем сообщение с ID
    $('.messages').append(
        `<div id="${messageId}" class="alert alert-danger d-flex align-items-center alert-dismissible fade show text-break" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </svg>
            <div>
                ${msg}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`
    );
    
    // Прокручиваем к сообщению
    if (anchor) {
        if (anchor === '#error-message') {
            // Если указан якорь по умолчанию, прокручиваем к новому сообщению
            $('html, body').animate({
                scrollTop: $('#' + messageId).offset().top - 100
            }, 200);
        } else {
            // Иначе прокручиваем к указанному якорю
            $('html, body').animate({
                scrollTop: $(anchor).offset().top - 100
            }, 200);
        }
    }
    
    // Автоматически скрываем сообщение через 5 секунд
    // setTimeout(function() {
    //     $('#' + messageId).alert('close');
    // }, 5000);
}

function showSuccessMessage(msg) {
    $('.messages').append(
        `<div class="alert alert-success d-flex align-items-center alert-dismissible fade show" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
            </svg>
            <div>
                ${msg}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`
    )
}

/**
 * Показать ошибку под элементом
 * @param {jQuery} $element — jQuery-обёртка (input, select и т.д.)
 * @param {string} message — текст ошибки
 */
function showElementError($element, message) {
    const $errorContainer = $element.next('.invalid-feedback')

    if (!$errorContainer.length) {
        $element.after('<div class="invalid-feedback"></div>')
    }

    $element.addClass('is-invalid')
    $element.next('.invalid-feedback').text(message).show()
}

/**
 * Скрыть/очистить ошибку у элемента
 * @param {jQuery} $element — jQuery-обёртка (input, select и т.д.)
 */
function clearElementError($element) {
    $element.removeClass('is-invalid')
    const $errorContainer = $element.next('.invalid-feedback')
    if ($errorContainer.length) {
        $errorContainer.text('').hide()
    }
}

function bufferToBase64(buf) {
    let binstr = Array.prototype.map.call(buf, function (ch) {
        return String.fromCharCode(ch);
    }).join('');
    return btoa(binstr);
}

function validateEmailField($emailField) {
    clearElementError($emailField)

    let emailVal = $emailField.val()?.toString().trim() || ''
    const emailPattern = /^[^\s@]+@[^\s@]+\.[a-zA-Z]{2,}$/

    if (emailVal && !emailPattern.test(emailVal)) {
        showElementError($emailField, 'Введите корректный e-mail (например: user@example.com)')
        return false
    }

    return true
}

async function addImgToPdf(filePath, imgUrl, imgParams) {
    const { PDFDocument, StandardFonts, grayscale, rgb, degrees } = PDFLib;
    const existingPdfBytes = await fetch(filePath).then(res => res.arrayBuffer());

    const pdfDoc = await PDFDocument.load(existingPdfBytes);
    const pages = pdfDoc.getPages();
    const firstPage = pages[0];

    const pngImageBytes = await fetch(imgUrl).then((res) => res.arrayBuffer());
    const pngImage = await pdfDoc.embedPng(pngImageBytes);

    firstPage.drawImage(pngImage, imgParams);

    const pdfBytes = await pdfDoc.save();

    let base64 = bufferToBase64(pdfBytes);

    //download(pdfBytes, "pdf-lib_modify_example.pdf", "application/pdf");

    $.ajax({
        url: '/ulab/file/bytePdfToServerAjax',
        data: {
            file: base64, path: filePath
        },
        method: 'POST',
        success: function (json) {
            let arr = JSON.parse(json);
            console.log(arr)
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
                msg = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                msg = 'Time out error.';
            } else if (exception === 'abort') {
                msg = 'Ajax request aborted.';
            } else {
                msg = 'Uncaught Error.\n' + jqXHR.responseText;
            }
            console.error(msg)
        }
    })
}

/**
 * Функция для задержки выполнения другой функции при частых вызовах
 * @param {Function} func - Функция, выполнение которой нужно отложить
 * @param {number} wait - Время задержки
 * @returns {Function} - Функция с задержкой выполнения
 */
function delayExecution(func, wait) {
    let timeout
    return function() {
        const context = this, args = arguments
        clearTimeout(timeout)
        timeout = setTimeout(() => func.apply(context, args), wait)
    }
}