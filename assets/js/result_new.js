/**
 * Результаты испытаний
 */
$(function ($) {
    let $body = $('body')

    let $btnStart = $('.btn_start')
    let $btnPause = $('.btn_pause')
    let $btnStop = $('.btn_stop')
    let $btnCreateProtocol = $('.btn_create_protocol')
    let $btnMeasurementSheet = $('.btn_group_measurement_sheet')
    let $btnUnboundProtocol = $('.btn_unbound_protocol')

    const dealId = $('#deal_id').val()

    const $journalMethods = $('#journal_methods')

    $body.on('change', '.all-check', function () {
        $body.find(`.method-check`).prop("checked", $(this).prop("checked")).trigger('change')

        toggleProbe()
    })

    $body.on('change', '.method-check', function () {
        let methodId = $(this).data('method_id')

        let parent = $body.find(`.all-check`),
            all = true,
            curState = $(this).prop("checked")

        $body.find(`.method-check`).each(function () {
            return all = ($(this).prop("checked") === curState)
        })

        if ( all ) {
            parent.prop({
                indeterminate: false,
                checked: curState
            })
        } else {
            parent.prop({
                indeterminate: true,
                checked: false
            })
        }

        $body.find(`.method-id-${methodId}`).prop("checked", $(this).prop("checked"))

        toggleProbe()
    })

    $body.on('change', '.probe-check', function () {
        let methodId = $(this).data('method_id')

        let parent = $body.find(`#method_name_${methodId}`),
            parentParent = $body.find(`.all-check`),
            all = true,
            curState = $(this).prop("checked")

        $body.find(`.method-id-${methodId}`).each(function () {
            return all = ($(this).prop("checked") === curState)
        })

        if ( all ) {
            parent.prop({
                indeterminate: false,
                checked: curState
            })
        } else {
            parent.prop({
                indeterminate: true,
                checked: false
            })
        }

        $body.find(`.method-check`).each(function () {
            return all = ($(this).prop("checked") === curState)
        })

        if ( all ) {
            parentParent.prop({
                indeterminate: false,
                checked: curState
            })
        } else {
            parentParent.prop({
                indeterminate: true,
                checked: false
            })
        }

        toggleProbe()
    })

    function toggleProbe() {
        let probeIdList = $journalMethods.find(".probe-check:checked").map(function() {
            return $(this).val();
        }).get()

        let measurementIdList = $journalMethods.find(".probe-check:checked").map(function() {
            let tmp = $(this).data('measurement_id')
            if ( tmp === `undefined` ) { return }
            return $(this).data('measurement_id')
        }).get()

        if ( probeIdList.length > 0 ) {
            $btnStart.removeClass('disabled')
            $btnPause.removeClass('disabled')
            $btnStop.removeClass('disabled')
            $btnCreateProtocol.removeClass('disabled')
            $btnUnboundProtocol.removeClass('disabled')
        } else {
            $btnStart.addClass('disabled')
            $btnPause.addClass('disabled')
            $btnStop.addClass('disabled')
            $btnCreateProtocol.addClass('disabled')
            $btnUnboundProtocol.addClass('disabled')
        }

        if ( measurementIdList.length > 0 ) {
            $btnMeasurementSheet.removeClass('disabled')
        } else {
            $btnMeasurementSheet.addClass('disabled')
        }
    }

    let stickyEl2 = new Sticksy('.js-sticky-widget2', {topSpacing: 20, listen: true,})
    stickyEl2.onStateChanged = function (state) {
        if(state === 'fixed') {
            stickyEl2.nodeRef.classList.add('widget--sticky')
        } else {
            stickyEl2.nodeRef.classList.remove('widget--sticky')
        }
    }

    $('.select2').select2({
        theme: 'bootstrap-5',
    })

    let journalDataTable = $journalMethods.DataTable({
        bAutoWidth: false,
        autoWidth: false,
        fixedColumns: false,
        processing: true,
        serverSide: true,
        bSortCellsTop: true,
        scrollX: true,
        fixedHeader: false,
        colReorder: false,
        ajax: {
            type : 'POST',
            data: function ( d ) {
                d.deal_id = dealId
                d.material_id = $('#filter-material').val()
                d.method_id = $('#filter-methods').val()
                d.probe_id = $('#filter-probe').val()
                d.protocol_id = $('.selected-probe:checked').val()
                d.selected_protocol_id = $('.selected_protocol_id').val()
            },
            url : '/ulab/result/getMethodsProbeJournalAjax/',
            dataSrc: function (json) {
                return json.data
            }
        },
        columns: [
            {
                data: 'view_gost',
                orderable: false,
            },
            {
                data: 'ewq',
                orderable: false,
                width: "20px",
                className: 'cursor-pointer text-center',
                render: function (data, type, item) {
                    return `
                        <input 
                            class="form-check-input probe-check method-id-${item['method_id']}" 
                            type="checkbox" 
                            data-method_id="${item['method_id']}" 
                            data-measurement_id="${item.measurement.id}"
                            id="method_name_${item['method_id']}_${item['probe_number']}_${item['ugtp_id']}"
                            name="gost_check[${item['ugtp_id']}]"
                            value="${item['ugtp_id']}"
                        >`
                }
            },
            {
                data: 'material_name',
                orderable: false,
                render: function (data, type, item) {
                    let materialGroup = ``
                    if ( item.group_name != null ) {
                        materialGroup = `(${item.group_name})`
                    }

                    return `<label class="cursor-pointer" for="method_name_${item['method_id']}_${item['probe_number']}_${item['ugtp_id']}">${item.material_name} ${materialGroup}</label>`
                }
            },
            {
                data: 'cipher',
                orderable: false,
                render: function (data, type, item) {
                    return `<label class="cursor-pointer" for="method_name_${item['method_id']}_${item['probe_number']}_${item['ugtp_id']}">${item.cipher}</label>`
                }
            },
            {
                data: 'ewq2',
                orderable: false,
                className: 'text-center',
                render: function (data, type, item) {
                    if ( item.state_action.state === 'start' ) {
                        return `<i class="fa-regular fa-circle-play icon-big" title="В работе"></i>`
                    }

                    if ( item.state_action.state === 'pause' ) {
                        return `<i class="fa-solid fa-circle-pause icon-big" title="Приостановлено"></i>`
                    }

                    if ( item.state_action.state === 'complete' ) {
                        return `<i class="fa-regular fa-circle-check icon-big" title="Завершено"></i>`
                    }

                    return `<i class="fa-regular fa-circle icon-big" title="Не начато"></i>`
                }
            },
            {
                data: 'ewq3',
                orderable: false,
                className: 'text-center',
                render: function (data, type, item) {
                    if ( item.measurement.id === undefined ) {
                        return `<button type="button" class="btn bg-transparent border-0 mt-0 p-0 measurement-sheet disabled" title="Лист измерения">
                                    <i class="fa-solid fa-calculator font-size-35"></i>
                                </button>`
                    }
                    return `<button type="button" class="btn bg-transparent border-0 mt-0 p-0 measurement-sheet" data-measurement="${item.measurement.id}" data-ugtp="${item.ugtp_id}" data-method="${item.method_id}" title="Лист измерения">
                                <i class="fa-solid fa-calculator font-size-35"></i>
                            </button>`
                }
            },
            {
                data: 'units',
                orderable: false,
                className: 'text-center'
            },
            {
                data: 'ewq3',
                orderable: false,
                className: 'text-center',
                render: function (data, type, item) {
                    if ( item.nd_method_id > 0 ) {
                        return `<a href="/ulab/normDocGost/method/${item.nd_method_id}" class="text-decoration-none">${item.tech.reg_doc} ${item.tech.name}</a>`
                    }

                    return `-`
                }
            },
            {
                data: 'normative_value',
                orderable: false,
                className: '',
                render: function (data, type, item) {
                    let html = `<div class="form-text text-start aa">${item.normative_text?? ''}</div>`

                    if (item.readonly_normative_value) {
                        html += `<div class="normative-value w-100 border p-2 like-input text-start bg-light-secondary like-input"
                                         title="Доступно для редактирования если ТУ выбрано при формировании ТЗ и ТУ не нормируемое и нет номера протокола или есть номер но протокол разблокирован">
                                        ${item.normative_value}
                                    </div>`
                    } else {
                        html += `<input type="text"
                                           class="form-control normative-value bg-white"
                                           name="normative_value[${item.mtr_id}][${item.ugtp_id}]"
                                           value="${item.normative_value?? ''}">`
                    }
                    html += `<div class="form-text text-start">${item.normative_message?? ''}</div>`

                    return html
                }
            },
            {
                data: 'actual_value',
                orderable: false,
                className: '',
                render: function (data, type, item) {
                    let html = `<div class="form-text text-danger text-start">${item.out_range?? ''}</div>`
                    let readonly = item.confirm_oa_readonly ? 'readonly' : ''

                    if ( item.readonly_actual_value ) {
                        html += `<input ${item.actual_value_type}
                                    class="me-2 actual-value actual-value-${item.ugtp_id} w-100 border p-2 bg-light-secondary"
                                    name="actual_value[${item.mtr_id}][${item.ugtp_id}]"
                                    value="${item.actual_value}"
                                    title="Доступно для редактирования если нет номера протокола или есть номер но протокол на редактировании и у методики ф/значение текстом или у методики ф/значение не текстом и нет листа измерения"
                                    readonly>`
                    } else {
                        html += `<div class="d-flex actual-value-wrapper mb-1">
                                    <input ${item.actual_value_type}
                                            class="me-2 actual-value actual-value-${item.ugtp_id} w-100 border p-2 "
                                            name="actual_value[${item.mtr_id}][${item.ugtp_id}]"
                                            value="${item.actual_value}" ${readonly}>
                                </div>`
                    }
                    html += `<div class="form-text text-start">${item.actual_value_message?? ''}</div>`

                    return html
                }
            },
            {
                data: 'ewq3',
                orderable: false,
                className: '',
                render: function (data, type, item) {
                    let html = ``
                    let isInvalid = item.match_message? 'is-invalid' : ''

                    if ( item.readonly_match ) {
                        html += `<div class="form-control match w-100 p-2 text-start bg-light-secondary text-start like-input ${isInvalid}"
                                     aria-describedby="match_${item.mtr_id}_${item.ugtp_id}"
                                     title="Доступно для редактирования если нет номера протокола или есть номер но протокол на редактировании и ТУ не нормируемое или в ТУ ручное управление 'соотв/не соотв' или фактических значений более 1">
                                    ${item.match_view?? ''}
                                </div>
                                <div id="match_${item.mtr_id}_${item.ugtp_id}"
                                     class="invalid-feedback">
                                    ${item.match_message?? ''}
                                </div>`
                    } else {
                        html += `<select class="form-select match ${isInvalid}"
                                        name="match[${item.mtr_id}][${item.ugtp_id}]"
                                        aria-describedby="match_${item.mtr_id}_${item.ugtp_id}">
                                    <option value="0"
                                        ${item.match == 0? 'selected' : ''}>
                                        Не соответствует
                                    </option>
                                    <option value="1"
                                        ${item.match == 1? 'selected' : ''}>
                                        Соответствует
                                    </option>
                                    <option value="2"
                                        ${item.match == 2? 'selected' : ''}>
                                        -
                                    </option>
                                    <option value="3"
                                        ${item.match == 3? 'selected' : ''}>
                                        Не нормируется
                                    </option>
                                </select>
                                <div id="match_${item.mtr_id}_${item.ugtp_id}"
                                     class="invalid-feedback">
                                    ${item.match_message?? ''}
                                </div>`
                    }
                    html += `<div class="form-text">${item.match_text?? ''}</div>`

                    return html
                }
            },
            {
                data: 'ewq3',
                orderable: false,
                className: 'text-center cursor-pointer',
                render: function (data, type, item) {
                    let html = ``
                    let title = `Диапазон: ${item.range_ao}`

                    if ( item.in_field ) {
                        html += `<i class="fa-regular fa-circle-check icon-big" title="${title}"></i>`
                    } else {
                        html += `<i class="fa-regular fa-circle-xmark icon-big" title="${title}></i>`
                    }

                    return html
                }
            },
            {
                data: 'ewq3',
                orderable: false,
                className: '',
                render: function (data, type, item) {
                    if ( item.protocol.ID && item.protocol.INVALID == 0 ) {

                        return `<a href="/ulab/result/card_oati/${item.deal_id}?protocol_id=${item.protocol.ID}"
                                   class="text-decoration-none text-nowrap fw-bold">
                                    ${item.protocol.NUMBER?? 'Номер не присвоен'}
                                </a>`
                    }
                    return ``
                }
            },
        ],
        columnDefs: [{ visible: false, targets: 0 }],
        drawCallback: function (settings) {
            let api = this.api();
            let rows = api.rows({ page: 'current' }).nodes();
            let last = null;
            let data = rows.data()
            let disableMs = ''

            api.column(0, { page: 'current' })
                .data()
                .each(function (group, i) {
                    if (last !== group) {
                        if ( data[i].measurement.id === undefined ) {
                            disableMs = 'disabled'
                        }
                        $(rows)
                            .eq(i)
                            .before(
                                `<tr class="bg-sky-blue">
                                    <td colspan="4">
                                        <div class="form-check">
                                            <input class="form-check-input method-check" type="checkbox" data-method_id="${data[i].method_id}" id="method_name_${data[i].method_id}">
                                            <label class="form-check-label" for="method_name_${data[i].method_id}">
                                                <strong>${group}</strong>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a class="text-black" href="/ulab/gost/method/${data[i].method_id}">
                                            <i class="fa-solid fa-arrow-right-to-bracket icon-big"></i>
                                        </a>
                                    </td>
                                    <td colspan="8"></td>
                                </tr>`
                            );

                        last = group;
                    }
                });
        },
        createdRow: function( row, data, dataIndex ) {
            if ( data.selected_protocol_id !== null && data.selected_protocol_id == data.gtp_protocol_id ) {
                $(row).addClass("bg-pele-green")
            }
        },
        language: dataTablesSettings.language,
        lengthMenu: [[10, 25, 50, 100, -1], [10,25, 50, 100, "Все"]],
        pageLength: -1,
        order: [[ 2, "asc" ]],
        dom: 'frt<"bottom"lip>',
        buttons: dataTablesSettings.buttons,
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

    journalDataTable.on('draw', function () {
        $btnStart.addClass('disabled')
        $btnPause.addClass('disabled')
        $btnStop.addClass('disabled')
        $btnCreateProtocol.addClass('disabled')
        $btnMeasurementSheet.addClass('disabled')
        $body.find('.all-check').prop({indeterminate: false,checked: false})
    })

    $('.filter, .selected-probe').on('input', function () {
        journalDataTable.ajax.reload()
    })

    function reportWindowSize() {
        journalDataTable
            .columns.adjust()
    }

    window.onresize = reportWindowSize

    function isValidNumber(value) {
        return value !== null && value !== '' && !isNaN(+value)
    }

    $body.on('click', '.btn_start', function () {
        let probeIdList = $(".probe-check:checked").map(function(){
            return $(this).val();
        }).get()

        let $contentBlock = $('#gost_room_form .gost_room_container')

        $.ajax({
            url: `/ulab/result/getRoomsConditionsAjax/`,
            method: "POST",
            dataType: "json",
            data: {
                probe_id_list: probeIdList,
            },
            success: function (json) {
                $.magnificPopup.open({
                    items: {
                        src: '#gost_room_form',
                        type: 'inline',
                        fixedContentPos: false,
                    },
                    closeOnBgClick: false,
                    showCloseBtn: true,
                    callbacks: {
                        open: function() {
                            $contentBlock.find('[data-bs-toggle="popover"]').each(function () {
                                new bootstrap.Popover(this, {
                                    customClass: 'popover-wide-equipment',
                                    html: true,
                                    trigger: 'hover focus',
                                    container: 'body'
                                })
                            })
                        },
                        beforeOpen: function() {
                            $contentBlock.empty()

                            let html = ``

                            if ( json.length === 0 ) {
                                $contentBlock.append(`<p>Нечего стартовать</p>`)
                            } else {
                                $.each(json, function (i, probe) {

                                    if ( probe[0].rooms.length === 0 ) { return true }

                                    html += `<div class="text-center mb-2"><strong>${probe[0].material_name} | ${probe[0].cipher}</strong></div>`

                                    $.each(probe, function (j, method) {
                                        if ( method.rooms.length === 0 ) { return true }

                                        const warnings = []
                                        const currentDate = new Date()

                                        if (Array.isArray(method.equipment)) {
                                            method.equipment.forEach(item => {
                                                const equipment = item.info || {}
                                                const room = item.room || {}
                                                const { temp, wet } = room

                                                // Если переносное оборудование то не проверять
                                                if (+equipment['is_portable']) {
                                                    return
                                                }

                                                // Проверка: оборудование не проверено
                                                if (!+item.CHECKED) {
                                                    warnings.push(`<strong>${equipment.OBJECT}</strong> - не проверено.`)
                                                }

                                                // Проверка температуры
                                                if (+equipment.TEMPERATURE !== 1) {
                                                    if (!isValidNumber(temp) || !isValidNumber(equipment.TOO_EX) || !isValidNumber(equipment.TOO_EX2)) {
                                                        warnings.push(`<strong>${equipment.OBJECT}</strong> - отсутствуют корректные данные для проверки температуры.`)
                                                    } else if (+temp < +equipment.TOO_EX || +temp > +equipment.TOO_EX2) {
                                                        warnings.push(`<strong>${equipment.OBJECT}</strong> - температура не соответствует.`)
                                                    }
                                                }

                                                // Проверка влажности
                                                if (+equipment.HUMIDITY !== 1) {
                                                    if (!isValidNumber(wet) || !isValidNumber(equipment.OVV_EX) || !isValidNumber(equipment.OVV_EX2)) {
                                                        warnings.push(`<strong>${equipment.OBJECT}</strong> - отсутствуют корректные данные для проверки влажности.`)
                                                    } else if (+temp < +equipment.OVV_EX || +temp > +equipment.OVV_EX2) {
                                                        warnings.push(`<strong>${equipment.OBJECT}</strong> - влажность не соответствует.`)
                                                    }
                                                }

                                                // Проверка срока поверки оборудования
                                                if (!+equipment.NO_METR_CONTROL) { // Подлежит периодическому контролю
                                                    const dateEnd = equipment.actual_certificate?.at(-1)?.date_end
                                                    if (dateEnd) {
                                                        const poverkaDate = new Date(dateEnd)
                                                        if (!isNaN(poverkaDate) && poverkaDate <= currentDate && !["OOPP", "VO"].includes(equipment.IDENT)) {
                                                            warnings.push(`<strong>${equipment.OBJECT}</strong> - истек срок поверки оборудования.`)
                                                        }
                                                    } else {
                                                        warnings.push(`<strong>${equipment.OBJECT}</strong> - отсутствуют корректные данные поверки оборудования.`)
                                                    }
                                                }
                                            });
                                        }

                                        const equipmentWarnings = warnings.join('<br>')

                                        const warningIcon = equipmentWarnings
                                            ? `<i class="fas fa-exclamation-circle text-danger ml-1 cursor-pointer" tabindex="0"
                                                data-bs-toggle="popover"  
                                                data-bs-title="Несоответствия оборудования"
                                                data-bs-content="${equipmentWarnings.trim()}"
                                                onclick="event.stopPropagation()"></i>`
                                            : ''


                                        let roomHtml = ``
                                        $.each(method.rooms, function (k, room) {
                                            let textLabelTemp = ``
                                            let textLabelWet = ``
                                            let textLabelPress = ``

                                            const isInvalidTemp = (!isValidNumber(room.temp) || +room.temp < +method.cond_temp_1 || +room.temp > +method.cond_temp_2) && +method.is_not_cond_temp != 1
                                                ? 'is-invalid bg-img-none'
                                                : ''
                                            const isInvalidWet = (!isValidNumber(room.wet) || +room.wet < +method.cond_wet_1 || +room.wet > +method.cond_wet_2) && +method.is_not_cond_wet != 1
                                                ? 'is-invalid bg-img-none'
                                                : ''
                                            const isInvalidPress = (!isValidNumber(room.pressure) || +room.pressure < +method.cond_pressure_1 || +room.pressure > +method.cond_pressure_2) && +method.is_not_cond_pressure != 1
                                                ? 'is-invalid bg-img-none'
                                                : ''

                                            if ( method.is_not_cond_temp == 1 ) {
                                                textLabelTemp = `Не нормируется`
                                            } else {
                                                textLabelTemp = `От ${method.cond_temp_1} до ${method.cond_temp_2}`
                                            }
                                            if ( method.is_not_cond_wet == 1 ) {
                                                textLabelWet = `Не нормируется`
                                            } else {
                                                textLabelWet = `От ${method.cond_wet_1} до ${method.cond_wet_2}`
                                            }
                                            if ( method.is_not_cond_pressure == 1 ) {
                                                textLabelPress = `Не нормируется`
                                            } else {
                                                textLabelPress = `От ${method.cond_pressure_1} до ${method.cond_pressure_2}`
                                            }

                                            roomHtml += `<div class="row mb-2 room_block">
                                                            <div class="col-5">
                                                                <div class="form-check pt-2">
                                                                    <input type="checkbox" class="form-check-input check_room" id="check_room_${method.ugtp_id}_${room.room_id}" name="form[${method.ugtp_id}][room_id]" value="${room.room_id}" checked>
                                                                    <label class="form-check-label" for="check_room_${method.ugtp_id}_${room.room_id}">
                                                                        ${room.name || '<span class="text-danger">Не выбрано помещение</span>'}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <input type="number" step="any" class="form-control input_temp ${isInvalidTemp}" 
                                                                    data-room_id="${room.room_id}" data-min="${method.cond_temp_1}" data-max="${method.cond_temp_2}" data-validate="${method.is_not_cond_temp}" 
                                                                    name="form[${method.ugtp_id}][${room.room_id}][temp]" value="${room.temp?? ''}">
                                                                <div class="form-text">${textLabelTemp}</div>
                                                            </div>
                                                            <div class="col">
                                                                <input type="number" step="any" class="form-control input_wet ${isInvalidWet}" 
                                                                    data-room_id="${room.room_id}" data-min="${method.cond_wet_1}" data-max="${method.cond_wet_2}" data-validate="${method.is_not_cond_wet}" 
                                                                    name="form[${method.ugtp_id}][${room.room_id}][wet]" value="${room.wet?? ''}">
                                                                <div class="form-text">${textLabelWet}</div>
                                                            </div>
                                                            <div class="col">
                                                                <input type="number" step="any" class="form-control input_press ${isInvalidPress}" 
                                                                    data-room_id="${room.room_id}" data-min="${method.cond_pressure_1}" data-max="${method.cond_pressure_2}" data-validate="${method.is_not_cond_pressure}" 
                                                                    name="form[${method.ugtp_id}][${room.room_id}][pressure]" value="${room.pressure?? ''}">
                                                                <div class="form-text">${textLabelPress}</div>
                                                            </div>
                                                        </div>`
                                        })

                                        let methodColorClass = (!+method.is_actual || !+method.is_confirm) ? 'text-danger' : '';

                                        html += `<div class="gost_room_block mb-3">
                                                    <div class="row mb-1">
                                                        <div class="col-5"><strong class="${methodColorClass}">${method.name} ${+method.is_confirm?'':'(не проверено)'}</strong> ${warningIcon}</div>
                                                        <div class="col">Температура</div>
                                                        <div class="col">Влажность</div>
                                                        <div class="col">Давление</div>
                                                    </div>
                                                    ${roomHtml}
                                                </div>
                                                <div class="line-dashed-small"></div>`
                                    })
                                })

                                $contentBlock.append(html)
                            }
                        },
                    }
                })
            }
        })

        return false
    })

    function validateInput($element, roomId, selector) {
        let min = parseFloat($element.data('min'))
        let max = parseFloat($element.data('max'))
        let validate = parseInt($element.data('validate'))
        let value = parseFloat($element.val())

        if ((isNaN(value) || value < min || value > max) && validate !== 1) {
            $element.addClass('is-invalid bg-img-none')
            $(`${selector}[data-room_id="${roomId}"]`).addClass('is-invalid bg-img-none')
        } else {
            $element.removeClass('is-invalid bg-img-none')
            $(`${selector}[data-room_id="${roomId}"]`).removeClass('is-invalid bg-img-none')
        }
    }

    $body.on('change', '.check_room', function () {
        let $block = $(this).closest('.room_block')

        $block.find('input.form-control').prop('disabled', !$(this).prop('checked'))
    })

    $body.on('input', '.input_temp', function () {
        let value = $(this).val()
        let roomId = $(this).data('room_id')

        $(`.input_temp[data-room_id="${roomId}"]`).val(value)
        validateInput($(this), roomId, '.input_temp')
    })
    $body.on('input', '.input_wet', function () {
        let value = $(this).val()
        let roomId = $(this).data('room_id')

        $(`.input_wet[data-room_id="${roomId}"]`).val(value)
        validateInput($(this), roomId, '.input_wet')
    })
    $body.on('input', '.input_press', function () {
        let value = $(this).val()
        let roomId = $(this).data('room_id')

        $(`.input_press[data-room_id="${roomId}"]`).val(value)
        validateInput($(this), roomId, '.input_press')
    })

    $body.on('click', '.btn_pause', function () {
        startStop($(this), 'newPauseTrialAjax')

        return false
    })

    $body.on('click', '.btn_stop', function () {
        startStop($(this), 'newStopTrialAjax')

        return false
    })

    function startStop($button, method) {
        let probeIdList = $(".probe-check:checked").map(function(){
            return $(this).val();
        }).get()

        if ( probeIdList.length === 0 ) {
            return false
        }

        let btnHtml = $button.html()
        $button.html(`<i class="fa-solid fa-arrows-rotate spinner-animation"></i>`)
        $button.addClass('disabled')

        $.ajax({
            url: `/ulab/result/${method}/`,
            method: "POST",
            dataType: "text",
            data: {
                probe_id_list: probeIdList,
            },
            success: function () {
                journalDataTable.on('draw', function () {
                    $button.html(btnHtml)
                    $button.removeClass('disabled')
                })

                journalDataTable.ajax.reload()
            }
        })
    }


    /** Получить лист измерения */
    $body.on('click', '.measurement-sheet', function (e) {
        const btn = $(this)
        const $form = $('#measurementModalForm')
        const $contentBlock = $form.find('.measurement_content')

        let measurementId = $(this).data('measurement'),
            ugtpId = $(this).data('ugtp'),
            methodId = $(this).data('method');

        let measurementObject = []
        measurementObject.push({ugtpId : ugtpId, measurement_id : measurementId, methodId : methodId})

        btn.find('i').addClass('spinner-animation');
        btn.addClass('disabled');

        if (btn.find('.fa-calculator').length > 0) {
            btn.prop('disabled', true);
            btn.find('.fa-calculator').addClass('d-none');
            btn.append(
                `<div class="spinner-border" role="status">
                <span class="visually-hidden">Загрузка...</span>
            </div>`
            )
        }


        $.ajax({
            method: 'POST',
            url: '/ulab/result/getMeasurementSheetAjax',
            cache: false,
            data: {
                measurement_object: measurementObject,
            },
            success: function (data) {
                $.magnificPopup.open({
                    items: {
                        src: '#measurementModalForm',
                        type: 'inline',
                        fixedContentPos: false,
                    },
                    closeOnBgClick: false,
                    showCloseBtn: true,
                    callbacks: {
                        beforeOpen: function() {
                            $contentBlock.empty()

                            $contentBlock.html(data)
                        },
                    }
                })

                btn.find('i').removeClass('spinner-animation')
                btn.removeClass('disabled')

                if (btn.find('.fa-calculator').length > 0) {
                    btn.prop('disabled', false);
                    btn.find('.fa-calculator').removeClass('d-none');
                    btn.find('.spinner-border').remove();
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
                console.error(msg)
            }
        })
    })

    /**
     * фактическое значение
     */
    $body.on('click', '.add-value-actual', function () {
        let actualValueWrapper = $(this).parents('.actual-value-wrapper'),
            cloneActualValueWrapper = $(actualValueWrapper).clone(true),
            tdActualValue = actualValueWrapper.closest('.td-actual-value');

        cloneActualValueWrapper.find('.actual-value').val('');

        cloneActualValueWrapper.find('.add-value-actual').replaceWith(
            `<button type="button" class="btn btn-danger del-value-actual mt-0 btn-square">
                 <i class="fa-solid fa-minus icon-fix"></i>
            </button>`
        );
        console.log(actualValueWrapper, tdActualValue)

        tdActualValue.append(cloneActualValueWrapper);
    })

    $body.on('click', '.actual-value-wrapper button.del-value-actual', function () {
        let actualValueWrapper = $(this).closest('.actual-value-wrapper');

        actualValueWrapper.remove();
    })


    /** Получить групп лист измерения */
    $body.on('click', '.btn_group_measurement_sheet', function (e) {
        const $btn = $(this)
        const $form = $('#measurementModalForm')
        const $contentBlock = $form.find('.measurement_content')

        let measurementObject = $journalMethods.find(".probe-check:checked").map(function() {
            let measurementId = $(this).data('measurement_id')
            if ( measurementId === `undefined` ) { return }
            let ugtpId = $(this).val()
            let methodId = $(this).data('method_id')

            return {ugtpId : ugtpId, measurement_id : measurementId, methodId : methodId}
        }).get()

        if ( measurementObject.length === 0 ) { return false }

        let btnHtml = $btn.html()
        $btn.html(`<i class="fa-solid fa-arrows-rotate spinner-animation"></i>`)
        $btn.addClass('disabled')

        $.ajax({
            method: 'POST',
            url: '/ulab/result/getMeasurementSheetAjax',
            data: {
                measurement_object: measurementObject,
            },
            success: function (data) {
                $.magnificPopup.open({
                    items: {
                        src: '#measurementModalForm',
                        type: 'inline',
                        fixedContentPos: false,
                    },
                    closeOnBgClick: false,
                    showCloseBtn: true,
                    callbacks: {
                        beforeOpen: function() {
                            $contentBlock.empty()

                            $contentBlock.html(data)
                        },
                        open: function() {
                            $('body').addClass('noscroll');
                        },
                        close: function() {
                            $('body').removeClass('noscroll');
                        }
                    }
                })

                $btn.html(btnHtml)
                $btn.removeClass('disabled')
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
    })


    $body.on('click', '.btn_unbound_protocol', function () {
        let probeIdList = $(".probe-check:checked").map(function(){
            return $(this).val();
        }).get()

        $('.count-selected-probe').text(probeIdList.length)
        $('.probe-id-list').val(probeIdList)
    })


    $body.on('click', '.change-trials-date', function () {
        $('.date-trials').prop('readonly', !$(this).prop('checked')).toggleClass('bg-light-secondary')
    })

    $body.on('click', '.change-trials-conditions', function () {
        $('.condition_input').prop('readonly', !$(this).prop('checked')).toggleClass('bg-light-secondary')
    })

    /**
     * протокол недействителен
     */
    $body.on('change', '.protocol-is-invalid', function (e) {
        if (!confirm('Вы действительно хотите признать протокол недействительным? После признания протокола недействительным, данные нельзя будет восстановить')) {
            e.preventDefault();
            $(this).prop('checked', false);
            return false;
        }

        $(this).closest('form').submit();
    })

    /**
     * добавить оборудование
     */
    $body.on('change', '.equipment', function () {
        const equipmentUsed = $('.equipment-used'),
            selectedOption = $(this).find('option:selected');

        let equipmentUsedCount = equipmentUsed.length,
            selectedText = selectedOption.text(),
            selectedGost = selectedOption.data('gost'),
            selectedOborud = $(this).val(),
            aldOborudGost = JSON.parse($('#equipmentIds').val());

        aldOborudGost.push(JSON.parse(selectedOborud));
        $('#equipmentIds').val(JSON.stringify(aldOborudGost));

        let newOption = `<option value="${selectedOborud}">${selectedText}</option>`

        equipmentUsed.append(newOption)
    })


    /**
     * удалить оборудование
     */
    $body.on('dblclick', '.equipment-used option', function () {
        let aldOborudGost = JSON.parse($('#equipmentIds').val());
        let iEquipmentUsed = aldOborudGost.indexOf(+this.value);

        if (iEquipmentUsed !== -1) {
            aldOborudGost.splice(iEquipmentUsed, 1);
        } else {
            return false
        }

        $('#equipmentIds').val(JSON.stringify(aldOborudGost));
        $('.equipment-used option').eq(iEquipmentUsed).remove()
    })

    /**
     * вернуть оборудование по умолчанию
     */
    $body.on('click', '.revert-default', function () {
        let protocolId = $(this).data('protocolId')
        let equipmentUsedSelect = $('.equipment-used')

        $.ajax({
            method: 'POST',
            url: '/ulab/result/revertDefaultAjax',
            data: {
                protocol_id: protocolId
            },
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    equipmentUsedSelect.empty()
                    
                    if (data.default_equipment) {
                        $.each(data.default_equipment, function(key, value) {
                            equipmentUsedSelect.append(
                                `<option value="${value.b_o_id}" class="${value.bg_color}">
                                    ${value.TYPE_OBORUD} ${value.OBJECT}, инв. номер ${value.REG_NUM}
                                </option>`
                            )
                        })
                        
                        let equipmentIds = data.default_equipment_ids || []
                        $('#equipmentIds').val(JSON.stringify(equipmentIds))
                    } else {
                        $('#equipmentIds').val('[]')
                    }
                } else if (data.error) {
                    console.log(data.error.message)
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
                console.error(msg)
            }
        })
    })


    $body.on('click', '.protocol-information', function () {
        const dealId = $('#deal_id').val()

        const _this = $(this),
            protocolInformation = $('#protocolInformation')

        let protocolId = $(this).data('protocol')

        _this.prop('disabled', true);
        _this.find('.icon').addClass('d-none');
        _this.append(
            `<div class="spinner-border" role="status">
                <span class="visually-hidden">Загрузка...</span>
            </div>`
        );

        // Сбрасываем значения формы
        protocolInformation.find('.verify').html('');
        protocolInformation.find('.equipment-used').html('');
        protocolInformation.find('.equipment').html('');
        protocolInformation.find('#equipmentIds').val('');
        protocolInformation[0].reset()

        $.ajax({
            method: 'POST',
            url: '/ulab/result/getProtocolInfoAjax',
            data: {
                deal_id: dealId,
                protocol_id: protocolId,
            },
            dataType: "json",
            success: function (data) {
                let count = Object.keys(data).length

                if (count) {
                    let verify = protocolInformation.find('.verify'),
                        equipmentUsed = protocolInformation.find('.equipment-used'),
                        equipment = protocolInformation.find('#equipment')

                    $.each(data['assigned'], function(key, value) {
                        verify.append(`<option value="${value['user_id']}">${value['user_name']}</option>`);
                    });

                    $.each(data['protocol_equipment'], function(key, value) {
                        equipmentUsed.append(
                            `<option value="${value['b_o_id']}" class="${value['bg_color']}">
                                ${value['TYPE_OBORUD']} ${value['OBJECT']}, инв. номер ${value['REG_NUM']}
                            </option>`
                        );
                    });

                    $.each(data['oboruds'], function(key, value) {
                        equipment.append(
                            `<option value="${value['o_id']}" data-gost="${value['g_id']}">
                                ${value['TYPE_OBORUD']} ${value['OBJECT']}
                                , инв. номер ${value['REG_NUM']} ${value['reg_doc']}, ${value['clause']}
                            </option>`
                        );
                    });

                    let protocolNum = data['protocol']['NUMBER'] ? data['protocol']['NUMBER'] : 'Номер не присвоен';

                    protocolInformation.find('.title').text(`Информация по протоколу № ${protocolNum}`);
                    protocolInformation.find('input[name="protocol_id"]').val(protocolId);

                    // Общая информация
                    let protocolType = data['protocol']['PROTOCOL_TYPE'] ? data['protocol']['PROTOCOL_TYPE'] : 0;
                    protocolInformation.find('.protocol-type').val(protocolType);
                    let protocolTemplate = data['protocol']['id_template'] ? data['protocol']['id_template'] : 0;
                    protocolInformation.find('.protocol-template').val(protocolTemplate);
                    protocolInformation.find('.verify').val(data['protocol']['verify']);
                    protocolInformation.find('.no-evaluate').prop('checked', +data['protocol']['NO_COMPLIANCE']);

                    //Информация об испытаниях
                    let dateBegin = protocolInformation.find('.date-begin'),
                        dateEnd = protocolInformation.find('.date-end'),
                        temp1 = protocolInformation.find('.temp1'),
                        temp2 = protocolInformation.find('.temp2'),
                        wet1 = protocolInformation.find('.wet1'),
                        wet2 = protocolInformation.find('.wet2'),
                        changeTrialsConditions = protocolInformation.find('.change-trials-conditions'),
                        attestatInProtocol = protocolInformation.find('.attestat-in-protocol'),
                        switchAttestat = attestatInProtocol.closest('.switch'),
                        isChangeTrialsConditions = +data['protocol']['CHANGE_TRIALS_CONDITIONS'] || data['is_deal_nk'],
                        changeTrialsDate = +data['protocol']['CHANGE_TRIALS_DATE'] || data['is_deal_osk'] || data['is_deal_nk'];

                    dateBegin.val(data['dates_trials']['date_begin']);
                    dateBegin.prop('readonly', !changeTrialsDate);
                    dateBegin.data('protocol', protocolId);
                    changeTrialsDate ? dateBegin.removeClass('bg-light-secondary').addClass('bg-white') :
                        dateBegin.removeClass('bg-white').addClass('bg-light-secondary');

                    dateEnd.val(data['dates_trials']['date_end']);
                    dateEnd.prop('readonly', !changeTrialsDate);
                    dateEnd.data('protocol', protocolId);
                    changeTrialsDate ? dateEnd.removeClass('bg-light-secondary').addClass('bg-white') :
                        dateEnd.removeClass('bg-white').addClass('bg-light-secondary');

                    temp1.val(data['conditions']['TEMP_O']);
                    temp1.prop('readonly', !isChangeTrialsConditions);
                    isChangeTrialsConditions ? temp1.removeClass('bg-light-secondary').addClass('bg-white') :
                        temp1.removeClass('bg-white').addClass('bg-light-secondary');

                    temp2.val(data['conditions']['TEMP_TO_O']);
                    temp2.prop('readonly', !isChangeTrialsConditions);
                    isChangeTrialsConditions ? temp2.removeClass('bg-light-secondary').addClass('bg-white') :
                        temp2.removeClass('bg-white').addClass('bg-light-secondary');

                    wet1.val(data['conditions']['VLAG_O']);
                    wet1.prop('readonly', !isChangeTrialsConditions);
                    isChangeTrialsConditions ? wet1.removeClass('bg-light-secondary').addClass('bg-white') :
                        wet1.removeClass('bg-white').addClass('bg-light-secondary');

                    wet2.val(data['conditions']['VLAG_TO_O']);
                    wet2.prop('readonly', !isChangeTrialsConditions);
                    isChangeTrialsConditions ? wet2.removeClass('bg-light-secondary').addClass('bg-white') :
                        wet2.removeClass('bg-white').addClass('bg-light-secondary');

                    protocolInformation.find('.change-trials-date').prop('checked', +data['protocol']['CHANGE_TRIALS_DATE']);
                    changeTrialsConditions.data('protocol', protocolId);
                    changeTrialsConditions.prop('checked', +data['protocol']['CHANGE_TRIALS_CONDITIONS']);
                    protocolInformation.find('.output-in-protocol').prop('checked', +data['protocol']['OUTPUT_IN_PROTOCOL']);

                    //Информация об оборудовании
                    protocolInformation.find('.revert-default').data('protocolId', protocolId);
                    protocolInformation.find('#equipmentIds').val(data['equipment_ids_json']);

                    //Данные объекта испытаний
                    protocolInformation.find('.object-description').val(data['object_data']['DESCRIPTION']);
                    protocolInformation.find('.object').val(data['object_data']['OBJECT']);
                    protocolInformation.find('.place-probe').val(data['object_data']['PLACE_PROBE']);
                    protocolInformation.find('.date-probe').val(data['object_data']['DATE_PROBE']);
                    protocolInformation.find('.additional-information').val(data['protocol']['DOP_INFO']);
                    protocolInformation.find('.protocol-outside-lis').prop('checked', +data['protocol']['PROTOCOL_OUTSIDE_LIS']);
                    attestatInProtocol.prop('checked', +data['protocol']['ATTESTAT_IN_PROTOCOL']);
                    attestatInProtocol.prop('disabled', !+data['protocol']['IN_ATTESTAT_DIAPASON']); // Если значение или методика у протокола НЕ в диапазоне аттестата(не соответствуют условиям аттестации), не в ОА, то выдать(выбрать) протокол "C аттестатом аккредитации" нельзя

                    if (!+data['protocol']['IN_ATTESTAT_DIAPASON']) {
                        if (!switchAttestat.hasClass('opacity-30')) {
                            switchAttestat.addClass('opacity-30');
                            switchAttestat.prop('title', 'Значение или методика у протокола не в ОА или в ОА но не подтверждено');
                        }
                    } else {
                        switchAttestat.removeClass('opacity-30');
                        switchAttestat.prop('title', '');
                    }
                }

                $.magnificPopup.open({
                    items: {
                        src: protocolInformation,
                        type: 'inline',
                        fixedContentPos: false
                    },
                    closeOnBgClick: false
                });

                _this.prop('disabled', false);
                _this.find('.icon').removeClass('d-none');
                _this.find('.spinner-border').remove();

                protocolInformation.find('.select2').select2({
                    theme: 'bootstrap-5',
                    width: 'resolve',
                })
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
        })
    })

    /**
     * Проверка условия у протокола перед формированием
     */
    $body.on('click', '.validate-conditions', function (e) {
        let success = true
        let protocolId = $(this).data('protocol_id')

        $('.messages').empty();

        $.ajax({
            method: 'POST',
            cache: false,
            async: false,
            url: '/ulab/result/validateProtocolAjax/',
            dataType: 'json',
            data: {
                protocol_id: protocolId
            },
            success: function (data) {
                if ( data.success ) {
                    success = true
                } else {
                    success = false
                    $.each(data.errors, function (i, item) {
                        showErrorMessage(item)
                    })
                    window.scrollTo(0,0)
                }
            }
        })

        return success
    })
})
