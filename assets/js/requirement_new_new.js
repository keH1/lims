let methodList = null
let conditionList = null
let normDocList = null

let $strTotal = null
let $inputTotal = null
let $inputDiscount = null

$.ajax({
    method: 'POST',
    url: '/ulab/requirement/getMethodsAjax',
    dataType: 'json',
    success: function (data) {
        methodList = data
    }
})

$.ajax({
    method: 'POST',
    url: '/ulab/requirement/getTechCondListAjax',
    dataType: 'json',
    success: function (data) {
        conditionList = data
    }
})

$.ajax({
    method: 'POST',
    url: '/ulab/normDocGost/getNormDocListAjax',
    dataType: 'json',
    success: function (data) {
        normDocList = data
    }
})

function formatState(state) {
    const option = $(state.element)
    const color = option.data("color")

    if (!color) {
        return state.text
    }

    return $(`<span style="color: ${color}">${state.text}</span>`)
}


$(function ($) {
    const $body = $('body')

    const $journalMaterial = $('#journal_material_2')

    const dealId = $('#deal_id').val()

    $strTotal = $('#str_total')
    $inputTotal = $('#price-total')
    $inputDiscount = $('#price_discount')

    $('#inlineRadio22, #inlineRadio21').change(function () {
        $('#compliance').prop('disabled', !$('#inlineRadio22').prop('checked'))
    })

    $body.on('change', '.material-check', function () {
        let materialId = $(this).data('material_id')
        $body.find(`.material-id-${materialId}`).prop("checked", $(this).prop("checked"))

        toggleProbe()
    })

    $body.on('change', '.probe-check', function () {
        let materialId = $(this).data('material_id')

        let parent = $body.find(`#material_name_${materialId}`),
            all = true,
            curState = $(this).prop("checked")

        $body.find(`.material-id-${materialId}`).each(function () {
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

        toggleProbe()
    })

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
        templateResult: formatState,
        templateSelection: formatState,
        placeholder: $(this).data('placeholder'),
        width: '100%',
    })

    $('#journal_material_2 tbody').on('click', 'td.act-details-control', function () {
        let tr = $(this).closest('tr')
        let row = journalDataTable.row(tr)

        if (row.child.isShown()) {
            let table = $("table", row.child())
            table.DataTable().destroy()
            table.detach()

            // Эта строка уже открыта - закрываем
            row.child.hide()
            tr.removeClass('shown')

            $(this).html('<i class="fa-solid fa-chevron-right icon-big"></i>')
        }
        else {
            // Открыть эту строку
            createChild(row)
            tr.addClass('shown')

            $(this).html('<i class="fa-solid fa-chevron-down opacity-30 icon-big"></i>')
        }
    })

    let journalDataTable = $journalMaterial.DataTable({
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
                d.deal_id = dealId
                d.material_id = $('#filter-material option:selected').val()
                d.cipher = $('#filter-cipher').val()
                d.work_id = $('.work_radio:checked')?.val()
            },
            url : '/ulab/requirement/getMaterialProbeJournalAjax/',
            dataSrc: function (json) {
                return json.data
            }
        },
        columns: [
            {
                data: 'material_name',
                orderable: false,
                render: function (data, type, item) {
                    return `
                    <div class="form-check">
                        <input class="form-check-input material-check" type="checkbox" data-material_id="${item['material_id']}" id="material_name_${item['material_id']}">
                        <label class="form-check-label" for="material_name_${item['material_id']}">
                            ${item['material_name']}
                        </label>
                    </div>`
                }
            },
            {
                data: 'ewq',
                orderable: false,
                className: 'act-details-control',
                render: function (data, type, item) {
                    return `<i class="fa-solid fa-chevron-right icon-big"></i>`
                }
            },
            {
                data: 'cipher',
                orderable: false,
                className: 'ps-4',
                render: function (data, type, item) {
                    return `
                    <div class="form-check">
                        <input class="form-check-input filter-method filter-probe-id probe-check material-id-${item['material_id']}" data-material_id="${item['material_id']}" type="checkbox" value="${item['id']}" id="proba_name_${item['id']}">
                        <label class="form-check-label" for="proba_name_${item['id']}">
                            ${item['cipher']}
                        </label>
                    </div>`
                }
            },
            {
                data: 'name_for_protocol',
                orderable: false,
            },
            {
                data: 'count_methods',
                width: '40px',
                className: 'text-center',
                orderable: false,
                render: function (data, type, item) {
                    return `<span class="count_methods_probe_${item.id}">${item.count_methods}</span>`
                }
            },
            {
                data: 'edit',
                orderable: false,
                className: 'text-end',
                render: function (data, type, item) {
                    return `
                    <a class="btn btn-primary btn-sm popup-edit-probe-form"><i class="fa-solid fa-pencil"></i></a>`
                }
            },
        ],
        columnDefs: [{ visible: false, targets: 0 }],
        drawCallback: function (settings) {
            let api = this.api();
            let rows = api.rows({ page: 'current' }).nodes();
            let last = null;
            let data = rows.data()

            api.column(0, { page: 'current' })
                .data()
                .each(function (group, i) {
                    if (last !== group) {
                        $(rows)
                            .eq(i)
                            .before(
                                `<tr>
                                    <td></td>
                                    <td colspan="5">
                                        <div class="form-check">
                                            <input class="form-check-input material-check" type="checkbox" data-material_id="${data[i].material_id}" id="material_name_${data[i].material_id}">
                                            <label class="form-check-label" for="material_name_${data[i].material_id}">
                                                <strong>${group}</strong>
                                            </label>
                                        </div>
                                    </td>
                                </tr>`
                            );

                        last = group;
                    }
                });
        },
        createdRow: function( row, data, dataIndex ) {
            // $(row).addClass("bg-pele-green")
        },
        language: dataTablesSettings.language,
        lengthMenu: [[10, 25, 50, 100, -1], [10,25, 50, 100, "Все"]],
        pageLength: 10,
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

    $(window).on('resize', function () {
        $('#journal_material_2').DataTable().columns.adjust()
    })

    let tmpTimeout = 0
    $body.on('input', '.filter, .work_radio', function () {
        clearTimeout(tmpTimeout)
        tmpTimeout = setTimeout(function() {
            journalDataTable.ajax.reload()
        }.bind(this), 1000)

        let $btnGroupEdit = $('.btn-group-edit')
        let $btnAddMethods = $('.btn-add-methods')

        $btnGroupEdit.addClass('disabled')
        $btnAddMethods.addClass('disabled')
    })

    // применить скидку
    $body.on('click', '.discount-apply', function () {
        let totalPrice = parseFloat($('#price-total').val())
        let discountVal = parseFloat($('.discount-input').val())
        let discountType = $('.discount-type').val()

        let discountPrice = 0

        if ( isNaN(totalPrice) ) {
            totalPrice = 0
        }

        if ( isNaN(discountVal) ) {
            discountVal = 0
            $('.discount-input').val(0)
        }

        if ( discountType == 'percent' ) {
            if ( discountVal < 0 ) {
                discountVal = 0
            } else if ( discountVal > 100 ) {
                discountVal = 100
            }

            discountPrice = totalPrice - totalPrice * discountVal / 100
        } else if ( discountType == 'rub' && discountVal >= 0 ) {
            discountPrice = totalPrice - discountVal
        }

        if (discountPrice < 0) {
            discountPrice = 0
        }

        $('#price_discount').val(discountPrice.toFixed(2))
        $strTotal.text(discountPrice.toFixed(2) + ' руб.')
    })

    $('#form_requirement').on('submit', function () {
        $('.discount-apply').trigger('click')
    })

    $body.on('click', '.popup-edit-probe-form', function (e) {
        let data = journalDataTable.row(e.target.closest('tr')).data()

        $('#edit-probe-modal-form').find('.probe_id').val(data.id)
        $('#edit-probe-modal-form').find('.name_for_protocol').val(data.name_for_protocol)

        let $selectGroup = $('#edit-probe-modal-form').find('.select_group')

        let htmlOptionsGroup = `<option value="">Без группы</option>`
        $.ajax({
            url: "/ulab/material/getGroupByMaterialAjax/",
            method: "POST",
            cache: false,
            async: false,
            dataType: "json",
            data: {
                material_id: data.material_id,
            },
            success: function (json) {
                htmlOptionsGroup += getHtmlOptions(json, data.group)

                $selectGroup.html(htmlOptionsGroup)

                $selectGroup.select2({
                    theme: 'bootstrap-5'
                })
            }
        })

        $.magnificPopup.open({
            items: {
                src: '#edit-probe-modal-form'
            },
            type: 'inline',
            closeBtnInside: true,
            fixedContentPos: false,
            closeOnBgClick: false,
            focus: '#focus-blur-loop-select',
            midClick: true
        })
    })

    $body.on('click', '.delete-probe-btn', function () {
        return confirm(
            "Подтвердите удаление пробы и всех прикрепленных методик.\n" +
            "Пробы, по которым сформирован акт, невозможно удалить.\n" +
            "Пробы, у которых есть результаты испытаний, невозможно удалить.\n" +
            "Удаление последней пробы у материала, удалит материал."
        )
    })

    function toggleProbe() {
        let probeIdList = $(".filter-probe-id:checked").map(function(){
            return $(this).val();
        }).get()

        let $btnGroupEdit = $('.btn-group-edit')
        let $btnAddMethods = $('.btn-add-methods')

        if ( probeIdList.length > 0 ) {
            $btnGroupEdit.removeClass('disabled')
            $btnAddMethods.removeClass('disabled')
        } else {
            $btnGroupEdit.addClass('disabled')
            $btnAddMethods.addClass('disabled')
        }
    }

    $body.on('click', '.btn-add-methods', function () {
        let probeIdList = $(".filter-probe-id:checked").map(function(){
            return $(this).val();
        }).get()
        let materialId = $(".filter-probe-id:checked").data('material_id')

        $('.count-selected-probe').text(probeIdList.length)
        $('.probe-id-list').val(probeIdList)

        let $selectScheme = $('#add-methods-modal-form').find('#select-scheme')
        let $btnApply = $('#apply-scheme')

        let htmlOptionsScheme = `<option value="">Нет схемы / ручной ввод</option>`
        $.ajax({
            url: "/ulab/material/getSchemeByMaterialAjax/",
            method: "POST",
            cache: false,
            async: false,
            dataType: "json",
            data: {
                material_id: materialId,
            },
            success: function (json) {
                if ( json.length === 0 ) {
                    $selectScheme.prop('disabled', true)
                    $btnApply.prop('disabled', true)
                } else {
                    $selectScheme.prop('disabled', false)
                    $btnApply.prop('disabled', false)
                }

                htmlOptionsScheme += getHtmlOptions(json)

                $selectScheme.html(htmlOptionsScheme)

                $selectScheme.select2({
                    theme: 'bootstrap-5',
                })
            }
        })
    })

    $body.on('click', '#apply-scheme', function () {
        let schemeId = $("#select-scheme").val()
        const $methodContainer = $('.method-container')
        const $methodBlock = $methodContainer.find('.method-block')
        let countMethod = 0

        if ( $methodBlock.length > 0 ) {
            let num = $methodContainer.find('.method-block').map(function() {
                return $(this).data('gost_number');
            }).get();

            countMethod = Math.max.apply(Math, num) + 1;
        }

        if ( schemeId !== undefined && schemeId > 0 ) {
            $('#scheme_id').val(schemeId)

            $.ajax({
                url: "/ulab/material/getSchemeParamAjax/",
                method: "POST",
                cache: false,
                async: false,
                dataType: "json",
                data: {
                    scheme_id: schemeId
                },
                success: function (json) {
                    $.each(json, function (i, item) {
                        $methodContainer.append(getHtmlMethod(methodList, normDocList, i + countMethod, item.method_id, item.nd_id))

                        $methodContainer.find('.method-block:last-child').find('.method-select').trigger('change')
                        $methodContainer.find('.method-block:last-child').find('.tu-select').trigger('change')
                        $methodContainer.find('.method-block:last-child').find('.select2').select2({
                            theme: 'bootstrap-5',
                            templateResult: formatState,
                            templateSelection: formatState,
                        })
                    })

                }
            })
        }
    })

    $body.on('click', '.paginate_button', function () {
        let $btnGroupEdit = $('.btn-group-edit')
        let $btnAddMethods = $('.btn-add-methods')

        $btnGroupEdit.addClass('disabled')
        $btnAddMethods.addClass('disabled')
    })

    // добавляем строчку с методикой в модальном окне добавление методик
    $body.on('click', '.add-new-method', function () {
        const $methodContainer = $('.method-container')
        const $methodBlock = $methodContainer.find('.method-block')
        let countMethod = 0

        if ( $methodBlock.length > 0 ) {
            let num = $methodContainer.find('.method-block').map(function() {
                return $(this).data('gost_number');
            }).get();

            countMethod = Math.max.apply(Math, num) + 1;
        }

        $methodContainer.append(getHtmlMethod(methodList, normDocList, countMethod))

        $methodContainer.find('.method-block:last-child').find('.select2').select2({
            theme: 'bootstrap-5',
            templateResult: formatState,
            templateSelection: formatState,
        })
    })

    // удаляем строчку с методикой в модальном окне добавление методик
    $body.on('click', '.del-new-method', function () {
        $(this).parents('.method-block').remove()
    })

    // выбираем методику в модальном окне добавление методик
    $body.on('change', '.method-select', function () {
        const id = $(this).val()
        const $parent = $(this).parents('.method-block')

        let data = []

        if ( id > 0 ) {
            data = methodList[id]

            $parent.find('.method-link')
                .removeClass('disabled')
                .attr('href', `/ulab/gost/method/${id}`)
        } else {
            data.price = 0
            data.assigned = []

            $parent.find('.method-link')
                .addClass('disabled')
                .attr('href', ``)
        }

        $parent.find('.price-input').val(parseFloat(data.price))

        let options = `<option value="">Исполнитель</option>`
        $.each(data.assigned, function (i, item) {
            options += `<option value="${item.user_id}">${item.short_name}</option>`
        })

        $parent.find('.user-select').html(options)
    })

    // выбираем ТУ в модальном окне добавление методик
    $body.on('change', '.tu-select', function () {
        const id = $(this).val()

        if ( id > 0 ) {
            $(this).parents('.method-block').find('.tu-link')
                .removeClass('disabled')
                .attr('href', `/ulab/normDocGost/method/${id}`)
        } else {
            $(this).parents('.method-block').find('.tu-link')
                .addClass('disabled')
                .attr('href', ``)
        }
    })


    $body.on('change', '.clear_confirm_change', function () {
        $('#clear_confirm').val('1')
    })

    // отправить тз на проверку
    $body.on('click', '.sent_tz', function () {
        const delaId = $('#deal_id').val()
        const $block = $('.head-user-block')
        const $btn = $(this)

        $btn.find('i').removeClass().addClass('fa-solid fa-arrows-rotate spinner-animation')
        $btn.addClass('disable')

        $.ajax({
            method: 'POST',
            data: {
                deal_id: delaId
            },
            url: '/ulab/requirement/confirmTzSentAjax',
            dataType: 'json',
        }).always( function (data) {
            $block.find('i').removeClass().addClass('fa-solid fa-hourglass-half').attr('title', 'Ожидание проверки')
            $btn.html(`<i class="fa-regular fa-paper-plane"></i> Передано`)
        })
    })

    // одобрить тз
    $body.on('click', '.approve_tz', function () {
        const tzId = $('#tz_id').val()
        const $block = $('.curr_user')
        const $btn = $(this)

        $btn.find('i').removeClass().addClass('fa-solid fa-arrows-rotate spinner-animation')
        $btn.addClass('disable')
        $('.not_approve_tz').addClass('disable')

        $.ajax({
            method: 'POST',
            data: {
                tz_id: tzId
            },
            url: '/ulab/requirement/confirmTzApproveAjax',
            dataType: 'json',
        }).always( function (data) {
            $block.find('.icon').addClass('text-green').find('i').removeClass().addClass('fa-regular fa-circle-check').attr('title', 'ТЗ потверждено')
            $btn.find('i').removeClass().addClass('fa-regular fa-circle-check')
        })
    })

    // отправить и одобрить тз
    $body.on('click', '.sent_approve_tz', function () {
        const tzId = $('#tz_id').val()
        const delaId = $('#deal_id').val()
        const $headBlock = $('.head-user-block')
        const $block = $('.curr_user')
        const $btn = $(this)

        $btn.find('i').removeClass().addClass('fa-solid fa-arrows-rotate spinner-animation')
        $btn.addClass('disable')
        $('.not_approve_tz').addClass('disable')

        $.ajax({
            method: 'POST',
            data: {
                tz_id: tzId,
                deal_id: delaId,
            },
            url: '/ulab/requirement/confirmTzSentApproveAjax',
            dataType: 'json',
        }).always( function (data) {
            $headBlock.find('i').removeClass().addClass('fa-solid fa-hourglass-half').attr('title', 'Ожидание проверки')
            $block.find('.icon').addClass('text-green').find('i').removeClass().addClass('fa-regular fa-circle-check').attr('title', 'ТЗ потверждено')
            $btn.find('i').removeClass().addClass('fa-regular fa-circle-check')
        })
    })

    // не одобрить тз
    $body.on('click', '.not_approve_tz_btn', function () {
        const tzId = $('#tz_id').val()
        const $block = $('.curr_user')
        const $btn = $(this)
        const desc = $('#desc_return').val()

        $btn.find('i').removeClass().addClass('fa-solid fa-arrows-rotate spinner-animation')
        $('.not_approve_tz').addClass('disable')
        $('.approve_tz').addClass('disable')

        $.ajax({
            method: 'POST',
            data: {
                tz_id: tzId,
                desc: desc
            },
            url: '/ulab/requirement/confirmTzNotApproveAjax',
            dataType: 'json',
        }).always( function (data) {
            $block.find('.icon').addClass('text-red').find('i').removeClass().addClass('fa-regular fa-circle-xmark')
            $btn.find('i').removeClass().addClass('fa-regular fa-circle-xmark')
            $.magnificPopup.close()
            location.reload()
        })
    })

    // Добавление объекта испытаний
    $('#add-material-modal-form').on('submit', function () {
        let $form = $(this)
        let $button = $form.find(`button[type="submit"]`)
        let btnHtml = $button.html()

        $button.html(`<i class="fa-solid fa-arrows-rotate spinner-animation"></i>`)
        $button.addClass('disabled')

        $.ajax({
            url: "/ulab/requirement/addMaterialToTzAjax/",
            data: $form.serialize(),
            dataType: "json",
            async: true,
            method: "POST",
            complete: function () {
                journalDataTable.ajax.reload()

                $button.html(btnHtml)
                $button.removeClass('disabled')

                $form.find('input[type="number"]').val(1)
                $form.find('select').val(null).trigger('change')

                $.magnificPopup.close()
            }
        })

        return false
    })

    // Добавление проб
    $('#add-probe-modal-form').on('submit', function () {
        let $form = $(this)
        let $button = $form.find(`button[type="submit"]`)
        let btnHtml = $button.html()

        $button.html(`<i class="fa-solid fa-arrows-rotate spinner-animation"></i>`)
        $button.addClass('disabled')

        $.ajax({
            url: "/ulab/requirement/addProbeToMaterialAjax/",
            data: $form.serialize(),
            dataType: "json",
            async: true,
            method: "POST",
            complete: function () {
                journalDataTable.ajax.reload()

                $button.html(btnHtml)
                $button.removeClass('disabled')

                $form.find('input[type="number"]').val(1)
                $form.find('select').val(null).trigger('change')

                $.magnificPopup.close()
            }
        })

        return false
    })

    // Добавление методик
    $('#add-methods-modal-form').on('submit', function () {
        let $form = $(this)
        let $button = $form.find(`button[type="submit"]`)
        let btnHtml = $button.html()

        $button.html(`<i class="fa-solid fa-arrows-rotate spinner-animation"></i>`)
        $button.addClass('disabled')

        $.ajax({
            url: "/ulab/requirement/addMethodsToProbeAjax/",
            data: $form.serialize(),
            dataType: "json",
            async: true,
            method: "POST",
            complete: function(xhr) {
                $form.find('.method-container').empty()

                journalDataTable.ajax.reload()

                if (xhr.responseJSON && xhr.responseJSON.success && xhr.responseJSON.priceData) {
                    $('#str_total').text(xhr.responseJSON.priceData.price_ru)
                    $('#price-total').val(xhr.responseJSON.priceData.price)
                    $('#price_discount').val(xhr.responseJSON.priceData.price_discount)
                }

                $button.html(btnHtml)
                $button.removeClass('disabled')
                $('.btn-add-methods').addClass('disabled')

                $.magnificPopup.close()
            }
        })

        return false
    })

    $('#edit-probe-modal-form button[type="submit"]').on('click', function () {
        $('#button_action').val($(this).val())
    })

    // Редактирование пробы
    $('#edit-probe-modal-form').on('submit', function () {
        let $form = $(this)
        let $button = $form.find(`button[type="submit"]`)

        $button.addClass('disabled')

        $.ajax({
            url: "/ulab/requirement/editProbeAjax/",
            data: $form.serialize(),
            dataType: "json",
            async: true,
            method: "POST",
            success: function (json) {
                if ( !json.success ) {
                    showErrorMessage(json.error, '#error-message')

                    $button.removeClass('disabled')

                    $.magnificPopup.close()
                }

                if ( json.type === 'delete' ) {
                    $strTotal.text(json.data.price_ru)
                    $inputTotal.val(json.data.price)
                    $inputDiscount.val(json.data.price_discount)
                }

                journalDataTable.ajax.reload()
            },
            complete: function () {
                $button.removeClass('disabled')

                $.magnificPopup.close()
            }
        })

        return false
    })

    // Добавление работы
    $('#add-work-modal-form').on('submit', function (e) {
        e.preventDefault()

        let dealId = $('#deal_id').val()
        let $form = $(this)
        let $workLastRow = $('#work_table_last_row')
        let $button = $form.find(`button[type="submit"]`)
        let btnHtml = $button.html()
        let formData = new FormData($form[0])


        $button.html(`<i class="fa-solid fa-arrows-rotate spinner-animation"></i>`)
        $button.addClass('disabled')

        $.ajax({
            url: "/ulab/requirement/addWorkAjax/",
            data: formData,
            dataType: "json",
            contentType: false,
            cache: false,
            processData:false,
            async: true,
            method: "POST",
            success: function (json) {
                let linkFileResult =
                    `<form class="form form-upload-file" method="post"
                          action="#"
                          enctype="multipart/form-data">
                        <input type="hidden" name="work_id" value="${json.data.work_id}">
                        <input type="hidden" name="deal_id" value="${dealId}">
                        <label class="btn btn-sm btn-success" title="Загрузить результаты испытаний">
                            Добавить
                            <input class="d-none" type="file" name="file_result" accept=".doc, .docx, .xls, .xlsx, .pdf">
                        </label>
                    </form>`
                let linkFileProtocol =
                    `<form class="form form-upload-file" method="post"
                          action="#"
                          enctype="multipart/form-data">
                        <input type="hidden" name="work_id" value="${json.data.work_id}">
                        <input type="hidden" name="deal_id" value="${dealId}">
                        <label class="btn btn-sm btn-success" title="Загрузить протокол испытаний">
                            Добавить
                            <input class="d-none" type="file" name="file_protocol" accept=".doc, .docx, .xls, .xlsx, .pdf">
                        </label>
                    </form>`
                let textDateProtocol = ``

                if (json?.data?.file_name_result) {
                    linkFileResult =
                        `<a href="/ulab/upload/request/${dealId}/government_work/${json.data.work_id}/result/${json.data.file_name_result}">
                            ${json.data.file_name_result}
                        </a>`
                }
                if (json?.data?.file_name_protocol) {
                    linkFileProtocol =
                        `<a href="/ulab/upload/request/${dealId}/government_work/${json.data.work_id}/protocol/${json.data.file_name_protocol}">
                            ${json.data.file_name_protocol}
                        </a>`

                    textDateProtocol = json.data.date_protocol
                }

                $workLastRow.before(`
                <tr>
                    <td class="text-center">
                        <input type="radio" class="form-check-input work_radio" name="work_radio" id="work_radio_${json?.data?.work_id??0}" value="${json?.data?.work_id??0}">
                    </td>
                    <td>
                        <label for="work_radio_${json?.data?.work_id??0}">${json?.data?.name?? ''}</label>
                    </td>
                    <td>
                        ${json?.data?.object?? ''}
                    </td>
                    <td>
                        ${json?.data?.material_name?? ''}
                    </td>
                    <td>
                        ${json?.data?.probe_count?? 1}
                    </td>
                    <td>
                        Испытания не начаты
                    </td>
                    <td class="text-center">
                        ${linkFileResult}
                    </td>
                    <td class="text-center">
                        ${linkFileProtocol}
                    </td>
                    <td class="text-center text_date_protocol">
                        ${textDateProtocol}
                    </td>
                </tr>
                `)
            },
            complete: function () {
                journalDataTable.ajax.reload()

                $button.html(btnHtml)
                $button.removeClass('disabled')

                $.magnificPopup.close()
            }
        })

        return false
    })

    $body.on('change', '.form-upload-file', function () {
        $(this).closest('form').trigger('submit')
    })

    $body.on('submit', '.form-upload-file', function (e) {
        e.preventDefault()

        let $form = $(this)
        let $td = $form.closest('td')
        let formData = new FormData($form[0])

        $.ajax({
            url: "/ulab/requirement/addFileWorkAjax/",
            data: formData,
            dataType: "json",
            contentType: false,
            cache: false,
            processData:false,
            async: true,
            method: "POST",
            success: function (json) {
                if ( json?.data?.file_name_result !== undefined ) {
                    $td.html(
                        `<a href="/ulab/upload/request/${dealId}/government_work/${json.data.work_id}/result/${json.data.file_name_result}">
                            ${json.data.file_name_result}
                        </a>`
                    )
                }
                if ( json?.data?.file_name_protocol !== undefined ) {
                    $td.html(
                        `<a href="/ulab/upload/request/${dealId}/government_work/${json.data.work_id}/protocol/${json.data.file_name_protocol}">
                            ${json.data.file_name_protocol}
                        </a>`
                    )

                    $td.closest('tr').find('.text_date_protocol').html(json.data.date_protocol)
                }
            }
        })

        return false
    })
})


/**
 *
 * @param methodList
 * @param normDocList
 * @param gostNumber
 * @param defaultMethod
 * @param defaultTu
 * @returns {string}
 */
function getHtmlMethod(methodList, normDocList, gostNumber = 0, defaultMethod = 0, defaultTu = 0) {
    let optionMethod = getHtmlOptionsMethod(methodList, defaultMethod)
    let optionCondition = getHtmlOptionsNormDoc(normDocList, defaultTu)
    let typeRequest = $('#type_id').val()

    return `<div class="row justify-content-between method-block mb-2" data-gost_number="${gostNumber}">
                <div class="col-4">
                    <div class="input-group">
                        <select class="form-control select2 method-select" name="form[${gostNumber}][new_method_id]" required>
                            <option value=""></option>
                            ${optionMethod}
                        </select>
                        <a class="btn btn-outline-secondary method-link disabled"  title="Перейти в методику" href="">
                            <i class="fa-solid fa-right-to-bracket"></i>
                        </a>
                    </div>
                </div>
                <div class="col-4">
                    <div class="input-group">
                        <select class="form-control select2 tu-select" name="form[${gostNumber}][norm_doc_method_id]">
                            ${optionCondition}
                        </select>
                        <a class="btn btn-outline-secondary tu-link disabled"  title="Перейти в Нормативную документацию" href="">
                            <i class="fa-solid fa-right-to-bracket"></i>
                        </a>
                    </div>
                </div>
                <div class="col-2">
                    <select class="form-control user-select" name="form[${gostNumber}][assigned_id]">
                        <option value="">Исполнитель</option>
                    </select>
                </div>
                <div class="col ${typeRequest == 9? 'd-none' : ''}">
                    <div class="input-group">
                        <input class="form-control price-input" name="form[${gostNumber}][price]" type="number" min="0" step="0.01" value="0">
                        <span class="input-group-text">₽</span>
                    </div>
                </div>
                <div class="col-auto">
                    <button
                            class="btn btn-danger mt-0 del-new-method btn-square float-end"
                            type="button"
                    >
                        <i class="fa-solid fa-minus icon-fix"></i>
                    </button>
                </div>
            </div>`
}


/**
 *
 * @param conditionList
 * @param defaultTu
 * @returns {string}
 */
function getHtmlOptionsTu(conditionList, defaultTu = 0) {
    let optionCondition = '<option value="">--/--</option>'

    $.each(conditionList, function (i, item) {
        let selected = ``

        if ( item.id == defaultTu ) {
            selected = 'selected'
        }

        optionCondition += `<option value="${item.id}" ${selected}>${item.view_name}</option>`
    })

    return optionCondition
}


/**
 *
 * @param normDocList
 * @param defaultId
 * @returns {string}
 */
function getHtmlOptionsNormDoc(normDocList, defaultId = 0) {
    let optionCondition = '<option value="">--/--</option>'

    $.each(normDocList, function (i, item) {
        let selected = item.id == defaultId ? 'selected' : ''
        optionCondition += `<option value="${item.id}" ${selected}>${item.view_gost}</option>`
    })

    return optionCondition
}


/**
 *
 * @param methodList
 * @param defaultMethod
 * @returns {string}
 */
function getHtmlOptionsMethod(methodList, defaultMethod = 0) {
    let optionMethod = ''

    $.each(methodList, function (i, item) {

        let dataColor = ''
        let selected = ``

        if ( item.is_actual == 0 ) {
            dataColor = `data-color="#F00"`
        } else if ( item.in_field == 0 ) {
            dataColor = `data-color="#060060"`
        } else if ( item.is_confirm == 0 ) {
            dataColor = `data-color="#dfdf11"`
        }

        if ( item.id == defaultMethod ) {
            selected = 'selected'
        }

        optionMethod += `<option value="${item.id}" ${dataColor} ${selected}>${item.view_gost}</option>`
    })

    return optionMethod
}


/**
 *
 * @param list
 * @param id
 * @returns {string}
 */
function getHtmlOptions(list, id = 0) {
    let optionMethod = ''

    $.each(list, function (i, item) {
        let selected = ``

        if ( item.id == id ) {
            selected = 'selected'
        }

        optionMethod += `<option value="${item.id}" ${selected}>${item.name}</option>`
    })

    return optionMethod
}


/**
 *
 * @param methodId
 * @param defaultUserId
 * @returns {string}
 */
function getHtmlOptionAssignedByMethod(methodId, defaultUserId = 0) {
    let htmlOption = ''

    $.ajax({
        method: 'POST',
        async: false,
        data: {
            id: methodId
        },
        url: '/ulab/requirement/getMethodDataAjax',
        dataType: 'json',
        success: function (data) {

            htmlOption += `<option value="">Не назначен</option>`
            $.each(data.assigned, function (i, item) {
                let selected = ``
                if ( item.user_id == defaultUserId ) {
                    selected = 'selected'
                }
                htmlOption += `<option value="${item.user_id}" ${selected}>${item.short_name}</option>`
            })
        }
    })

    return htmlOption
}


/**
 * @param gostList
 * @param defaultId
 * @returns {string}
 */
function getHtmlOptionGost(gostList, defaultId) {
    let option = ''

    $.each(gostList, function (i, item) {
        let selected = ``

        if ( item.id == defaultId ) {
            selected = 'selected'
        }

        option += `<option value="${item.id}" ${selected}>${item.reg_doc}</option>`
    })

    return option
}


function createChild(row) {
    // row — исходный объект данных
    let rowData = row.data()
    let typeRequest = $('#type_id').val()

    let probeId = []
    const dealId = rowData.deal_id
    const $spanMethodCount = $('body').find(`.count_methods_probe_${rowData.id}`)
    probeId.push(rowData.id)

    // Таблица, которую мы преобразуем в DataTable
    let table = $('<table class="table table-striped journal text-start table-hover table-sm w-100" />')

    let thead = `
        <thead>
            <tr class="table-light">
                <th scope="col">№</th>
                <th scope="col">Методика испытаний</th>
                <th scope="col">Нормативная документация</th>
                <th scope="col">Исполнитель</th>
                <th scope="col" class="${typeRequest == 9? 'd-none' : ''}">Цена</th>
                <th scope="col"></th>
            </tr>
        </thead>
    `

    table.append(thead)

    // Отобразить дочернюю строку
    row.child(table).show()

    let journal = table.DataTable({
        destroy : true,
        ordering: false,
        serverSide: true,
        rowReorder: true,
        initialValue:true,
        dom: 't<"bottom"lip>',
        pageLength: -1,
        ajax: {
            type : 'POST',
            data: function ( d ) {
                d.deal_id = dealId
                d.probe_id = probeId
            },
            url : '/ulab/requirement/getMethodJournalAjax/',
            dataSrc: function (json) {
                return json.data
            }
        },
        columns: [
            {
                data: 'gost_number',
                orderable: false,
                className: 'text-center gost_number grabable'
            },
            {
                data: 'method_view_name',
                orderable: false,
                className: 'method_view_name edit-cell cursor-pointer',
                render: function (data, type, item) {
                    if ( item.is_actual == 0 ) {
                        return `<div class="text-red is-invalid">${item.method_view_name}</div>
                                <div class="invalid-feedback">
                                    <i>Методика не актуальна</i>
                                </div>`
                    } else if ( item.in_field == 0 ) {
                        return `<div class="text-dark-blue is-invalid">${item.method_view_name}</div>
                                <div class="text-dark-blue invalid-feedback">
                                    <i>Методика не в области аккредитации</i>
                                </div>`
                    } else if ( item.is_confirm == 0 ) {
                        return `<div class="text-yellow is-invalid">${item.method_view_name}</div>
                                <div class="text-yellow invalid-feedback">
                                    <i>Методика не проверена</i>
                                </div>`
                    }

                    return `${item.method_view_name}`
                }
            },
            {
                data: 'tu_view_name',
                orderable: false,
                className: 'tu_view_name edit-cell cursor-pointer'
            },
            {
                data: 'assigned_name',
                orderable: false,
                className: 'assigned_name edit-cell cursor-pointer'
            },
            {
                data: 'price',
                width: "111px",
                orderable: false,
                className: 'price edit-cell cursor-pointer'
            },
            {
                data: 'edit',
                orderable: false,
                className: 'text-end',
                render: function (data, type, item) {
                    return `
                        <button type="button" class="btn btn-danger btn-sm btn-square-sm delete-method" data-gtp_id="${item.id}">
                            <i class="fa-solid fa-xmark"></i>
                        </button>`
                }
            },
        ],
        language: dataTablesSettings.language,
        initComplete: function (settings) {
            let api = this.api()

            if ( typeRequest == 9 ) { // 9 - ид гос заявок
                let column = api.column(4)
                column.visible(false)
            }

            api.columns().every(function () {
                let timeout
                $(this.header()).closest('thead').find('.search:eq('+ this.index() +')').on( 'input', function () {
                    clearTimeout(timeout)
                    const searchValue = this.value
                    timeout = setTimeout(function () {
                        api
                            .column($(this).parent().index())
                            .search(searchValue)
                            .draw()
                    }.bind(this), 1000)
                })
            })
        }
    })

    // двигаем строки
    journal.on('row-reordered', function (e, diff, edit) {
        let dataList = []
        $.each(edit.nodes, function (i, item) {
            let data = journal.row(item).data()
            data.newPosition = diff[i].newPosition

            dataList.push(data)
        })

        if ( dataList.length > 0 ) {
            $.ajax({
                url: "/ulab/requirement/changeGostNumberAjax",
                data: {
                    "data": dataList,
                },
                dataType: "text",
                method: "POST",
                success: function (json) {
                    journal.ajax.reload()
                }
            })
        }
    })


    // удалить методику
    journal.on('click', '.delete-method', function () {
        if ( confirm("Подтвердите удаление методики.\nВнимание, методика, у которой есть результат испытания, не удалится.") ) {
            const id = $(this).data('gtp_id')
            const tzId = $('#tz_id').val()

            $.ajax({
                url: "/ulab/requirement/deleteMethodAjax/",
                data: {
                    "id": id,
                    "tz_id": tzId,
                },
                dataType: "json",
                method: "POST",
                success: function (json) {
                    if ( json.success ) {
                        journal.ajax.reload()

                        $spanMethodCount.text(--rowData.count_methods)

                        $strTotal.text(json.data.price_ru)
                        $inputTotal.val(json.data.price)
                        $inputDiscount.val(json.data.price_discount)
                    } else {
                        alert(`При удалении возникла ошибка: ${json.error}`)
                    }
                }
            })
        }

        return false
    })


    // редактируем ячейку
    journal.on('click', 'td.edit-cell', function (e) {

        const $thisCell = $(this)

        let data = journal.row($thisCell.closest('tr')).data()

        if ( $thisCell.hasClass('assigned_name') ) {
            $thisCell.html(`<select class="form-control">` + getHtmlOptionAssignedByMethod(data.new_method_id, data.assigned_id) + `</select>`)
        } else if ( $thisCell.hasClass('tu_view_name') ) {
            $thisCell.html(`<select class="form-control select2">` + getHtmlOptionsNormDoc(normDocList, data.norm_doc_method_id) + `</select>`)
        } else if ( $thisCell.hasClass('method_view_name') ) {
            $thisCell.html(`<select class="form-control select2">` + getHtmlOptionsMethod(methodList, data.new_method_id) + `</select>`)
        } else if ( $thisCell.hasClass('price') ) {
            $thisCell.html($(`<input type="number" class="form-control" value="${data.price}">`))
        } else {
            return false
        }

        $thisCell.removeClass('edit-cell')
        $thisCell.addClass('save-cell')

        $thisCell.find('.select2').select2({
            theme: 'bootstrap-5',
            templateResult: formatState,
            templateSelection: formatState,
        })
    })


    // сохраняем изменения в ячейке
    journal.on('change', 'td.save-cell select, td.save-cell input', function () {
        const tzId = $('#tz_id').val()
        let $thisCell = $(this).closest('td')
        let $thisRow = $(this).closest('tr')
        let val = $(this).val()
        let data = journal.row($thisRow.closest('tr')).data()
        let cellName = ''

        if ( $thisCell.hasClass('assigned_name') ) {
            cellName = 'assigned'
        } else if ( $thisCell.hasClass('tu_view_name') ) {
            cellName = 'tu'
        } else if ( $thisCell.hasClass('method_view_name') ) {
            cellName = 'method'
        } else if ( $thisCell.hasClass('price') ) {
            cellName = 'price'
        } else {
            return false
        }

        $.ajax({
            url: "/ulab/requirement/updateMethodAjax/",
            data: {
                "id": data.id,
                "tz_id": tzId,
                "val": val,
                "field": cellName,
            },
            dataType: "json",
            method: "POST",
            success: function (json) {
                if ( json.success ) {
                    journal.ajax.reload()

                    $strTotal.text(json.data.price_ru)
                    $inputTotal.val(json.data.price)
                    $inputDiscount.val(json.data.price_discount)
                } else {
                    alert(`При обновлении возникла ошибка: ${json.error}`)
                }
            }
        })
    })
}